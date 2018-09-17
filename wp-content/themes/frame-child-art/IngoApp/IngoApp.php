<?php

class IngoApp {

    private $_productCategories = null;

    public function init()
    {
        $this->_initWidgets();
        $this->_includeStyles();
        $this->_includeScripts();
    }


    public function addForm($formId, $formClass)
    {
        $form = get_post($formId);

        return join('', [
            "<div class='formContainer $formClass'>",
                '<div class="formWrapper">',
                    '<h3 class="formTitle col-title">' . __($form->post_title, 'ingo') . '</h3>',
                    do_shortcode("[pojo-form id='$formId']"),
                '</div>',
            '</div>'
        ]);
    }


    private function _includeStyles()
    {
        function ingoStyles() {
            wp_enqueue_style(
                'custom-style',
                get_stylesheet_directory_uri() . '/assets/css/custom_style.css',
                [],
                '1.0.2'
            );
        }

        add_action( 'wp_enqueue_scripts', 'ingoStyles', 800 );
    }

    private function _includeScripts()
    {

        function nikoletiScripts() {
            wp_enqueue_script(
                'ingo-app',
                get_stylesheet_directory_uri() . '/assets/js/app.js',
                ['jquery'],
                '1.0',
                true
            );
        }

        add_action( 'wp_enqueue_scripts', 'nikoletiScripts' );
    }



    public function renderProductCategoryPageContent()
    {
        echo join('', [
            '<div class="categoryProductsContainer container-fluid clearfix">',
                '<div class="sidebar col-md-3 col-sm-12 ">',
        			//do_shortcode( $this->getCategoriesAccordionMarkup() ),

        		'<div class="accordion-wrapper"><div class="accordion-title"><h2>קטלוג מוצרים</h2></div>',

                    //"<h3 class='col-title'>" . __('קטלוג מוצרים', 'ingo') . "</h3>",
                    $this->getCategoriesAccordionMarkup(),
        		'</div>',
                    $this->addForm(937, 'hidden-sm hidden-xs'), // will be hidden on small screens
                '</div>',

                //'<div class="col-md-1 hidden-sm hidden-xs"></div>',

                '<div class="categoryProductsList col-md-9 col-sm-12 clearfix">'
        ]);

        $this->renderCategoryProduct();

        echo join('', [
                '</div>', // categoryProductsList
                $this->addForm(937, 'hidden-md hidden-lg'), // will be shown only on small screens
            '</div>' // categoryProductsContainer
        ]);
    }



