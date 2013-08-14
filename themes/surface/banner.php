<?php
$section = Classy_Page::get_the_page();

if(!empty($section) && $section instanceof Classy_Page) {
	$section->the_banner();
}