<?php
/**
 * Template Name: Page with Sidebar
 */
?>
<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="grid grid-pad cf">

                        <header class="article-header">

                            <h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>

                        </header> <?php // end article header ?>
                    
						<main id="main" class="col-9-12 first-col cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

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

						<?php get_sidebar(); ?>

				</div>

			</div>

<?php get_footer(); ?>
