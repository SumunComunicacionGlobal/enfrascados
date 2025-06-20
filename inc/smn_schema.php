<?php
// exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Remove the generated product schema markup from the Product Category and Shop pages.
 */
function wc_remove_product_schema_product_archive() {
    remove_action( 'woocommerce_shop_loop', array( WC()->structured_data, 'generate_product_data' ), 10, 0 );
}
add_action( 'woocommerce_init', 'wc_remove_product_schema_product_archive' );

/**
 * Remove structured data output on all pages.
 */
function wc_remove_output_structured_data() { 
    // Remove structured data output from the footer of all pages.
    if ( function_exists( 'WC' ) && WC()->structured_data ) {
        remove_action( 'wp_footer', array( WC()->structured_data, 'output_structured_data' ), 10 ); 
    }
} 
// Add the 'wc_remove_output_structured_data' function to the 'init' action.
add_action( 'init', 'wc_remove_output_structured_data' );



add_action('wp_head', 'smn_agregar_schema_product_cat', 1);
function smn_agregar_schema_product_cat() {

    if (is_product_category() || is_shop() ) {

        global $wp_query;

        $total_pages = $wp_query->max_num_pages;
        $products_in_current_page = $wp_query->posts;
        $term_url = get_term_link( get_queried_object() );

        // Create php array and encode it to json
        $products = array();
        foreach ($products_in_current_page as $p) {
            $product = wc_get_product( $p->ID );
            $product_id = $p->ID;
            $product_name = get_the_title( $product_id );
            // remove text from <span to the end of string in $product_name
            $product_name = preg_replace('/<span.*$/s', '', $product_name);
            // remove long hyphen from $product_name
            $product_name = str_replace('&#8211;', '', $product_name);
            // regex to get span.post-title-subtitle content from $product_name
            $pattern = '/<span class="post-title-subtitle">(.*)<\/span>/';
            preg_match($pattern, $product_name, $matches);

            $product_name = strip_tags($product_name);
            $product_name = trim($product_name);
            
            $product_image = get_the_post_thumbnail_url( $product_id, 'full' );
            $product_price = get_post_meta( $product_id, '_price', true );
            $product_url = get_permalink( $product_id );
            $current_year = date('Y');
            $price_valid_until = $current_year . '-12-31';
            $availability = $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock';

            $pagination = array();
            

            $product_data = array(
                '@type' => 'Product',
                'name' => $product_name,
                'image' => $product_image,
                'offers' => array(
                    '@type' => 'Offer',
                    'priceCurrency' => 'EUR',
                    'price' => $product_price,
                    'priceValidUntil' => $price_valid_until,
                    'itemCondition' => 'https://schema.org/NewCondition',
                    'availability' => $availability,
                    'url' => $product_url,
                    'seller' => array(
                        '@type' => 'Organization',
                        'name' => 'Enfrascados Shop'
                    )
                ),
            );

            if ( !empty($matches) ) {
                $additional_type = $matches[1];
                $additional_type = str_replace(' UD.', ' unidades', $additional_type);
                $product_data['additionalType'] = $additional_type;
            }

            array_push($products, $product_data);


            if ( $total_pages && $total_pages > 1 ) {

                $pagination = array(
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => array()
                );

                for ($i = 1; $i <= $total_pages; $i++) {
                    $page_url = get_term_link( get_queried_object() );
                    if ( $i > 1 ) {
                        $page_url = $page_url . 'page/' . $i . '/';
                    }
                    $page_name = 'Página ' . $i;
                    $page_data = array(
                        '@type' => 'ListItem',
                        'position' => $i,
                        'name' => $page_name,
                        'item' => $page_url
                    );
                    array_push($pagination['itemListElement'], $page_data);
                }

                $pagination['totalItems'] = $total_pages;

            }

        }

        if ( $products ) {

            // create php array with schema data
            $schema_data = array(
                '@context' => 'https://schema.org',
                '@type' => 'ItemList',
                'itemListElement' => $products,
                'mainEntityOfPage' => array(
                    '@type' => ['WebPage', 'CollectionPage'],
                    '@id' => $term_url
                ),
                'author' => array(
                    '@type' => 'Organization',
                    'name' => 'Enfrascados Shop',
                    'url' => get_home_url()
                ),
            );

            if ( $pagination ) {
                $schema_data['pagination'] = $pagination;
            }

            $schema = '<script type="application/ld+json" id="enfrascados-product-cat-schema">' . json_encode($schema_data) . '</script>';
            echo '<!-- Schema para categoría de producto -->';
            echo $schema;

        }

    }
}


 
// insertar schema personalizado en header
add_action('wp_head', 'smn_agregar_schema_product_single', 1 );
function smn_agregar_schema_product_single() {
	
    if ( is_singular( 'product' ) ) {
		global $product;
        global $post;

        if ( !$product || $product == null || !is_a( $product, 'WC_Product' ) ) {
            return;
        }

        $product_name = esc_html( $post->post_title );
        $product_image_url = get_the_post_thumbnail_url();
        $product_short_description = strip_tags( get_the_excerpt() );
        $product_short_description = str_replace( array( "\n", "\r" ), ' ', $product_short_description );

        $primary_category_id = get_post_meta( get_the_ID(), 'rank_math_primary_product_cat', true );
        if ( ! $primary_category_id ) {
            $primary_category_id = wc_get_product_cat_ids( get_the_ID() )[0];
        }
        $product_category = get_term( $primary_category_id, 'product_cat' )->name;
        $primary_category_ancestors = get_ancestors( $primary_category_id, 'product_cat' );
        if ( $primary_category_ancestors ) {
            $primary_category_ancestors = array_reverse( $primary_category_ancestors );
            $hierarchy = array();
            foreach ( $primary_category_ancestors as $ancestor_id ) {
                $ancestor = get_term( $ancestor_id, 'product_cat' )->name;
                $hierarchy[] = $ancestor;
            }
            $hierarchy[] = $product_category;
            $product_category = implode( ' &gt; ', $hierarchy );
        }

        $product_related_schema = '';
		$product_cross_sells = $product->get_cross_sell_ids();
        if ( $product_cross_sells ) {

            $related_products = '[';
            foreach ( $product_cross_sells as $product_id ) {
                $product_obj = get_post( $product_id );

                if ( ! $product_obj ) {
                    continue;
                }
                
                $related_products .= '{
                    "@type": "Product",
                    "name": "'. $product_obj->post_title . '",
                    "url": "' . get_permalink( $product_id ) . '"
                },';

            }

			// remove last comma
			$related_products = rtrim( $related_products, ',' );
			$related_products .= ']';


            $product_related_schema = ',"isRelatedTo":' . $related_products;
        }

        ob_start();
        ?>
        <!-- Schema para producto individual -->
        <script type="application/ld+json" id="enfrascados-product-single-schema">
            {
            "@context": "https://schema.org/",
            "@type": "Product",
            "name": "<?php echo $product_name; ?>",
            "image": "<?php echo $product_image_url; ?>",
            "description": "<?php echo $product_short_description; ?>",
            "category": "<?php echo $product_category; ?>",
            "brand": {
                "@type": "Brand",
                "name": "Enfrascados"
            },


            "offers": {
                "@type": "Offer",
                "url": "<?php the_permalink(); ?>",
                "priceCurrency": "EUR",
                "price": "<?php echo get_post_meta( get_the_ID(), '_price', true ); ?>",
                "availability": "https://schema.org/InStock",
                "seller": {
                    "@type": "Organization",
                    "name": "Enfrascados",
                    "url": "https://enfrascados.shop/",
                    "description":"Enfrascados es un e-commerce de envases de vidrio, botellas, tarros o frascos, para que todas las personas puedan comprar online frascos de vidrio y tengan a su alcance opciones sencillas, bellas y ecológicas para contener, conservar, embotar, almacenar o decorar sus alimentos o bebidas, o simplemente tener un motivo para tener un frasco."
                }
            },


            "audience": {
                "@type": "Audience",
                "audienceType": "B2C Consumidores que necesitan botellas o frascos de vidrio o cristal para guardar, embotar, conservar sus alimentos, incluso para decoración, DIY, ideas originales. Y B2B pymes o pequeños productores de alimentos que necesitan envases de cristal tipo frascos, botes, tarros o tuppers para envasar, conservar y distribuir su producción de miel, aceite, mermeladas, alimentos en conserva, legumbres, cereales, cervezas, licores, bebidas alcohólicas, etc...",
                "geographicArea": {
                    "@type": "AdministrativeArea",
                    "name": "España"
                }
            }

            <?php echo $product_related_schema; ?>
            
            ,"slogan": "Un frasco es un frasco. Y esto no va del frasco, sino de tu motivo para usarlo. Tener un motivo significa tener ganas. Porque los motivos importan. Artículos de vidrio que encontrarás en nuestra tienda ONLINE"
            }

        </script>

    <?php }
    $schema = ob_get_clean();
    $schema = str_replace( array( "\n", "\r" ), ' ', $schema );
    $schema = str_replace( array( '    ' ), '', $schema );
    echo $schema;

}