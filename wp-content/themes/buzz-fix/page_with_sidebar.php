<?php
/*
Template Name: Page with Sidebar
*/

get_header();

?>

<section id="main" class="col-sm-12 col-md-8 sidebar-right" role="main">
    <?php the_content(); ?>
</section>


<aside id="sidebar" class="col-sm-12 col-md-4" role="complementary">
	<?php 
        if (is_active_sidebar('elementor_sidebar')) {
            dynamic_sidebar('elementor_sidebar');
        }
    ?>
</aside>

<?php

get_footer();
