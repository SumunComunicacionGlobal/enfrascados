<?php

// Change icon from Woocommerce Blocks: customer-account, mini-cart
add_filter('render_block', 'woo_icon_account_render_block_core', null, 2);

// Desactivar el zoom en la página de producto
add_filter( 'woocommerce_single_product_zoom_enabled', '__return_false' );

// Aplicar descuento específico al añadir un producto al carrito si se cumplen ciertas condiciones
// add_action( 'woocommerce_cart_calculate_fees', 'aplicar_descuento_especifico', 20, 1 );
// function aplicar_descuento_especifico( $cart ) {
	
// 	//if ( !current_user_can( 'manage_options' ) ) return;
//     if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;	

//     // IDs de los productos
//     $producto_tuppers = 2655;
//     $productos_botellas = array( 2653, 2654 );
//     $cantidad_botellas_requerida = 2;
//     $descuento = 4.88; // Monto del descuento sin impuestos

//     // Verificar si el producto con descuento está en el carrito
//     $producto_descuento_en_carrito = false;
//     foreach ( $cart->get_cart() as $cart_item ) {
//         if ( $cart_item['product_id'] == $producto_tuppers ) {
//             $producto_descuento_en_carrito = true;
//             break;
//         }
//     }

//     // Si el producto de descuento no está en el carrito, salir
//     if ( !$producto_descuento_en_carrito ) return;

//     // Contar la cantidad total de los productos requeridos en el carrito
//     $cantidad_total_botellas = 0;
//     foreach ( $cart->get_cart() as $cart_item ) {
//         if ( in_array( $cart_item['product_id'], $productos_botellas ) ) {
//             $cantidad_total_botellas += $cart_item['quantity'];
//         }
//     }

//     // Si se cumple la cantidad requerida, aplicar el descuento
//     if ( $cantidad_total_botellas >= $cantidad_botellas_requerida ) {
//         $cart->add_fee( __( 'Descuento especial productos Home', 'smn' ), -$descuento, true, '' );
//     }
	
// }

// Multiplica los gastos de envío por un factor que depende del total del carrito
// add_filter('woocommerce_package_rates', 'adjust_shipping_costs', 10, 2);
// function adjust_shipping_costs($rates, $package) {
//     $cart_total = WC()->cart->cart_contents_total + WC()->cart->tax_total;
//     $multiplier = ceil($cart_total / 80);
	

// 	// APLICAR EL MULTIPLICADOR DE GASTOS DE ENVÍO

// 	foreach($rates as $rate_key => $rate_values ) {
//         // Get original cost
//         $original_cost = $rates[$rate_key]->cost;
//         // Calculate the discounted rate cost
//         $new_cost = $original_cost * $multiplier;
//         // Set the discounted rate cost
//         $rates[$rate_key]->cost = number_format($new_cost, 2);
		
//         // Taxes rate cost (if enabled)
//         $taxes = array();
//         foreach ($rates[$rate_key]->taxes as $key => $tax){
//             if( $tax > 0 ){ // set the new tax cost
//                 // set the new line tax cost in the taxes array
//                 $taxes[$key] = number_format( $tax * $multiplier, 2 );
//             }
//         }
//         // Set the new taxes costs
//         $rates[$rate_key]->taxes = $taxes;
		
//     }

//     return $rates;
// }

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

		// Elimina el primer título h2 que es el título de la sección de tabs
		$block_content = preg_replace('/<h2[^>]*>.*?<\/h2>/', '', $block_content, 1);
		

		return $block_content;
	}

	return $block_content;
}

// add_filter('woocommerce_loop_add_to_cart_link', function($button, $product, $args = array()) {
// 	// Add a unique class to the add to cart button
// 	$button = str_replace('class="', 'class="js-add-to-cart ', $button);
// 	return $button;
// }, 10, 3);

// Añade una clase "adding" al botón de añadir al carrito cuando se hace clic, y la elimina cuando se completa la acción de añadir al carrito, tanto para productos individuales como para productos en archivos. Esto permite mostrar un estado visual de "añadiendo" mientras se procesa la acción, mejorando la experiencia del usuario al proporcionar retroalimentación inmediata.
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

