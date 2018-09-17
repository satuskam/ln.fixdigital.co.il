<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_My_Custom_Elementor_Price extends Widget_Base {

   public function get_id() {
      return 'product-price';
   }

   public function get_name() {
      return 'product-price';
   }

   public function get_title() {
      return __( 'Product Price', 'elementor-custom-element' );
   }

   public function get_icon() {
      // Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
      return 'post-list';
   }

   protected function render( $instance = [] ) {
      $price1=get_field('price1');
      $price2=get_field('price2');
      if($price1&&$price2)
      {
         echo '<h5>';
         echo  '<span>₪'.get_field('price1') . '</span> ליחיד ' .'<span>₪'. get_field('price2') . '</span> לזוג';
         echo '</h5>';
         return;
      }
      if($price1)
      {
         echo '<h5';
         echo  '<span>₪'.get_field('price1') . '</span> ליחיד ';
         echo '</h5>';
         return;
      }
      if($price2)
      {
         echo '<h5>';
         echo  '<span>₪'.get_field('price2') . '</span> לזוג ';
         echo '</h5>';
         return;
      }
   }

   protected function content_template() {}

   public function render_plain_content( $instance = [] ) {}

}


class Widget_My_Custom_Elementor_Discount extends Widget_Base {

   public function get_id() {
      return 'product-discount';
   }

   public function get_name() {
      return 'product-discount';
   }

   public function get_title() {
      return __( 'Product Discount', 'elementor-custom-element' );
   }

   public function get_icon() {
      // Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
      return 'post-list';
   }

   protected function render( $instance = [] ) {
      if (get_field('discount'))
      {
         echo   '<h5>'.get_field('discount') . '% הנחה בהזמנה Online'.'</h5>';
      }

   }

   protected function content_template() {}

   public function render_plain_content( $instance = [] ) {}

}

class Widget_My_Custom_Elementor_Links extends Widget_Base {

   public function get_id() {
      return 'product-links';
   }

   public function get_name() {
      return 'product-links';
   }

   public function get_title() {
      return __( 'Product Links', 'elementor-custom-element' );
   }

   public function get_icon() {
      // Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
      return 'post-list';
   }

   protected function render( $instance = [] ) {
      $price1=get_field('price1');
      $price2=get_field('price2');
      echo '         <!-- mark test42 -->';
      if($price1&&$price2)
      {
         ?>
         <!-- mark test42 -->
            <div class="butwrap_left">
               <div class="product-button-left">
                  <a href="/buy?product_id=<?=the_ID()?>&price_id=2">להזמנה Online לזוג</a>
                  <i class="fa fa-caret-left"></i>
               </div>
            </div><div class="butwrap_right">
               <div class="product-button-right">
                  <a href="/buy?product_id=<?=the_ID()?>&price_id=1">להזמנה Online ליחיד</a>
                  <i class="fa fa-caret-left"></i>
               </div>
            </div>
         <?php
         return;
      }
      if($price1)
      {
         ?>
            <div class="butwrap_left">

            </div><div class="butwrap_right">
               <div class="product-button-right">
                  <a href="/buy?product_id=<?=the_ID()?>&price_id=1">להזמנה Online ליחיד</a>
                  <i class="fa fa-caret-left"></i>
               </div>
            </div>
         <?php
         return;
      }
      if($price2)
      {
         ?>
            <div class="butwrap_left">
               <div class="product-button-left">
                  <a href="/buy?product_id=<?=the_ID()?>&price_id=2">להזמנה Online לזוג</a>
                  <i class="fa fa-caret-left"></i>
               </div>
            </div><div class="butwrap_right">

            </div>
         <?php
         return;
      }
   }

   protected function content_template() {}

   public function render_plain_content( $instance = [] ) {}

}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_My_Custom_Elementor_Price() );
Plugin::instance()->widgets_manager->register_widget_type( new Widget_My_Custom_Elementor_Discount() );
Plugin::instance()->widgets_manager->register_widget_type( new Widget_My_Custom_Elementor_Links() );
