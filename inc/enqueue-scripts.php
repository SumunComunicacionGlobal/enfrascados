<?php
/**
 * Enqueue scripts and styles.
 */

 function enfrascados_scripts() {

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'enfrascados-js', get_template_directory_uri() . '/assets/js/enfrascados.js', array(), true );

}
add_action( 'wp_enqueue_scripts', 'enfrascados_scripts' );

/** 
* Gutenberg scripts and styles
 */
function smn_gutenberg_scripts() {

	wp_enqueue_script(
		'be-editor', 
		get_stylesheet_directory_uri() . '/assets/js/editor.js', 
		array( 'wp-blocks', 'wp-dom', 'wp-dom-ready', 'wp-edit-post' ), 
		filemtime( get_stylesheet_directory() . '/assets/js/editor.js' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'smn_gutenberg_scripts' );