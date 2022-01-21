			<footer class="footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">

				<div id="inner-footer" class="wrap cf">

					<div id="footer_widgets_center" class="col-6-12 col-offset-3-12">
                        <ul class="footer_widgets_center_list">
                            <?php dynamic_sidebar( 'footer_widgets_center' ); ?> 
                        </ul>
                        <p class="source-org copyright">&copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>.</p>
					</div>
                    
					<div id="footer_widgets_right" class="col-3-12">
                        <ul class="footer_widgets_right_list">
                            <?php dynamic_sidebar( 'footer_widgets_right' ); ?> 
                        </ul>
					</div>

				</div>

			</footer>

		</div>

		<?php // all js scripts are loaded in library/bones.php ?>
		<?php wp_footer(); ?>

	</body>

</html> <!-- end of site. what a ride! -->
