Classy
======

Classy is a set of classes for WordPress, which wraps post types (Posts, Pages and custom post types) and users in a class.
It includes common methods which replicate built in functionality (such as `the_content` and `the_permalink`), but means you
can easily override their behaviour per post type without having to modify the view template files.

The codebase also includes some started classes for custom post types, such as “People” and “Features Boxes”, and their
corresponding view template files.

***Coming Soon…***

##Examples##

###Post Example###

    <?php
    $classy_post = Classy_Post::find_by_id(get_the_ID());
    $classy_post->get_ID();
    $classy_post->get_title();
    $classy_post->get_content();
    $classy_post->the_attr('class');
    $classy_post->the_attr('data');
    ?>

###Person Example###

*This is a custom post type*. Create the `Classy_Person` class which extends `Classy`, setup the `register_post_type`
method with the details about the post type. You can also optionally setup image sizes using `register_images` and
assign any taxonomies, including custom taxonomies using `register_taxonomies`, both methods are called by default.

    <?php
    $classy_person = Classy_Person::find_by_slug('forename-surname');
    $classy_person->get_ID();
    $classy_person->get_content();
    $classy_person->get_permalink();
    $classy_person->get_full_name(); // uses custom fields, defaults to the_title()
    $classy_person->get_post_type(); // returns 'person' as the custom post type
    $classy_person->get_avatar(); // return the thumbnail for the person, same as get_thumbnail('avatar');
    ?>