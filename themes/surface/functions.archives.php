<?php
	
/*/////////////////////////////////////////////////////////////////////
	Smart Archives
/////////////////////////////////////////////////////////////////////*/
add_action('publish_post', 'smart_archives_generate'); // generate archives after a new post
add_action('delete_post', 'smart_archives_generate'); // generate archives after a post is deleted
add_action('trash_post', 'smart_archives_generate'); // generate archives after a post is trashed

function smart_archives_filename($id) {
	$post_type	= get_post_type($id);
	$post_type	= empty($post_type) ? 'post' : $post_type;
	$category	= template_taxonomy_single_object('category', $id);
	
	$filename	= 'archive-' . $post_type . (!empty($category) && !empty($category->slug) ? '-' . $category->slug : '');
	$extension	= '.txt';
	
	return realpath(dirname(__FILE__) . '/../../archives/') . '/' . $filename . $extension;
}

// display archives
function smart_archives($id, $echo = true) {
	$file = smart_archives_filename($id);

	if(!file_exists($file)) {
		smart_archives_generate($id);
	}

	if($echo === true) {
		echo file_get_contents($file);
	}
	else {
		return file_get_contents($file);
	}
}

// generate archives
function smart_archives_generate($id) {
    global $wpdb, $PHP_SELF;
    setlocale(LC_ALL,WPLANG); // set localization language; please see instructions
	
	$post_type		= get_post_type($id);
	$post_type		= empty($post_type) ? 'post' : $post_type;
	$category		= template_taxonomy_single_object('category', $id);
	
    $now			= gmdate('Y-m-d H:i:s', (time() + ((get_option('gmt_offset')) * 3600)));  // get the current GMT date
	$excludeMonth	= date('n/Y', strtotime($now));
    $bogusDate		= '/01/2001'; // used for the strtotime() function below
    
	$file			= smart_archives_filename($id);
	$fh				= fopen($file, 'w+');

    $yearsWithPosts = $wpdb->get_results('
        SELECT distinct year(post_date) AS `year`, count(ID) as posts
        FROM ' . $wpdb->posts . '
        WHERE post_type = "' . $post_type . '"
        AND post_status = "publish"
        GROUP BY year(post_date)
        ORDER BY post_date DESC');

    foreach($yearsWithPosts as $currentYear) {
        for($currentMonth = 1; $currentMonth <= 12; $currentMonth++) {
			$sql = 'SELECT wp_posts.*
					FROM ' . $wpdb->posts . '
						
					' . (!empty($category) && !empty($category->term_id) ? 'INNER JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id)' : '') . '
					' . (!empty($category) && !empty($category->term_id) ? 'INNER JOIN wp_term_taxonomy ON (wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id)' : '') . '
					
					WHERE 1 = 1
						
					' . (!empty($category) && !empty($category->term_id) ? 'AND (wp_term_taxonomy.term_id IN (' . $category->term_id . '))' : '') . '
						
					AND wp_posts.post_type = "' . $post_type . '"
					AND wp_posts.post_status = "publish"
					AND year(wp_posts.post_date) = "' . $currentYear->year . '"
					AND month(wp_posts.post_date) = "' . $currentMonth . '"
					GROUP BY wp_posts.ID
					ORDER BY wp_posts.post_date DESC';
			
			if($excludeMonth !== $currentMonth . '/' . $currentYear->year) {
	            $monthsWithPosts[$currentYear->year][$currentMonth] = $wpdb->get_results($sql);
			}
        }
    }

	// get the month name; strftime() should localize
	for($currentMonth = 1; $currentMonth <= 12; $currentMonth++) {
		$monthNames[$currentMonth] = ucfirst(strftime("%b", strtotime($currentMonth . $bogusDate)));
	}

	if($yearsWithPosts) {
		$d = 1;
		foreach($yearsWithPosts as $currentYear) {
			for ($currentMonth = 12; $currentMonth >= 1; $currentMonth--) {
				if ($monthsWithPosts[$currentYear->year][$currentMonth]) {
					$archives .= '<div class="date' . ($d === 1 ? ' active' : '') . '">' . "\r\n";
						$archives .= '<h4><a href="' . get_month_link($currentYear->year, $currentMonth) . '">' . $monthNames[$currentMonth] . ' ' . $currentYear->year . '</a></h4>' . "\r\n";
						$archives .= '<ul class="nav">' . "\r\n";

						$i = 1; $total = count($monthsWithPosts[$currentYear->year][$currentMonth]);
						foreach($monthsWithPosts[$currentYear->year][$currentMonth] as $post) {
							$class	 = array('hentry');
							$class[] = ($i === 1) ? 'f' : '';
							$class[] = ($i === $total) ? 'l' : '';

							$archives .= '<li class="' . join(' ', get_post_class($class, $post->ID)) . '">' . "\r\n";
								$archives .= '<a href="' . get_permalink($post->ID) . '" class="url" rel="bookmark">' . "\r\n";
									$archives .= '<h5 class="entry-title">' . $post->post_title . '</h5>' . "\r\n";
									$archives .= '<p class="entry-date date">' . mysql2date('F Y', $post->post_date) . '</p>' . "\r\n";
									$archives .= '<div class="entry-summary">' . apply_filters('the_excerpt', $post->post_excerpt) . '</div>' . "\r\n";
								$archives .= '</a>';
							$archives .= '</li>' . "\r\n\r\n";

							$i++;
						}
						$archives .= '</ul>' . "\r\n";
					$archives .= '</div>' . "\r\n";
					$d++;
				}
			}
		}
	}
	
	fwrite($fh, $archives); // write archives to file
	fclose($fh); // close archives file
}