<?php
/*
Template Name: Page with Sidebar
*/

get_header('with-title');

?>

<section id="main" class="col-sm-9 col-md-9 sidebar-right" role="main">
    <?php the_content(); ?>
</section>


<aside id="sidebar" class="col-sm-3 col-md-3" role="complementary">
	<?php 
        if (is_active_sidebar('elementor_sidebar')) {
            dynamic_sidebar('elementor_sidebar');
        }
    ?>
</aside>

<?php

get_footer();