// Previene que se añadan varias veces los iconos de "añadiendo" y "añadido" en el botón de añadir al carrito, lo que puede ocurrir si el usuario hace clic varias veces antes de que se procese la acción de añadir al carrito. Esto es especialmente útil para evitar confusiones visuales y mejorar la experiencia del usuario.
add_action('wp_footer', function () {
    if ( ! is_product() ) {
        return;
    }
    ?>
    <script>
    (function($) {

        function cleanCartPopupIcons($btn) {
            $btn.find('.xoo-cp-adding').not(':last').remove();
            $btn.find('.xoo-cp-added').not(':last').remove();

            if ($btn.find('.xoo-cp-added').length) {
                $btn.find('.xoo-cp-adding').remove();
            }
        }

        $(document).on('click', '.single_add_to_cart_button', function(e) {
            var $btn = $(this);

            if ($btn.data('xooProcessing')) {
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }

            $btn.data('xooProcessing', true);
            $btn.find('.xoo-cp-adding, .xoo-cp-added').remove();

            setTimeout(function() {
                cleanCartPopupIcons($btn);
            }, 50);
        });

        $(document.body).on('added_to_cart ajax_complete wc_fragments_refreshed updated_wc_div', function() {
            $('.single_add_to_cart_button').each(function() {
                var $btn = $(this);
                cleanCartPopupIcons($btn);

                setTimeout(function() {
                    $btn.removeData('xooProcessing');
                }, 500);
            });
        });

        $(document).ajaxComplete(function() {
            $('.single_add_to_cart_button').each(function() {
                cleanCartPopupIcons($(this));
            });
        });

    })(jQuery);
    </script>
    <?php
}, 99);

// Prevenir error fatal al cargar el checkout
add_action('woocommerce_init', function() {
		
	//if ( !current_user_can('manage_options') ) return;
	
	if ( WC()->session ) {	
		$chosen = WC()->session->get( 'chosen_shipping_methods' );
		if ( ! is_array( $chosen ) ) {
			WC()->session->set( 'chosen_shipping_methods', array( $chosen ) );
		}
	}
	
});

// Cambia el texto del botón "Añadir al carrito" en la página de producto y en los bloques de productos
add_filter( 'woocommerce_product_add_to_cart_text', function() {
    return __( 'Añadir', 'smn' );
});
add_filter( 'woocommerce_product_single_add_to_cart_text', function() {
    return __( 'Añadir', 'smn' );
});

// Cambia el texto de "Out of stock" a "Agotado" en los productos que no están en stock, tanto en la página de producto como en los bloques de productos. 
add_filter('woocommerce_get_availability_text', 'custom_out_of_stock_text', 10, 2);
function custom_out_of_stock_text($availability, $product) {
    if (!$product->is_in_stock()) {
        $availability = __( 'Agotado', 'smn');
    }
    return $availability;
}

// Añadir un mensaje destacado en el checkout para explicar el proceso de facturación y envío
add_action('woocommerce_before_checkout_billing_form', function() { 
	
	echo '<p class="texto-destacado">' . __('Introduce aquí los datos de facturación. Por defecto se usarán estos datos también para el envío, pero podrás enviar a una dirección diferente a la de facturación si marcas más adelante la casilla "enviar a una dirección diferente"','smn') . '</p>';
	
});

add_action( 'woocommerce_before_checkout_shipping_form', function() { 
	
	echo '<p class="texto-destacado">' . __('Si quieres enviar a la misma dirección de facturación, desmarca la casilla anterior ("Enviar a una dirección diferente").', 'smn') . '</p>'; 

}, 1);

// Añade la palabra "packs" al stock de los productos que tengan "pack" en su nombre, para indicar que se venden por packs en lugar de unidades individuales. Esto ayuda a los clientes a entender mejor la cantidad disponible y el formato de venta del producto, mejorando la claridad y la experiencia de compra.
add_filter( 'woocommerce_format_stock_quantity', function( $stock_quantity, $product ) {
	
	$product_name = strtolower($product->get_title());
	if ( str_contains( $product_name, 'pack' ) ) {
		return $stock_quantity . ' ' . __('packs', 'smn');
	}
	
	return $stock_quantity;
}, 10, 2);

