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
	      <?php
	  		if (is_active_sidebar('elementor_footer')) {
	      		dynamic_sidebar('elementor_footer');
	  		}
			?>
		</footer>


		</div>


</div><!-- #container -->
<?php wp_footer(); ?>
</body>
</html>