    public function renderCategoryProduct()
    {
        $this->removeWoocommerceSortingDropdown();
        $this->removeWoocommerceResultCount();

        if ( apply_filters( 'woocommerce_show_page_title', true ) ) {
            echo(join('', [
                "<h3 class='col-title'>",
                    $this->getCurrentCategoryName(),
                    ' <span>(' . $this->getCurrentCategoryProductsCount(). ')</span> ',
                "</h3>"
            ]));
	}

	do_action( 'woocommerce_archive_description' );
    //global $wp_query;
    //$wp_query->set( 'cat', $this->getCurrentCategoryId() );

	if ( have_posts() ) {

            do_action( 'woocommerce_before_shop_loop' );

            woocommerce_product_loop_start();

            woocommerce_product_subcategories();

            while ( have_posts() ) {
                the_post();
                wc_get_template_part( 'content', 'product' );
            }

            woocommerce_product_loop_end();

            do_action( 'woocommerce_after_shop_loop' );

        } elseif ( ! woocommerce_product_subcategories( ['before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ] ) ) {

            do_action( 'woocommerce_no_products_found' );
        }


    }



    public function getCategoriesAccordionMarkup()
    {
       $allCategories = $this->getProductCategories();

       $accordionHtml = '[accordion]';

       foreach ($allCategories as $cat) {
           if ($cat->category_parent) continue;

           if ($cat->cat_ID === $this->getCurrentSupperCategoryId()) {
               $state = 'open';
           } else {
               $state = 'closed';
           }

           /**
             *  Check if current catregory has children
             *  if so, then render +/- controls and subcategories list
             *  if not render direct link to category content without +/- controls
             */

                // build link to category items list
                $link = "<a href=\"".get_category_link( $cat->cat_ID )."\">{$cat->cat_name}</a>";
            if( !$this->category_has_children( $cat->cat_ID, 'product_cat' ) ){
                // no plus/minus, only link to category items


                // text to render in accordion tab header
                $accordionHtml .= "[accordion-item class=clearfix title='<span class=\"title\">{$link}</span>' state={$state}]";

                // closing current accordion item
                $accordionHtml .= '[/accordion-item]';

                // preventing subcategory rendering ( because nothing to render ) and going to next category
                continue;
            }
            else{
                // plus/minus and subcategories
                $accordionHtml .= "[accordion-item class=clearfix title='<div class=\"toggle\"><i class=\"fa fa-sort-desc\" aria-hidden=\"true\"></i></div><span class=\"title\">{$link}</span> <span class=\"plus sign\">+</span><span class=\"minus sign\">&ndash;</span>' state={$state}]";
            }

           foreach ($allCategories as $subCat) {
               if ($subCat->category_parent !== $cat->cat_ID) continue;

               $subCatId = $subCat->cat_ID;

               $active = $this->getCurrentCategoryId() === $subCatId ? 'active' : '';

               $accordionHtml .= join('', [
                   '<li>',
                        "<a data-test='".$subCatId."' href='". get_category_link($subCatId) . "' class='{$active}'>",
                            $subCat->cat_name,
                        '</a>',
                    '</li>'
                ]);
           }

           $accordionHtml .= '[/accordion-item]';
        }

       $accordionHtml .= '[/accordion]';

        $html = join('', [
            '<div class="subcatsAccordion">',
                do_shortcode($accordionHtml),
            '</div>'
        ]);

        return $html;
    }

    /**
    * Check if given term has child terms
    *
    * @param Integer $term_id
    * @param String $taxonomy
    *
    * @return Boolean
    */
    public function category_has_children( $term_id = 0, $taxonomy = 'category' ) {
        $children = get_categories( array(
            'child_of'      => $term_id,
            'taxonomy'      => $taxonomy,
            'hide_empty'    => false,
            'fields'        => 'ids',
        ) );
        return ( $children );
    }


    public function getProductCategories()
    {
        if (!is_array($this->_productCategories)) {
            $args = array(
                'taxonomy'     => 'product_cat',
                'orderby'      => 'cat_ID',
                'show_count'   => 0,
                'pad_counts'   => 0,
                'hierarchical' => 1,
                'title_li'     => '',
                'hide_empty'   => 0
            );

           $this->_productCategories = get_categories( $args );
        }

        return $this->_productCategories;
    }



    public function getCurrentSupperCategoryId()
    {
        $catID = $this->getCurrentCategoryId();
        $currCat = get_category($catID);

        if ($currCat->category_parent) {
            $catID = $currCat->category_parent;
        }

        return $catID;
    }


    public function getCurrentCategoryId()
    {
        $cat = get_queried_object();
        $catID = $cat->term_id;

        return $catID;
    }


    public function getCurrentCategoryName()
    {
        $currCatId= $this->getCurrentCategoryId();
        $currCat = get_category($currCatId);

        return $currCat->name;
    }


    public function getCurrentCategoryProductsCount()
    {
        $productsCount = 0;

        $currCatId= $this->getCurrentCategoryId();
        $currCat = get_category($currCatId);

        $productsCount = $currCat->count;

        foreach ($this->_productCategories as $pCat) {
            if ($pCat->category_parent === $currCatId) {
                $productsCount += $pCat->count;
            }
        }

        return $productsCount;
    }


    // Remove the sorting dropdown from Woocommerce
    public function removeWoocommerceSortingDropdown()
    {
        remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_catalog_ordering', 30 );
    }


    // Remove the result count from WooCommerce
    public function removeWoocommerceResultCount()
    {
        remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
    }


    private function _initWidgets()
    {
        add_action( 'widgets_init', function(){
            register_sidebar( [
		'name'          => __( 'Single product discription bottom', 'ingo' ),
		'id'            => 'single_product_description_bottom_widget_zone',
		'description'   => __( 'Woocommerce single product description bottom', 'ingo' ),
		'before_widget' => '<section id="%1$s" class="widget singleProductDescrBottomWidgetZone">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
            ] );
        });
    }

}

