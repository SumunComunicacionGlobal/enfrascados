<?php
/**
 * Register ACF Blocks
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @return void
 */


function smn_register_blocks() {
    register_block_type( get_stylesheet_directory() . '/custom-blocks/timeline' );
    register_block_type( get_stylesheet_directory() . '/custom-blocks/slider' );
    register_block_type( get_stylesheet_directory() . '/custom-blocks/fadetext' );
}

add_action( 'init', 'smn_register_blocks' );