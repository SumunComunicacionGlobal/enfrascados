<?php 

// Shortcode para mostrar la imagen destacada de la categoría de producto de WooCommerce
function mostrar_imagen_destacada_categoria_producto($atts) {
    // Obtenemos la categoría actual del producto
    $categoria_producto = get_queried_object();
    
    // Verificamos si es una categoría de producto de WooCommerce
    if (is_a($categoria_producto, 'WP_Term') && taxonomy_exists('product_cat')) {
        // Obtenemos la ID de la categoría
        $categoria_id = $categoria_producto->term_id;

        // Obtenemos la imagen destacada de la categoría
        $imagen_destacada_id = get_term_meta($categoria_id, 'thumbnail_id', true);

        // Obtenemos el campo de imagen de ACF
        $imagen_acf = get_field('imagen_cat_product', 'product_cat_' . $categoria_id);

        // Si el campo de imagen de ACF no está vacío, muestra la imagen de ACF
        if (!empty($imagen_acf)) {
            return '<div class="wp-img-hero--container"><img class="wp-img-hero" src="' . esc_url($imagen_acf['url']) . '" alt="' . esc_attr($imagen_acf['alt']) . '" /></div>';
        }
    
        // Verificamos si hay una imagen destacada
        elseif ($imagen_destacada_id) {
            // Obtenemos la URL de la imagen
            $imagen_destacada_url = wp_get_attachment_url($imagen_destacada_id);

            // Mostramos la imagen
            return '<div class="wp-img-hero--container"><img class="wp-img-hero" src="' . esc_url($imagen_destacada_url) . '" alt="' . esc_attr($categoria_producto->name) . '" /></div>';
        }
    }

    // En caso de no encontrar una imagen destacada
    return '';
}

// Registramos el shortcode
add_shortcode('mostrar_imagen_destacada_categoria_producto', 'mostrar_imagen_destacada_categoria_producto');


// Shortcode para mostrar subcategorías en una página de archivo de categoría de producto
function mostrar_subcategorias_producto($atts) {

    // Comprobar el tipo de visualización establecido para la categoría actual
    $tipo_visualizacion = woocommerce_get_loop_display_mode();
    if ( $tipo_visualizacion == 'products' ) return false;

    // Obtener la categoría actual del producto
    $categoria_actual = get_queried_object();

    // Verificar si es una categoría de producto de WooCommerce
    if (is_a($categoria_actual, 'WP_Term') && taxonomy_exists('product_cat')) {
        // Obtener la ID de la categoría actual
        $categoria_actual_id = $categoria_actual->term_id;

        // Verificar si la categoría actual es una categoría principal
        if ($categoria_actual->parent == 0) {
            // Estamos en una categoría principal, mostrar subcategorías de primer nivel
            $subcategorias = get_terms(array(
                'taxonomy' => 'product_cat',
                'parent' => $categoria_actual_id,
                'hide_empty' => false,
            ));
        } else {
            // Estamos en una subcategoría, mostrar las subcategorías hijas de esa subcategoría
            $subcategorias = get_terms(array(
                'taxonomy' => 'product_cat',
                'parent' => $categoria_actual_id,
                'hide_empty' => false,
            ));
        }

        // Verificar si hay subcategorías
        if ($subcategorias) {

            $output = '';
            
            $output = '<div class="wp-block-grid">';
            $output .= '<div style="height:100px" aria-hidden="true" class="wp-block-spacer hidden-mobile hidden-tablet"></div>';
            $output .= '<div class="wp-block-group wp-block-subcategories-shortcode has-secondary-80-color has-text-color is-vertical is-layout-flex wp-block-group-is-layout-flex">';
            $output .= '<p class="has-mudstone-font-family has-heading-2-font-size">' . __("¿Qué tipo de", "smn_admin") . '</p>';
            $output .= '<h2 class="wp-block-query-title">' . esc_html($categoria_actual->name) . '</h2>';
            $output .= '<p class="has-mudstone-font-family has-heading-2-font-size">' . __("estás buscando?", "smn_admin") . '</p>';
            $output .= '</div>';

            $output .= '<div class="wc-block-grid has-3-columns has-multiple-rows"><ul class="wc-block-grid__products subcategories-shortcode">';

            /// Iterar sobre cada subcategoría
            foreach ($subcategorias as $subcategoria) {
                
                // Obtener la imagen destacada de la subcategoría
                $img_destacada_id = get_term_meta($subcategoria->term_id, 'thumbnail_id', true);
                $img_destacada_url = wp_get_attachment_url($img_destacada_id);
                
                // Obtener el color de la subcategoría
                $categoria_color = get_term_meta($subcategoria->term_id, 'color_cat_product', true);

                // Obtener las clases de la subcategoría
                $term_classes = get_term_class('wc-block-grid__product', $subcategoria->term_id);
                $term_classes = implode(' ', $term_classes);

                // Construir el HTML con la imagen y el enlace
                $categoria_color = empty($categoria_color) ? '#e20916' : $categoria_color; // Si $categoria_color está vacío, usar blanco
                $output .= '<li class="' . esc_attr($term_classes) . '" style="background-color:' . esc_html($categoria_color) . ';">';
                if ($img_destacada_url) {
                    $output .= '<div class="wc-block-grid__product-image"><img loading="lazy" decoding="async" src="' . esc_url($img_destacada_url) . '" alt="' . esc_attr($subcategoria->name) . '" /></div>';
                }
                $output .= '<p class="wc-block-grid__product-title"><a class="wc-block-grid__product-link stretched-link" href="' . get_term_link($subcategoria) . '">' . esc_html($subcategoria->name) . '</a></p>';
                $output .= '</li>';
            }

            $output .= '</ul></div></div>';

            return $output;
        }
    }

    // En caso de no encontrar subcategorías o no estar en una categoría de producto
    return '<div style="height:72px" aria-hidden="true" class="wp-block-spacer hidden-mobile hidden-tablet"></div>';
}

