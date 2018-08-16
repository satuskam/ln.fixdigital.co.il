<?php
/**
 * The template for displaying the footer.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
			</div><!-- #content -->
		</div><!-- .container -->
	</div><!-- #primary -->

	
	
		<footer id="footer">
			<?php // get_sidebar( 'elementor_footer' ); ?>
        	<?php 
        		if (is_active_sidebar('elementor_footer')) {
            		dynamic_sidebar('elementor_footer');
        		}
      		?>
		</footer>

<!--<style>
	#footer {padding: 0px}
	#footer #elementor-library-3, #footer p {margin: 0px}
	#sidebar-footer  {padding: 0px}
</style>-->
	
	</div><!-- .container-wrapper -->
</div><!-- #container -->
<?php wp_footer(); ?>
</body>
</html>