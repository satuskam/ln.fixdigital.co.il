<?php
/**
 * The template for displaying the footer.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
			</div><!-- #content -->
		</div><!-- .container -->
	</div><!-- #primary -->

	
	
    <footer id="footer-copyright" class="footer">
        <?php // get_sidebar( 'elementor_footer' ); ?>
        <?php
            if (is_active_sidebar('elementor_footer')) {
                dynamic_sidebar('elementor_footer');
            }
        ?>
    </footer>
	
	<!--</div> .container-wrapper -->
</div><!-- #container -->
<?php wp_footer(); ?>
</body>
</html>