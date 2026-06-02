<?php

remove_action('wp_head', 'wp_generator');


add_action( 'wp_body_open', 'smn_connectif_contact_form_7', 1 );
function smn_connectif_contact_form_7() { ?>
	
	<script async id="__cn_generic_script__dca3e662-ad2d-4cfc-8c61-b86557bcb37a">!function(e){function t(){if(!e.querySelector("#__cn_client_script_dca3e662-ad2d-4cfc-8c61-b86557bcb37a")){var t=e.createElement("script");t.setAttribute("src","https://cdn.connectif.cloud/eu8/client-script/dca3e662-ad2d-4cfc-8c61-b86557bcb37a"),e.body.appendChild(t)}}"complete"===e.readyState||"interactive"===e.readyState?t():e.addEventListener("DOMContentLoaded",t)}(document);</script>

<?php }


add_action( 'wp_head' , 'smn_add_to_favorites' );
function smn_add_to_favorites() { ?>
	<script>
		function addToFavorites() {
			var url = window.location.href;
			var title = document.title;

			if (window.sidebar && window.sidebar.addPanel) { // Firefox <23
				window.sidebar.addPanel(title, url, '');
			} else if (window.external && ('AddFavorite' in window.external)) { // IE Favorites
				window.external.AddFavorite(url, title);
			} else if (window.opera && window.print) { // Opera Hotlist
				this.title = title;
				return true;
			} else { // Other browsers (mainly WebKit - Chrome/Safari)
				alert('Presiona ' + (navigator.userAgent.toLowerCase().indexOf('mac') != -1 ? 'Cmd' : 'Ctrl') + ' + D para añadir esta página a tus favoritos.');
			}
		}
	</script>
<?php }

/**
 * Enqueue scripts and styles.
 */

 function enfrascados_scripts() {

	$theme_version = wp_get_theme()->get( 'Version' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'enfrascados-js', get_template_directory_uri() . '/assets/js/enfrascados.js', array(), $theme_version );

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