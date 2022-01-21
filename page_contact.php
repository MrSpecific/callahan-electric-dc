<?php
/**
 * Template Name: Contact Page
 */
?>
<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="grid grid-pad cf">

                    <header class="article-header">

                        <h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>
                        
                    </header>
                    
						<main id="main" class="col-9-12 last-col cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<section class="entry-content cf" itemprop="articleBody">
									<?php
										// the content (pretty self explanatory huh)
										the_content();
									?>
								</section> <?php // end article section ?>

								<footer class="article-footer cf">

								</footer>

								<?php comments_template(); ?>

							</article>

							<?php endwhile; endif; ?>

						</main>

						<div id="contact_info" class="sidebar col-3-12 first-col cf" role="complementary">
                            
                            <?php if ( get_theme_mod( 'dc_business_info_office_hours' ) ) : ?>
                                <p class="contact_info_section">
                                    <h3>Office Hours</h3>
                                    <?php echo get_theme_mod( 'dc_business_info_office_hours' ); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ( get_theme_mod( 'dc_business_info_service_hours' ) ) : ?>
                                <p class="contact_info_section">
                                    <h3>Service Hours</h3>
                                    <?php echo get_theme_mod( 'dc_business_info_service_hours' ); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ( get_theme_mod( 'dc_business_info_service_area' ) ) : ?>
                                <p class="contact_info_section">
                                    <h3>Service Area</h3>
                                    <?php echo stripslashes(nl2br(get_theme_mod( 'dc_business_info_service_area' ))); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ( get_theme_mod( 'dc_business_info_warranty' ) ) : ?>
                                <p class="contact_info_section">
                                    <h3>Warranty</h3>
                                    <?php echo stripslashes(nl2br(get_theme_mod( 'dc_business_info_warranty' ))); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php if ( get_theme_mod( 'dc_business_info_licenses' ) ) : ?>
                                <p class="contact_info_section">
                                    <h3>Licenses</h3>
                                    <?php echo stripslashes(nl2br(get_theme_mod( 'dc_business_info_licenses' ))); ?>
                                </p>
                            <?php endif; ?>
                    
                        </div>

				</div>

			</div>

<?php get_footer(); ?>