// Cambia el nombre de las dimensiones y el peso en la tabla de atributos del producto, para mostrar "Dimensiones del embalaje" en lugar de "Dimensiones" y "Peso del embalaje" en lugar de "Peso". Esto proporciona una descripción más clara y específica de las dimensiones y el peso que se están mostrando, indicando que se refieren al embalaje del producto en lugar del producto en sí, lo que puede ser especialmente útil para productos que se venden por packs o que tienen un peso y dimensiones significativos debido al embalaje.
add_filter( 'woocommerce_display_product_attributes', 'change_woocommerce_display_product_attributes_label', 1, 2 );
function change_woocommerce_display_product_attributes_label( $product_attributes, $product ){

	if( isset( $product_attributes['weight'] ) ){
        $product_attributes['weight']['label'] = __( 'Peso del embalaje', 'smn' );
    }
    if( isset( $product_attributes['dimensions'] ) ){
        $product_attributes['dimensions']['label'] = __( 'Dimensiones del embalaje', 'smn' );
    }
	
    return $product_attributes;
}

// Cambia el título de la pestaña "Información adicional" a "Características" en la página de producto
add_filter('woocommerce_product_tabs', 'rename_additional_information_tab', 98);
function rename_additional_information_tab($tabs) {
    if (isset($tabs['additional_information'])) {
        $tabs['additional_information']['title'] = __( 'Características', 'smn' );
    }
    return $tabs;
}


// // add_filter( 'woocommerce_format_dimensions', 'smn_wc_format_dimensions', 10, 2 );
// function smn_wc_format_dimensions($dimension_string, $dimensions) {

// 	$dimension_string = implode(' x ', array_filter(array_map('wc_format_localized_decimal', $dimensions)));

// 	if ($dimension_string) {

// 		$dimension_label = '&nbsp;' . get_option( 'woocommerce_dimension_unit' );
// 		$dimension_array = array();
		
// 		if ( $dimensions['length'] ) {
// 			$dimension_array[] = __('Largo', 'smn') . ': ' . $dimensions['length'] . $dimension_label;
// 		}
// 		if ( $dimensions['width'] ) {
// 			$dimension_array[] = __('Diámetro o ancho', 'smn') . ': ' . $dimensions['width'] . $dimension_label;
// 		}
// 		if ( $dimensions['height'] ) {
// 			$dimension_array[] = __('Alto', 'smn') . ': ' . $dimensions['height'] . $dimension_label;
// 		}
		
// 		$dimension_string = implode( '<br>', $dimension_array );
		
// 	} else {
// 		$dimension_string = __('N/A', 'woocommerce');
// 	}

// 	return $dimension_string;
// }

// add_filter( 'woocommerce_attribute', 'smn_change_wc_attribute_values', 10, 3);
// function smn_change_wc_attribute_values( $html, $attribute, $values ) {

// 	if ( $attribute['id'] == 1 ) {
// 		$values = array_map(function($x){ return $x . ' ml'; }, $values);
// 		$html = wpautop( wptexturize( implode( ', ', $values ) ) );
// 	}
	
// 	return $html;
// }

// Da formato al nombre de los productos en pack
add_filter( 'the_title', 'smn_format_product_title', 10, 2 );
function smn_format_product_title( $post_title, $post_id ) {
	
	if ( is_admin() ) return $post_title;
	
	$post_type = get_post_type( $post_id );
	if ( 'product' != $post_type ) return $post_title;
	
	$post_title = str_replace( array(' PACK ', ' Pack ', ' pack '), ' <br>Pack ', $post_title );
	
	$post_title_array = explode( "<br>", $post_title );

	if ( isset( $post_title_array[1] ) ) {
		$post_title_array[1] = '<span class="post-title-subtitle">' . $post_title_array[1] .'</span>';
	}
	
	$post_title = implode( '<br>', $post_title_array );
	$post_title = str_replace( ' TO ', ' <br>Tapa Twist Off ', $post_title );
		
	return $post_title;
}

