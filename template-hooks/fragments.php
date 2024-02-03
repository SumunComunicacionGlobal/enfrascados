<?php

/**
 * Fragmennts Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it's parent block.
 */

?>

<h1>Fragments PARTE</h1>

<div class="wp-block-template-part alignfull">
    
    <?php
        $tax = get_queried_object();
        $featured_posts = get_field('fragment_cat_product', $tax);
        if( $featured_posts ):
        
            foreach( $featured_posts as $post ): 

                // Setup this post for WP functions (variable must be named $post).
                setup_postdata($post); 
                the_content();
                    
            endforeach;

        // Reset the global post object so that the rest of the page works correctly.
        wp_reset_postdata();
        endif;
    ?>

</div>