<?php
/**
 * The template for displaying the footer.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
			</div><!-- #content -->
		</div><!-- .container -->
	</div><!-- #primary -->

<?php po_change_loop_to_parent( 'change' ); ?>
	<?php if ( ! pojo_is_blank_page() ) : ?>
		<footer id="footer" role="contentinfo">
			<?php get_sidebar( 'footer' ); ?>
			<div id="copyright">
				<div class="<?php echo WRAP_CLASSES; ?>">
					<div class="box-top-border">
						<div class="pull-left-copyright">
							<?php echo nl2br( pojo_get_option( 'txt_copyright_left' ) ); ?>
						</div>
						<div class="pull-right-copyright">
							<?php echo nl2br( pojo_get_option( 'txt_copyright_right' ) ); ?>
						</div>
					</div>
				</div><!-- .<?php echo WRAP_CLASSES; ?> -->
			</div>
		</footer>
	<?php endif; // end blank page ?>
<?php po_change_loop_to_parent(); ?>

</div><!-- #container -->
<?php wp_footer(); ?>
</body>
</html>