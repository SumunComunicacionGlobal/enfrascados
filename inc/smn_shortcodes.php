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
            return '<img src="' . esc_url($imagen_acf['url']) . '" alt="' . esc_attr($imagen_acf['alt']) . '" />';
        }
    
        // Verificamos si hay una imagen destacada
        elseif ($imagen_destacada_id) {
            // Obtenemos la URL de la imagen
            $imagen_destacada_url = wp_get_attachment_url($imagen_destacada_id);

            // Mostramos la imagen
            return '<img src="' . esc_url($imagen_destacada_url) . '" alt="' . esc_attr($categoria_producto->name) . '" />';
        }
    }

    // En caso de no encontrar una imagen destacada
    return '';
}

// Registramos el shortcode
add_shortcode('mostrar_imagen_destacada_categoria_producto', 'mostrar_imagen_destacada_categoria_producto');


// Shortcode para mostrar subcategorías en una página de archivo de categoría de producto
function mostrar_subcategorias_producto($atts) {
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
            $output = '<div class="wc-block-grid has-3-columns has-multiple-rows has-aligned-buttons"><ul class="wc-block-grid__products subcategories-shortcode">';

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
                $output .= '<h2 class="wc-block-grid__product-title"><a class="wc-block-grid__product-link stretched-link" href="' . get_term_link($subcategoria) . '">' . esc_html($subcategoria->name) . '</a></h2>';
                $output .= '</li>';
            }

            $output .= '</ul></div>';

            return $output;
        }
    }

    // En caso de no encontrar subcategorías o no estar en una categoría de producto
    return 'No se encontraron subcategorías para mostrar.';
}

// Registramos el shortcode
add_shortcode('mostrar_subcategorias_producto', 'mostrar_subcategorias_producto');







