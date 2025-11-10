<?php

remove_action('wp_head', 'wp_generator');

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


/** 
* WooCommerce script to accordion inested in tabs
*/
add_action('wp_footer', 'wc_success_accordion_script');
function wc_success_accordion_script(){
        // only load script on product pages
	if(is_product()){
	?>
<script type="text/javascript">
	(function($) {
	        // detect click on tab
		$(document).on('click', '.wc-tabs li', function(){
		        // get the name of the aria-controls which matches the id of the corresponding tab content
			let ClickedTab = $(this).attr('aria-controls');
			
			// get th emax scroll height of th ecorresponding tab
			let maxHeight = document.getElementById(ClickedTab).scrollHeight;

                        // toggle the active class on the tab
			$(this).toggleClass('active-tab');
			
			// toggle the active class on the tab content
			$('.wc-tab').toggleClass('active');
			
			// apply css and classes depending on the active-tab class
			if($(this).hasClass('active-tab')){
				$('#'+ClickedTab).css('max-height', maxHeight);
				$(this).find('.tab-button').addClass('clicked');
			} else {
				$('#'+ClickedTab).css('max-height', '0');
				$(this).find('.tab-button').removeClass("clicked");
			}
		});
	})( jQuery );
</script>
	<?php
	}
}