				<div id="sidebar1" class="sidebar col-3-12 last-col cf" role="complementary">

					<?php if ( is_active_sidebar( 'sidebar1' ) ) : ?>

						<?php dynamic_sidebar( 'sidebar1' ); ?>

					<?php else : ?>

						<?php
							/*
							 * This content shows up if there are no widgets defined in the backend.
							*/
						?>

					<?php endif; ?>
                    
                    <?php 
                        // Get sidebar gallery, if one exists
                        $galleryArray = get_post_gallery_ids($post->ID);
                        if ( !empty($galleryArray) ){
                            echo '<div class="sidebar_gallery">';

                            foreach ($galleryArray as $id) { 
                                //echo '<img src="' . wp_get_attachment_url( $id ) . '">';
                                echo wp_get_attachment_image( $id, 'dc-sidebar-thumb-400' );
                                $attachment_meta = wp_get_attachment( $id );
                               /* $meta = wp_get_attachment_metadata( $id );
                                if( !empty( $meta['image_meta']['caption'] ) ){*/
                                if( !empty( $attachment_meta['caption'] ) ){
                                    echo '<div class="sidebar_gallery_caption">';
                                    echo $attachment_meta['caption'];
                                    echo '</div>';
                                }
                            } 
                            echo '</div>';
                        }
                    ?>

				</div>
