<?php
/*
Plugin Name: Header and Footer Scripts Fixdigital
Plugin URI: 
Description: Allows you to insert code or text in the header or footer of your WordPress blog 
Version: 0.1.3
Author: satuskam
Author URI: 
License: GPLv2 or later

*/

define('SHFS_PLUGIN_DIR',str_replace('\\','/',dirname(__FILE__)));

if ( !class_exists( 'HeaderAndFooterScriptsFixdigital' ) ) {
	
	class HeaderAndFooterScriptsFixdigital {

		function __construct() {
		
			add_action( 'init', array( &$this, 'init' ) );
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
			add_action( 'wp_head', array( &$this, 'wp_head' ), 10000 );
			add_action( 'wp_footer', array( &$this, 'wp_footer' ), 10000 );
		
		}
		
	
		function init() {
			load_plugin_textdomain( 'insert-headers-and-footers-fixdigital', false, dirname( plugin_basename ( __FILE__ ) ).'/lang' );
		}
	
		function admin_init() {
			register_setting( 'insert-headers-and-footers-fixdigital', 'shfs_insert_header', 'trim' );
			register_setting( 'insert-headers-and-footers-fixdigital', 'shfs_insert_header_is_active', 'boolval' );
			register_setting( 'insert-headers-and-footers-fixdigital', 'shfs_insert_footer', 'trim' );
			register_setting( 'insert-headers-and-footers-fixdigital', 'shfs_insert_footer_is_active', 'boolval' );

			foreach (array('post','page') as $type) 
			{
                            add_meta_box('shfs_header_post_meta', 'Insert Script to &lt;head&gt;', 'shfs_header_meta_setup', $type, 'normal', 'high');
                            add_meta_box('shfs_footer_post_meta', 'Insert Script to &lt;WP footer&gt;', 'shfs_footer_meta_setup', $type, 'normal', 'high');
			}
			
			add_action('save_post','shfs_post_meta_save');
		}
	
		function admin_menu() {
                    $page = add_submenu_page( 'options-general.php', 'Header and Footer Scripts', 'Header and Footer Scripts', 'manage_options', __FILE__, array( &$this, 'shfs_options_panel' ) );
                }
	
		function wp_head() {
                    $isHeaderScriptActive = get_option( 'shfs_insert_header_is_active', false );
                    if ($isHeaderScriptActive) {
                        $meta = get_option( 'shfs_insert_header', '' );
                        if ( $meta != '' ) {
                            echo $meta, "\n";
                        }
                    }

                    $shfs_post_meta = get_post_meta( get_the_ID(), '_inpost_head_script' , TRUE );

                    if ( is_array($shfs_post_meta) ) {
                        if ($shfs_post_meta['is_active']) {
                            echo $shfs_post_meta['code'], "\n";
                        }
                    }
		}
	
		function wp_footer() {
            $test = null;
            
            if ( !is_admin() && !is_feed() && !is_robots() && !is_trackback() ) {
                $isFooterScriptActive = get_option( 'shfs_insert_footer_is_active', false );

                if ($isFooterScriptActive) {
                    $text = get_option( 'shfs_insert_footer', '' );
                    $text = convert_smilies( $text );
                    $text = do_shortcode( $text );
                }
			
                if ( $text != '' ) {
                    echo $text, "\n";
                }
                        
                $shfs_post_meta = get_post_meta( get_the_ID(), '_inpost_footer_script' , TRUE );

                if ( is_array($shfs_post_meta) ) {
                    if ($shfs_post_meta['is_active']) {
                        echo $shfs_post_meta['code'], "\n";
                    }
                }
            }
		}

			
		function fetch_rss_items( $num, $feed ) {
			include_once( ABSPATH . WPINC . '/feed.php' );
			$rss = fetch_feed( $feed );

			// Bail if feed doesn't work
			if ( !$rss || is_wp_error( $rss ) )
			return false;

			$rss_items = $rss->get_items( 0, $rss->get_item_quantity( $num ) );

			// If the feed was erroneous 
			if ( !$rss_items ) {
				$md5 = md5( $feed );
				delete_transient( 'feed_' . $md5 );
				delete_transient( 'feed_mod_' . $md5 );
				$rss = fetch_feed( $feed );
				$rss_items = $rss->get_items( 0, $rss->get_item_quantity( $num ) );
			}

			return $rss_items;
		}
		   
				
		function shfs_options_panel() { ?>
                    <div id="fb-root"></div>
                    <div id="shfs-wrap">
                        <div class="wrap">
                        <?php screen_icon(); ?>
                            <h2>Header and Footer Scripts for Fixdigital- Options</h2>
                            <hr />
                            <div class="shfs-wrap" style="width: auto;float: left;margin-right: 2rem;">

                                <form name="dofollow" action="options.php" method="post">
                                        
                                    <?php
                                        settings_fields( 'insert-headers-and-footers-fixdigital' );
                                        
                                        $isHeaderScriptActive = get_option( 'shfs_insert_header_is_active' ) ? true : false;
                                        $isFooterScriptActive = get_option( 'shfs_insert_footer_is_active' ) ? true : false;
                                    ?>

                                    <h3 class="shfs-labels" for="shfs_insert_header">Scripts in header:</h3>
                                    <textarea rows="10" cols="80" id="insert_header" name="shfs_insert_header"><?php echo esc_html( get_option( 'shfs_insert_header' ) ); ?></textarea>
                                    <br />
                                    <input type="checkbox" name="shfs_insert_header_is_active" value="1" <?= $isHeaderScriptActive ? 'checked' : '' ?> > Is Active
                                    <br />
                                    <br />
                                    
                                    <hr />
                                    
                                    <h3 class="shfs-labels footerlabel" for="shfs_insert_footer">Scripts in footer:</h3>
                                    <textarea rows="10" cols="80" id="shfs_insert_footer" name="shfs_insert_footer"><?php echo esc_html( get_option( 'shfs_insert_footer' ) ); ?></textarea>
                                    <br />
                                    <input type="checkbox" name="shfs_insert_footer_is_active" value="1" <?= $isFooterScriptActive ? 'checked' : '' ?> > Is Active
                                    <br />
                                    <p class="submit">
                                        <input class="button button-primary" type="submit" name="Submit" value="Save settings" /> 
                                    </p>
                                </form>
                            </div>

                        </div>
                    </div>
				
                    <!-- Place this tag after the last widget tag. -->
                    <script type="text/javascript">
                      (function() {
                            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                            po.src = 'https://apis.google.com/js/platform.js';
                            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                      })();
                    </script>


                    <script>(function(d, s, id) {
                      var js, fjs = d.getElementsByTagName(s)[0];
                      if (d.getElementById(id)) return;
                      js = d.createElement(s); js.id = id;
                      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=383137358414970";
                      fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>

                    <?php
		}
	}

	function shfs_header_meta_setup()
	{
		global $post;
	 
		// using an underscore, prevents the meta variable
		// from showing up in the custom fields section
		$headerScriptMeta = get_post_meta($post->ID,'_inpost_head_script',TRUE);
	 
		// instead of writing HTML here, lets do an include
		include(SHFS_PLUGIN_DIR . '/headerScriptMeta.php');
	 
		// create a custom nonce for submit verification later
		echo '<input type="hidden" name="shfs_post_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
	}
        
	function shfs_footer_meta_setup()
	{
		global $post;
	 
		// using an underscore, prevents the meta variable
		// from showing up in the custom fields section
		$footerScriptMeta = get_post_meta($post->ID,'_inpost_footer_script',TRUE);
	 
		// instead of writing HTML here, lets do an include
		include(SHFS_PLUGIN_DIR . '/footerScriptMeta.php');
	 
		// create a custom nonce for submit verification later
		echo '<input type="hidden" name="shfs_post_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
	}
	 
	function shfs_post_meta_save($post_id) 
	{
		// authentication checks

		// make sure data came from our meta box
		if ( ! isset( $_POST['shfs_post_meta_noncename'] )
			|| !wp_verify_nonce($_POST['shfs_post_meta_noncename'],__FILE__)) return $post_id;
			
		// check user permissions
		if ($_POST['post_type'] == 'page') 
		{
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		}
		else 
		{
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}

		$current_header_data = get_post_meta($post_id, '_inpost_head_script', TRUE);	
	 
		$new_header_data = $_POST['_inpost_head_script'];

		shfs_post_meta_clean($new_header_data);
		
		if ($current_header_data) 
		{
			if (is_null($new_header_data)) delete_post_meta($post_id,'_inpost_head_script');
			else update_post_meta($post_id,'_inpost_head_script',$new_header_data);
		}
		elseif (!is_null($new_header_data))
		{
			add_post_meta($post_id,'_inpost_head_script',$new_header_data,TRUE);
		}
                
                
		$current_footer_data = get_post_meta($post_id, '_inpost_footer_script', TRUE);	
	 
		$new_footer_data = $_POST['_inpost_footer_script'];

		shfs_post_meta_clean($new_footer_data);
		
		if ($current_footer_data) 
		{
			if (is_null($new_footer_data)) delete_post_meta($post_id,'_inpost_footer_script');
			else update_post_meta($post_id,'_inpost_footer_script',$new_footer_data);
		}
		elseif (!is_null($new_footer_data))
		{
			add_post_meta($post_id,'_inpost_footer_script',$new_footer_data,TRUE);
		}
                
                

		return $post_id;
	}

	function shfs_post_meta_clean(&$arr)
	{
		if (is_array($arr))
		{
			foreach ($arr as $i => $v)
			{
				if (is_array($arr[$i])) 
				{
					shfs_post_meta_clean($arr[$i]);

					if (!count($arr[$i])) 
					{
						unset($arr[$i]);
					}
				}
				else 
				{
					if (trim($arr[$i]) == '') 
					{
						unset($arr[$i]);
					}
				}
			}

			if (!count($arr)) 
			{
				$arr = NULL;
			}
		}
	}


	add_action('wp_dashboard_setup', 'shfs_dashboard_widgets');

	function shfs_dashboard_widgets() {
  		global $wp_meta_boxes;
		wp_add_dashboard_widget('blogsynthesisshfswidget', 'Latest from BlogSynthesis', 'shfs_widget');
	}		

		function shfs_widget() {		
			include_once( ABSPATH . WPINC . '/feed.php' );
			
			$rss = fetch_feed( 'http://feeds2.feedburner.com/blogsynthesis' );
			
			if ( ! is_wp_error( $rss ) ) :

				// Figure out how many total items there are, but limit it to 10. 
				$maxitems = $rss->get_item_quantity( 10 ); 

				// Build an array of all the items, starting with element 0 (first element).
				$rss_items = $rss->get_items( 0, $maxitems );

			endif; 
			
			{ ?>
				<div class="rss-widget">
                	<a href="http://www.blogsynthesis.com/#utm_source=wpadmin&utm_medium=dashboardwidget&utm_term=newsitemlogo&utm_campaign=shfs" title="BlogSynthesis - For Bloggers" target="_blank"><img src="<?php  echo plugin_dir_url( __FILE__ ); ?>images/blogsynthesis-100px.png"  class="alignright" alt="BlogSynthesis"/></a>			
					<ul>
						<?php if ( $maxitems == 0 ) : ?>
							<li><?php _e( 'No items', 'shfs-text-domain' ); ?></li>
						<?php else : ?>
							<?php // Loop through each feed item and display each item as a hyperlink. ?>
							<?php foreach ( $rss_items as $item ) : ?>
								<li>
									<a href="<?php echo esc_url( $item->get_permalink() ); ?>#utm_source=wpadmin&utm_medium=dashboardwidget&utm_term=newsitem&utm_campaign=shfs"
										title="<?php printf( __( 'Posted %s', 'shfs-text-domain' ), $item->get_date('j F Y | g:i a') ); ?>" target="_blank">
										<?php echo esc_html( $item->get_title() ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						<?php endif; ?>
					</ul>
					<div style="border-top: 1px solid #ddd; padding-top: 10px; text-align:center;">
						<span class="addthis_toolbox addthis_default_style" style="float:left;">
						<a class="addthis_button_facebook_follow" addthis:userid="blogsynthesis"></a>
						<a class="addthis_button_twitter_follow" addthis:userid="blogsynthesis"></a>
						<a class="addthis_button_google_follow" addthis:userid="+BlogSynthesis"></a>
						<a class="addthis_button_rss_follow" addthis:userid="http://feeds2.feedburner.com/blogsynthesis"></a>
						</span>
						&nbsp; &nbsp; &nbsp;
						<a href="http://www.blogsynthesis.com/newsletter/"><img src="<?php  echo plugin_dir_url( __FILE__ ); ?>images/email-16px.png" alt="Subscribe via Email"/> Subscribe by email</a>
                		&nbsp; &nbsp; &nbsp;
						<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-525ab1d176544441"></script>
					</div>
				</div>
		<?php }
		
	}
	
        $shfs_header_and_footer_scripts = new HeaderAndFooterScriptsFixdigital();

}


