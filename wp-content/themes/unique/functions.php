<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function register_my_menu() {
  register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action( 'init', 'register_my_menu' );


/*
 * Elementor simple header
 */
function simpleHeaderWithBannerWidgetInit()
{
    register_sidebar( array(
        'name'          => 'Elementor header',
        'id'            => 'elementor_header',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );

}
add_action( 'widgets_init', 'simpleHeaderWithBannerWidgetInit' );


function footerWidgetInit()
{
    register_sidebar( array(
        'name'          => 'Elementor footer',
        'id'            => 'elementor_footer',
        'before_widget' => '<div class="elementor_sidebar_wrap">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="elementor-sidebar-title">',
        'after_title'   => '</div>',
    ) );

}
add_action( 'widgets_init', 'footerWidgetInit' );

function   addCustomStyles() {
wp_enqueue_style(
    'poza-fix-style1',
    get_template_directory_uri() . '/assets/css/style.css'
);


wp_enqueue_style(
    'poza-fix-style2',
    get_template_directory_uri() . '/assets/css/rtl.css'
);

wp_enqueue_style(
    'poza-fix-style3',
    get_template_directory_uri() . '/assets/css/bootstrap.min.css'
);

}
add_action( 'wp_enqueue_scripts', 'addCustomStyles' );

function addCurrentPageTitleShortcode()
{
    $currTitle = '';

    $pagename = get_query_var('pagename');


    if ($pagename) {
        $page = get_page_by_slug($pagename);
    } else {


        $frontpageId = get_option( 'page_on_front' );
        $page = get_post($frontpageId);
    }

    if ($page && $page->post_title) {
        $currTitle = $page->post_title;
    }
    if (is_single())
    {
        $postname = get_the_title();
        $currTitle=$postname;
    }

    return "<span>$currTitle</span>";
}
add_shortcode('current-page-title', 'addCurrentPageTitleShortcode');


add_theme_support( 'post-thumbnails' );

add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

// function wp_head_func()
// {
//     echo "test44";
// }
add_action( 'wp_head', 'wp_head_func' );


function wp_head_func()
{
    $bigstring="<link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Varela+Round:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic|Roboto:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic|Lato:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&subset=hebrew'>";
    
    $bigstring.="<style type='text/css'>body{background-color: rgba(255, 255, 255, 1);background-position: top center;background-repeat: repeat;background-size: auto;background-attachment: scroll;color: #6d6d6d; font-family: 'Roboto', Arial, sans-serif; font-weight: normal; font-size: 15px;line-height: 1.9;}div.logo-text a{color: #000000; font-family: 'Varela Round', Arial, sans-serif; font-weight: bold; font-size: 31px;text-transform: none;letter-spacing: 0px;font-style: normal;line-height: 1em;}#header .logo{padding-top: 40px;padding-bottom: 40px;}#top-bar{background-color: rgba(0, 0, 0, 1);background-position: top center;background-repeat: repeat-x;background-size: auto;background-attachment: scroll;}#top-bar, #top-bar .widget-title,#top-bar .form-search .field{color: #818181; font-family: 'Roboto', Arial, sans-serif; font-weight: normal; font-size: 13px;text-transform: none;letter-spacing: 0px;font-style: normal;line-height: 35px;}#top-bar a{color: #818181;}#top-bar a:hover{color: #ffffff;}#header{background-color: rgba(255, 255, 255, 1);background-position: top center;background-repeat: repeat-x;background-size: auto;background-attachment: scroll;}.sticky-header{background-color: rgba(0, 0, 0, 1);background-position: top center;background-repeat: repeat-x;background-size: auto;background-attachment: scroll;}.sticky-header .sf-menu a,.sticky-header .mobile-menu a{color: #fff; font-family: 'Roboto', Arial, sans-serif; font-weight: normal; font-size: 14px;text-transform: uppercase;letter-spacing: 1px;font-style: normal;line-height: 70px;}.sticky-header .sf-menu a:hover,.sticky-header .sf-menu li.active a,.sticky-header .sf-menu li.current-menu-item > a,.sticky-header .sf-menu li.current-menu-ancestor > a,.sticky-header .mobile-menu a:hover,.sticky-header .mobile-menu li.current-menu-item > a{color: #999;}.sf-menu a, .mobile-menu a{color: #000000; font-family: 'Varela Round', Arial, sans-serif; font-weight: 600; font-size: 15px;text-transform: none;letter-spacing: 1.7px;font-style: normal;line-height: 60px;}.sf-menu a:hover,.sf-menu li.active a, .sf-menu li.current-menu-item > a,.sf-menu li.current-menu-ancestor > a,.mobile-menu a:hover,.mobile-menu li.current-menu-item > a{color: #0a0a0a;}.nav-main .sf-menu .sub-menu li{background-color: #ffffff;}.sf-menu .sub-menu li:hover,.sf-menu .sub-menu li.current-menu-item{background-color: #f2f2f2;}.nav-main .sf-menu .sub-menu li a{color: #232323; font-family: 'Varela Round', Arial, sans-serif; font-weight: normal; font-size: 14px;text-transform: none;letter-spacing: 0px;font-style: normal;line-height: 46px;}.nav-main .sf-menu .sub-menu li:hover > a,.nav-main .sf-menu .sub-menu li.current-menu-item > a{color: #0a0a0a;}#title-bar{height: 100px;line-height: 100px;}#title-bar.title-bar-style-custom_bg .title-bar-default{background-color: rgba(255, 255, 255, 0);background-position: center center;background-repeat: repeat;background-size: cover;background-attachment: scroll;}#title-bar .title-primary{color: #000000; font-family: 'Varela Round', Arial, sans-serif; font-weight: 500; font-size: 24px;text-transform: none;letter-spacing: 1px;font-style: normal;}#title-bar div.breadcrumbs, #title-bar div.breadcrumbs a{color: #939393; font-family: 'Varela Round', Arial, sans-serif; font-weight: normal; font-size: 12px;text-transform: none;letter-spacing: 0px;font-style: italic;}a{color: #d8d8d8;}a:hover, a:focus{color: #878787;}::selection{color: #ffffff;background: #000000;}::-moz-selection{color: #ffffff;background: #000000;}h1{color: #171717; font-family: 'Varela Round', Arial, sans-serif; font-weight: bold; font-size: 33px;text-transform: none;letter-spacing: 2px;font-style: normal;line-height: 25px;}h2{color: #000000; font-family: 'Varela Round', Arial, sans-serif; font-weight: normal; font-size: 29px;text-transform: none;letter-spacing: 2px;font-style: normal;line-height: 1.3em;}h3{color: #000000; font-family: 'Varela Round', Arial, sans-serif; font-weight: normal; font-size: 25px;text-transform: none;letter-spacing: 4px;font-style: normal;line-height: 1.5em;}h4{color: #000000; font-family: 'Varela Round', Arial, sans-serif; font-weight: bold; font-size: 17px;text-transform: none;letter-spacing: 0px;font-style: normal;line-height: 1.6em;}h5{color: #a8a8a8; font-family: 'Varela Round', Arial, sans-serif; font-weight: normal; font-size: 15px;text-transform: none;letter-spacing: 0px;font-style: italic;line-height: 1.5em;}h6{color: #000000; font-family: 'Varela Round', Arial, sans-serif; font-weight: normal; font-size: 15px;text-transform: none;letter-spacing: 0px;font-style: normal;line-height: 1.7em;}.image-link .overlay-title,.image-link .overlay-title a,.image-link .overlay-title a:hover,.image-link .overlay-title a.button,.image-link .overlay-title a.button:hover{color: #ffffff; font-family: 'Varela Round', Arial, sans-serif; font-weight: normal; font-size: 15px;text-transform: none;letter-spacing: 0px;font-style: normal;}.image-link .entry-date,.woocommerce span.onsale{background-color: #000000;}.image-link .entry-date .entry-date-day{color: #ffffff; font-family: 'Roboto', Arial, sans-serif; font-weight: normal; font-size: 30px;text-transform: uppercase;letter-spacing: -2px;font-style: normal;}.image-link .entry-date .entry-date-month,.woocommerce span.onsale{color: #ffffff; font-family: 'Varela Round', Arial, sans-serif; font-weight: normal; font-size: 13px;text-transform: none;letter-spacing: 0px;font-style: normal;}.blog-item h3.media-heading{color: #000000; font-family: 'Varela Round', Arial, sans-serif; font-weight: bold; font-size: 29px;text-transform: none;letter-spacing: 0px;font-style: normal;line-height: 1.2;}.entry-meta > span{color: #a7a7a7; font-family: 'Varela Round', Arial, sans-serif; font-weight: normal; font-size: 12px;text-transform: none;letter-spacing: 0px;font-style: normal;line-height: 1em;}h4.grid-heading{color: #141414; font-family: 'Varela Round', Arial, sans-serif; font-weight: bold; font-size: 21px;text-transform: none;letter-spacing: 0px;font-style: normal;line-height: 26px;}.gallery-item h4.grid-heading{color: #ffffff; font-family: 'Varela Round', Arial, sans-serif; font-weight: bold; font-size: 21px;text-transform: none;letter-spacing: 2px;font-style: normal;line-height: 0.8em;}.gallery-item h4.grid-heading small{color: #bababa; font-family: 'Varela Round', Arial, sans-serif; font-weight: normal; font-size: 13px;text-transform: none;letter-spacing: 0px;font-style: normal;}.single .entry-meta > span{color: #848484; font-family: 'Varela Round', Arial, sans-serif; font-weight: normal; font-size: 12px;text-transform: none;letter-spacing: 1px;font-style: normal;line-height: 1;}.entry-excerpt{color: #6c6c6c; font-family: 'Roboto', Arial, sans-serif; font-weight: 400; font-size: 15px;text-transform: none;letter-spacing: 0px;font-style: normal;line-height: 22px;}#primary #breadcrumbs,#primary #breadcrumbs a{color: #6c6c6c; font-family: 'Lato', Arial, sans-serif; font-weight: normal; font-size: 13px;text-transform: none;letter-spacing: 0px;font-style: normal;line-height: 1em;}.pagination > li > a,.pagination > li.active > a,.pagination > li > a:hover,.pagination > li.active > a:hover,nav.post-navigation a,.category-filters li a{color: #8e8e8e; font-family: 'Varela Round', Arial, sans-serif; font-weight: normal; font-size: 13px;text-transform: none;letter-spacing: 0px;font-style: normal;line-height: 1.5em;}.pagination > li.active > a,.pagination > li > a:hover,.pagination > li.active > a:hover,.pagination > li.active > a:focus,.category-filters li a:hover,.category-filters li a.active{background-color: #e8e8e8;}#sidebar{color: #666666; font-family: 'Roboto', Arial, sans-serif; font-weight: 300; font-size: 15px;text-transform: none;letter-spacing: 0px;font-style: normal;line-height: 1.9em;}#sidebar a{color: #000000;}#sidebar a:hover{color: #878787;}#sidebar .widget-title{color: #000000; font-family: 'Varela Round', Arial, sans-serif; font-weight: bold; font-size: 17px;text-transform: none;letter-spacing: 0px;font-style: normal;line-height: 50px;}#footer{background-color: rgba(10, 10, 10, 1);background-position: top center;background-repeat: repeat;background-size: auto;background-attachment: scroll;color: #878787; font-family: 'Roboto', Arial, sans-serif; font-weight: normal; font-size: 15px;text-transform: none;letter-spacing: 0px;font-style: normal;line-height: 1.9em;}#footer a{color: #878787;}#footer a:hover{color: #ffffff;}#sidebar-footer .widget-title{color: #ffffff; font-family: 'Varela Round', Arial, sans-serif; font-weight: bold; font-size: 19px;text-transform: none;letter-spacing: 1px;font-style: normal;line-height: 2em;}#copyright{background-color: rgba(0, 0, 0, 1);background-position: top center;background-repeat: repeat;background-size: auto;background-attachment: scroll;color: #ffffff; font-family: 'Roboto', Arial, sans-serif; font-weight: 400; font-size: 11px;text-transform: uppercase;letter-spacing: 1px;font-style: normal;line-height: 80px;}#copyright a{color: #bcbcbc;}#copyright a:hover{color: #ffffff;}#pojo-scroll-up{width: 50px;height: 50px;line-height: 50px;background-color: rgba(51, 51, 51, 0.6);background-position: top center;background-repeat: repeat;background-size: auto;background-attachment: scroll;}#pojo-scroll-up a{color: #eeeeee;}#pojo-a11y-toolbar .pojo-a11y-toolbar-overlay{background-color: #ffffff;}#pojo-a11y-toolbar .pojo-a11y-toolbar-overlay ul.pojo-a11y-toolbar-items li.pojo-a11y-toolbar-item a, #pojo-a11y-toolbar .pojo-a11y-toolbar-overlay p.pojo-a11y-toolbar-title{color: #333333;}#pojo-a11y-toolbar .pojo-a11y-toolbar-toggle a{color: #ffffff;background-color: #4054b2;}#pojo-a11y-toolbar .pojo-a11y-toolbar-overlay ul.pojo-a11y-toolbar-items li.pojo-a11y-toolbar-item a.active{background-color: #4054b2;color: #ffffff;}.image-link .overlay-image{background: rgba(0, 0, 0, 0.5);}#header .nav-main,.sf-menu .sub-menu li,.sf-menu .sub-menu li:last-child,.media:hover .image-link,nav.post-navigation,#sidebar .widget-title{border-color: #cdcdcd;}.sf-menu > li > .sub-menu > li:first-child:before{border-bottom-color: #cdcdcd;}.sf-menu a:hover,.sf-menu li.active a, .sf-menu li.current-menu-item > a,.sf-menu li.current-menu-ancestor > a,.mobile-menu a:hover,.mobile-menu li.current-menu-item > a,#sidebar .widget-title:after{border-color: #000000;}#top-bar ul.social-links li a .social-icon:before{width: 35px;height: 35px;line-height: 35px;}.sf-menu li:hover ul, .sf-menu li.sfHover ul{top: 60px;}.navbar-toggle .icon-bar,.navbar-toggle:hover .icon-bar, .navbar-toggle:focus .icon-bar{background-color: #000000;}.sf-menu > li > .sub-menu > li:first-child:after{border-bottom-color: #ffffff;}.sf-menu > li > .sub-menu > li:first-child:hover:after,.sf-menu > li > .sub-menu > li.current-menu-item:first-child:after{border-bottom-color: #f2f2f2;}.sticky-header .logo{color: #fff;}.sticky-header .navbar-toggle .icon-bar,.sticky-header .navbar-toggle:hover .icon-bar,.sticky-header .navbar-toggle:focus .icon-bar{background-color: #fff;}#pojo-a11y-toolbar .pojo-a11y-toolbar-overlay, #pojo-a11y-toolbar .pojo-a11y-toolbar-overlay ul.pojo-a11y-toolbar-items.pojo-a11y-links{border-color: #4054b2;}body.pojo-a11y-focusable a:focus{outline-style: solid !important;outline-width: 1px !important;outline-color: #FF0000 !important;}#pojo-a11y-toolbar{top: 100px !important;}          .image-link .overlay-image:before,.image-link .overlay-image:after {border-color: #ffffff;}
                 article.sticky:before {background-color: #000000;}
                 .author-info {background-color: #000000;color: #ffffff;}
                 .author-info .author-link, .author-info h4 {color: #ffffff;}
                 .widget_tag_cloud a, #sidebar-footer .widget_tag_cloud a {color: #ffffff;}
                 .widget_tag_cloud a:hover, #sidebar-footer .widget_tag_cloud a:hover {background-color: #000000; color: #ffffff;}
                 ul.social-links li a .social-icon:before {background-color: #000000;}
                 ul.social-links li a .social-icon:before {color: #ffffff; }
                 ul.social-links li a:hover .social-icon:before {background-color: #ffffff; }
                 ul.social-links li a:hover .social-icon:before {color: #000000; }
                 input[type='submit'],.button,.button.size-small,.button.size-large,.button.size-xl,.button.size-xxl {background-color: #000000; border-color: #000000; color: #ffffff;}
                 input[type='submit']:hover,.button:hover,.button.size-small:hover,.button.size-large:hover,.button.size-xl:hover, .button.size-xxl:hover {background: #ffffff; border-color: #000000; color: #000000;}@media (max-width: 767px) { #pojo-a11y-toolbar { top: 50px !important } }.sticky-header .sf-menu > li > a {
         padding: 0;
     }</style>";
    
     $bigstring.="<style type='text/css' media='print'>#wpadminbar { display:none; }</style>
     <style type='text/css' media='screen'>
       /*  html { margin-top: 32px !important; } */
         * html body { margin-top: 32px !important; }
         @media screen and ( max-width: 782px ) {
         /*    html { margin-top: 46px !important; } */
             * html body { margin-top: 46px !important; }
         }
     </style>";

    echo "$bigstring";
};

// Put your custom code here.
