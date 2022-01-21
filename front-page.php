<?php get_header(); ?>

			<div id="content" class="front_page_content">

				<div id="inner-content" class="grid grid-pad cf">

						<main id="main" class="col-12-12 last-col cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<section class="entry-content cf" itemprop="articleBody">
									<?php
										// the content (pretty self explanatory huh)
										the_content();
									?>
								</section> <?php // end article section ?>

								<footer class="article-footer cf">
                                    <div class="col-4-12 front_page_section">
                                        <?php if ( get_theme_mod( 'dc_front_page_left_text' ) ) : ?>
                                            <?php echo esc_attr( get_theme_mod( 'dc_front_page_left_text' )  ); ?>
                                        <?php endif; ?>
                                        <?php if ( get_theme_mod( 'dc_front_page_left_link' ) && get_theme_mod( 'dc_front_page_left_page' ) ) : ?>
                                        <a href="<?php echo get_permalink( get_theme_mod( 'dc_front_page_left_page' ) ); ?>">
                                            <?php echo esc_attr( get_theme_mod( 'dc_front_page_left_link' )  ); ?>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-4-12 front_page_section">
                                        <?php if ( get_theme_mod( 'dc_front_page_left_text' ) ) : ?>
                                            <?php echo esc_attr( get_theme_mod( 'dc_front_page_center_text' )  ); ?>
                                        <?php endif; ?>
                                        <?php if ( get_theme_mod( 'dc_front_page_center_link' ) && get_theme_mod( 'dc_front_page_center_page' ) ) : ?>
                                        <a href="<?php echo get_permalink( get_theme_mod( 'dc_front_page_center_page' ) ); ?>">
                                            <?php echo esc_attr( get_theme_mod( 'dc_front_page_center_link' )  ); ?>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-4-12 front_page_section">
                                        <?php if ( get_theme_mod( 'dc_front_page_right_text' ) ) : ?>
                                            <?php echo esc_attr( get_theme_mod( 'dc_front_page_right_text' )  ); ?>
                                        <?php endif; ?>
                                        <?php if ( get_theme_mod( 'dc_front_page_right_link' ) && get_theme_mod( 'dc_front_page_right_page' ) ) : ?>
                                        <a href="<?php echo get_permalink( get_theme_mod( 'dc_front_page_right_page' ) ); ?>">
                                            <?php echo esc_attr( get_theme_mod( 'dc_front_page_right_link' )  ); ?>
                                        </a>
                                        <?php endif; ?>
                                    </div>
								</footer>

								<?php comments_template(); ?>

							</article>

							<?php endwhile; endif; ?>

						</main>

				</div>

			</div>

<?php get_footer(); ?>
