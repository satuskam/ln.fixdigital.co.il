<?php
/**
 * The template for displaying the footer.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
				</div><!-- #content -->
		</div><!-- #primary -->

		<footer id="footer" role="contentinfo">

	    	<?php 
	    		if (is_active_sidebar('elementor_footer_frame')) {
	        		dynamic_sidebar('elementor_footer_frame');
	    		}
	  		?>

		</footer>

	</div><!-- .container-wrapper -->
</div><!-- #container -->
<?php wp_footer(); ?>
</body>
</html>