<?php
/**
 * The template for displaying the footer.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
				</div><!-- #content -->
			</div><!-- .container -->
		</div><!-- #primary -->
	</div><!--Layout Content--->

    <footer id="footer-copyright" class="footer" role="contentinfo">
        <div class="container">
            <?php 
        		if (is_active_sidebar('elementor_footer')) {
            		dynamic_sidebar('elementor_footer');
        		}
      		?>
        </div><!-- .container -->
    </footer>

</div><!-- #container -->
<?php wp_footer(); ?>
</body>
</html>