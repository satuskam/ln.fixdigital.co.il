<?php
/**
 * The main template file.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();

?>

<section id="main" role="main">

<?php the_content(); ?>

</section>

<?php

get_footer();