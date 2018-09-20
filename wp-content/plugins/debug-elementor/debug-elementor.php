<?php
/**
 * Plugin Name: Debug Elementor
 * Plugin URI:  https://wordpress.org/plugins/debug-elementor/
 * Description: Debugging plugin for Elementor to display post/page data saved by Elementor page builder.
 * Version:     1.0.0
 * Author:      Rami Yushuvaev
 * Author URI:  https://GenerateWP.com/
 * Text Domain: debug-elementor
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Debug Elementor.
 *
 * A general class that activates "Debug Elementor" plugin.
 *
 * @since 1.0.0
 */
final class Debug_Elementor {

    /**
     * Instance.
	 *
	 * Holds the plugin instance.
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     *
     * @var Debug_Elementor
     */
    private static $instance;

	/**
     * Feed name.
     *
     * Holds the feed name when accessed directly from the post feed URL (`/feed/elementor/`).
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @var string
     */
    public $feed = 'elementor';

    /**
	 * Plugin constructor.
	 *
	 * Initializing the plugin.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function __construct() {
        $this->actions();
    }

    /**
     * Get Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     * @return Debug_Elementor
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self;

			/**
			 * Debug Elementor loaded.
			 *
			 * Fires when the plugin was fully loaded and instantiated.
			 *
			 * @since 1.0.0
			 */
			do_action( 'debug-elementor-loaded' );
        }

        return self::$instance;
    }

	/**
     * Load actions.
     *
     * Load the plugin actions.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function actions() {
		// Text domain
		add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );

		// Elementor Feed (`/feed/elementor/`)
		add_action( 'init', [ $this, 'add_feed' ] );
        add_filter( 'feed_content_type', [ $this, 'feed_content_type' ], 10, 2 );
        add_filter( 'pre_get_posts', [ $this, 'pre_get_posts' ] );

		/**
		 * Debug Elementor actions.
		 *
		 * Fires on plugin init, after it has finished loading but before any headers are sent.
		 *
		 * @since 1.0.0
		 */
		do_action( 'debug-elementor-actions' );
	}

	/**
     * Load text domain.
     *
     * Load the plugin translation text domain.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'debug-elementor' );
    }

	/**
     * Add Elementor feed.
	 *
	 * Register new WordPress feed for Elementor.
     *
     * Can be accessed directly from the post feed URL (`/feed/elementor/`).
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function add_feed() {
        add_feed( $this->feed, [ $this, 'feed_output' ] );
    }

    /**
     * Content type.
     *
     * Return the correct HTTP header for Content-type.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @param string $content_type Content type indicating the type of data that
	 *                             the feed contains.
     * @param string $type         Feed type.
     *
     * @return string Content type.
     */
    public function feed_content_type( $content_type, $type ) {
        if ( $this->feed === $type ) {
            return 'application/json';
        }

        return $content_type;
    }

    /**
     * Pre get posts.
     *
     * Modify the feed query.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @param WP_Query $query The WP_Query instance.
     *
     * @return WP_Query The WP_Query instance.
     */
    public function pre_get_posts( $query ) {
        if ( $query->is_main_query() && $query->is_feed( $this->feed ) ) {
            // show all results, no pagination
            $query->set( 'nopaging', true );
        }

        return $query;
    }

    /**
     * Feed output
     *
     * Return the feed output.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function feed_output() {
        if ( have_posts() ) {
            while( have_posts() ) {
                the_post();

                $data = [
					'id' => get_the_id(),
					'title' => get_the_title(),
					'excerpt' => get_the_excerpt(),
					'url' => get_permalink(),
					'page_template' => get_post_meta( get_the_ID(), '_wp_page_template', true ),
					'featured_image' => [
						'url' => get_the_post_thumbnail_url(),
						'id' => get_post_thumbnail_id(),
					],
					'author' => [
						'id' => get_the_author_meta( 'ID' ),
						'name' => sprintf( '%s %s', get_the_author_meta( 'first_name' ), get_the_author_meta( 'last_name' ) ),
						'display_name' => get_the_author_meta( 'display_name' ),
					],
					'date' => [
						//'publish' => sprintf( '%s %s', get_the_date(), get_the_time() ),
						//'last_update' => sprintf( '%s %s', get_the_modified_date(), get_the_modified_time() ),
						'publish' => mysql2date( 'Y-m-d H:i:s', get_post()->post_date, false ),
						'last_update' => mysql2date( 'Y-m-d H:i:s', get_post()->post_modified, false ),
					],
					'elementor' => [
						'edit_mode' => get_post_meta( get_the_ID(), '_elementor_edit_mode', true ),
						'version' => get_post_meta( get_the_ID(), '_elementor_version', true ),
						'data' => json_decode( get_post_meta( get_the_ID(), '_elementor_data', true ) ),
						'css' => get_post_meta( get_the_ID(), '_elementor_css', true ),
					],
					//'post_meta' => get_post_meta( get_the_ID() ),
                ];

				/**
				 * Debug Elementor data.
				 *
				 * Filters the data displayed in the feed, before it's encoded as JSON.
				 *
				 * @since 1.0.0
				 *
				 * @param array $data    Data to return on feed.
				 * @param int   $post_id The post ID.
				 */
				$data = apply_filters( 'debug-elementor-data', $data, get_the_id() );

                echo json_encode( $data );
            }
        }
    }

}

/**
 * Debug Elementor.
 *
 * @since 1.0.0
 *
 * @return Debug_Elementor
 */
function debug_elementor() {
    return Debug_Elementor::get_instance();
}
add_action( 'plugins_loaded', 'debug_elementor', 0 );