// Registramos el shortcode
add_shortcode('mostrar_subcategorias_producto', 'mostrar_subcategorias_producto');


function smn_product_categories_by_id( $atts ) {
    // Atributos por defecto
    $atts = shortcode_atts( array(
        'ids' => '', // IDs de las categorías separadas por comas
    ), $atts, 'product_categories_by_id' );

    // Convertir la lista de IDs en un array
    $ids = explode( ',', $atts['ids'] );

    // Iniciar la salida
    $output = '<div class="woocommerce">';
    $output .= '<ul class="products">';

    // Iterar sobre cada ID
    foreach ( $ids as $id ) {
        // Obtener la categoría del producto
        $category = get_term( $id, 'product_cat' );

        // Comprobar si la categoría existe
        if ( $category && ! is_wp_error( $category ) ) {
            // Obtener el enlace a la categoría
            $link = get_term_link( $category );

            // Obtener la imagen de la categoría
            $thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
            $image = wp_get_attachment_url( $thumbnail_id );

            // Obtener el color de fondo
            $background_color = get_field('color_cat_product', $category);

            // Añadir la categoría a la salida
            $output .= '<li class="product-category product" style="background-color:' . esc_attr( $background_color ) . ';">';
            $output .= '<a href="' . esc_url( $link ) . '" aria-label="' . esc_attr( $category->name ) . '">';
            $output .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '">';
            $output .= '<p class="woocommerce-loop-category__title">' . esc_html( $category->name ) . '</p>';
            $output .= '</a>';
            $output .= '</li>';
        }
    }

    // Finalizar la salida
    $output .= '</ul></div>';

    return $output;
}
add_shortcode( 'product_categories_by_id', 'smn_product_categories_by_id' );


add_shortcode('slider', 'smn_slider');
function smn_slider() {
		
    $args = array(
        'post_type' => 'slide',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );

    $query = new WP_Query($args);

    $r = '';

    if ($query->have_posts()) {

        $r .= '<div id="slider-homepage" class="alignfull">';

        // SLICK SLIDER

            $r .= '<div class="slick-slider">';

            while ($query->have_posts()) {

                $query->the_post();

                $r .= '<div class="slick-slide">';
                    $r .= '<div class="slick-slide--content">';
                        $r .= get_the_content();
                    $r .= '</div>';
                $r .= '</div>';
            }

            $r .= '</div>';

        $r .= '</div>';

        ob_start();
        ?>

        <script>
            jQuery('.slick-slider').slick({
                autoplay: true,
                autoplaySpeed: 5000,
                arrows: true,
                dots: false,
                fade: true,
                pauseOnHover: false,
                pauseOnFocus: false,
                speed: 1000,
            });
        </script>

        <?php
        $r .= ob_get_clean();

    }

    wp_reset_postdata();
	
	return $r;
}


//function mostrar_productos_categoria_actual($atts) {
//    // Obtén la categoría actual
//    $categoria_actual = get_queried_object();
//
//    // Verifica si es una categoría
//    if (is_a($categoria_actual, 'WP_Term') && taxonomy_exists('category')) {
//        // Obtén la ID de la categoría actual
//        $categoria_actual_id = $categoria_actual->term_id;
//
//        // Crea una nueva consulta para obtener los productos de la categoría actual
//        $args = array(
//            'post_type' => 'product',
//            'posts_per_page' => -1,
//            'tax_query' => array(
//                array(
//                    'taxonomy' => 'category',
//                    'field'    => 'term_id',
//                    'terms'    => $categoria_actual_id,
//                ),
//            ),
//        );
//        $query = new WP_Query($args);
//
//        // Comprueba si la consulta tiene productos
//        if ($query->have_posts()) {
//            ob_start();
//            echo '<ul class="products columns-3">';
//            // Itera sobre cada producto
//            while ($query->have_posts()) {
//                $query->the_post();
//                wc_get_template_part('content', 'product');
//            }
//            echo '</ul>';
//            $output = ob_get_clean();
//
//            // Restablece la consulta principal
//            wp_reset_postdata();
//
//            return $output;
//        }
//    }
//
//    // En caso de no encontrar productos o no estar en una categoría
//    return '';
//}
//
//// Registra el shortcode
//add_shortcode('mostrar_productos_categoria_actual', 'mostrar_productos_categoria_actual');