// Añade una descripción debajo de la opción de envío "CTT Express" en el checkout, para informar a los clientes sobre las condiciones y tiempos de entrega asociados a esta opción de envío. Esto ayuda a los clientes a tomar decisiones informadas sobre su método de envío, mejorando la transparencia y la satisfacción del cliente.	
add_action( 'woocommerce_after_shipping_rate', 'bbloomer_shipping_rate_description' );
function bbloomer_shipping_rate_description( $method ) {
	
	   if ( $method->method_id === 'cttexpress' ) {
			echo '<p class="shipping-description"><small>'. __( 'Envío en 24h a España peninsular y baleares si haces tu pedido antes de las 13h.', 'smn') .'</small></p>';
	   }
		
}

// Desactivar función de plugin CTT que rompe la web al finalizar un pedido
remove_action('woocommerce_order_details_after_order_table', 'cttexpress_cttexpress_add_custom_text_after_order_table', 10);

// Personalizar el sufijo de precio para mostrar el precio sin impuestos junto al precio con impuestos, separados por una barra vertical. 
add_filter( 'woocommerce_get_price_suffix', 'smn_custom_price_suffix' );
function smn_custom_price_suffix( $html ) {
	
	$array = explode ( '|', $html );
	if ( count($array) > 1 ) {
		$html = $array[0] . '<span class="price-excluding-tax">'. $array[1] .'</span>';
	}
	
	return $html;
}

// Añade varios campos personalizados de ACF a la tabla de atributos del producto en la página de producto, y también añade un enlace a los productos relacionados (cross-sells) si existen. 
add_filter( 'woocommerce_display_product_attributes', 'smn_acf_product_attributes', 10, 2);
function smn_acf_product_attributes( $product_attributes, $product ) {
	
	$fields = array(
		'capacidad',
		'diametro',
		'alto',
		'peso',
	);
	
	$new_rows = array();
	
	foreach( $fields as $field ) {
		$value = get_field( $field );
		if ( !$value ) continue;
		
		$field_object = get_field_object( $field );
		$label = $field_object['label'];
		
		if ( $field_object['append'] ) {
			$value .= '&nbsp;' . $field_object['append'];
		}

		$new_rows['acf_' . $field_object['name']] = array(
			'label'		=> $label,
			'value'		=> $value
		);
	}
	
	// Poner las dimensiones nativas de WC al final
	$dimensions = array();
	
	if ( isset( $product_attributes['weight'] ) ) {
		$dimensions[] = $product_attributes['weight'];
		unset( $product_attributes['weight'] );
	}
	
	if ( isset( $product_attributes['dimensions'] ) ) {
		$dimensions[] = $product_attributes['dimensions'];
		unset( $product_attributes['dimensions'] );
	}

	$product_attributes = array_merge( $new_rows, $product_attributes, $dimensions );

	if ( $product && $product != null ) {

		$cross_sells = $product->get_cross_sell_ids();

		if ( $cross_sells) {
			$link = '<a href="#cross-sells">'. __( 'Ver', 'smn' ) .'</a>';

			$product_attributes['cross-sells'] = array(
				'label'				=> __( 'Productos compatibles', 'smn'),
				'value'				=> $link,
			);
		}
		
	}

	return $product_attributes;
}

// Añade una imagen personalizada al encabezado de los correos electrónicos de WooCommerce, para mostrar un diseño más atractivo y coherente con la marca
add_action( 'woocommerce_email_header', 'smn_woocommerce_email_header_action', 100, 2 );
function smn_woocommerce_email_header_action( $email_heading, $email ){
	$imagen = '<p style="text-align:center;"><img src="https://enfrascados.shop/wp-content/uploads/2024/01/tag-sticker.png" class="viwec-image" style="max-width: 100%; width: 200px;"></p>';
	echo $imagen;
}