<?php

// Change icon from Woocommerce Blocks: customer-account, mini-cart
add_filter('render_block', 'woo_icon_account_render_block_core', null, 2);


function woo_icon_account_render_block_core (string $block_content, array $block)
{
	if ( 
		'woocommerce/customer-account' === $block['blockName'] 
		&& !is_admin() 
		&& !wp_is_json_request()
    ) {
		
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="25" viewBox="0 0 22 25" fill="none">
		<path d="M9.21486 1.0452C7.31972 1.87761 5.79626 3.58315 5.59529 5.79537C5.36839 8.28807 7.01935 10.679 9.13923 11.6765C11.5616 12.8166 14.9522 12.8505 16.0737 9.88051C16.7025 8.21343 16.7673 5.84061 16.1688 4.16448C15.1358 1.26914 11.9376 -0.0631691 9.21486 1.0452C7.68924 1.66725 8.35265 4.27305 9.89988 3.64196C11.7259 2.89777 13.675 3.75053 13.9538 5.90168C14.077 6.85624 14.1051 9.03905 13.1975 9.57514C12.2899 10.1112 10.6735 9.53442 9.89556 9.07072C9.27969 8.70428 8.74378 8.1139 8.42612 7.44887C7.98097 6.51241 8.07821 5.46059 8.70272 4.64628C8.9361 4.34317 9.22351 4.09209 9.53468 3.88173C9.66002 3.79804 10.425 3.40897 9.89772 3.64196C10.5438 3.35921 10.989 2.753 10.7967 1.98619C10.6368 1.35283 9.8545 0.764717 9.21486 1.0452Z" fill="currentColor"/>
		<path d="M2.55158 23.1006C3.55209 19.918 6.18196 17.2624 9.47307 16.7602C14.0456 16.0635 17.8943 19.8931 19.6511 23.9579C20.3318 25.5345 22.5511 24.166 21.8725 22.5984C19.6662 17.4954 14.6961 13.2949 9.123 14.1092C4.8076 14.7403 1.4106 18.1174 0.0708147 22.3835C-0.447811 24.0348 2.03511 24.7405 2.55158 23.1006Z" fill="currentColor"/>
		</svg>';

		$block_content = preg_replace('/<svg.*<\/svg>/s', $svg, $block_content);

		return $block_content;
	}

	return $block_content;
}


// Allow HTML in term (category, tag) descriptions
foreach ( array( 'pre_term_description' ) as $filter ) {
	remove_filter( $filter, 'wp_filter_kses' );
	if ( ! current_user_can( 'unfiltered_html' ) ) {
		add_filter( $filter, 'wp_filter_post_kses' );
	}
}
 
foreach ( array( 'term_description' ) as $filter ) {
	remove_filter( $filter, 'wp_kses_data' );
}


// Add content below the products block to show the fragments CPT
add_action('woocommerce_after_main_content', 'add_content_below_to_products_block');

function add_content_below_to_products_block() {
    get_template_part('template-hooks/fragments');
}


// Elimina todas las tabs existentes y las sustituye por un acordeón
add_filter('render_block', 'wcsuccess_remove_all_product_tabs', null, 2);

function wcsuccess_remove_all_product_tabs(string $block_content, array $block) {
	if (
		'woocommerce/product-details' === $block['blockName']
		&& !is_admin()
		&& !wp_is_json_request()
	) {
		ob_start();
        get_template_part('template-hooks/woo-accordion');
        $block_content = ob_get_clean();

		// Elimina los títulos (h2)
        $block_content = preg_replace('#<h2>(.*?)</h2>#', '', $block_content);

		return $block_content;
	}

	return $block_content;
}

// add_filter('woocommerce_loop_add_to_cart_link', function($button, $product, $args = array()) {
// 	// Add a unique class to the add to cart button
// 	$button = str_replace('class="', 'class="js-add-to-cart ', $button);
// 	return $button;
// }, 10, 3);

add_action('wp_footer', function() {
	?>
	<script>
	(function($){
		$(document).on('click', '.single_add_to_cart_button', function(e){
			var $btn = $(this);
			$btn.addClass('adding');
		});

		// For AJAX add to cart (archive/products)
		$(document.body).on('added_to_cart', function(e, fragments, cart_hash, $button){
			if($button && $button.length){
				$button.removeClass('adding');
			}
		});

		// For single product add to cart
		$(document.body).on('ajax_add_to_cart', function(){
			$('.single_add_to_cart_button.adding').removeClass('adding');
		});
	})(jQuery);
	</script>
	<?php
});