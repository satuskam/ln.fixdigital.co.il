<?php

    global $pnTheme;

?>
<!DOCTYPE html>
<html <?= language_attributes() ?> style="background-image: url(<?= get_theme_mod('common_background_image') ?>)">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <?php wp_head(); ?>

  </head>

  <body <?php body_class(); ?>>
    <div class="wrapper">
      <div class="header-wrapper-right">
        <?php

          if (is_active_sidebar('gallery_page_header')) {
              dynamic_sidebar('gallery_page_header');
          }
        ?>
      </div>


