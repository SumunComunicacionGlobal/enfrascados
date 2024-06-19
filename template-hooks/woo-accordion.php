<?php 

$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>

<div data-block-name="woocommerce/product-details" class="wp-block-woocommerce-product-details">

    <div class="woocommerce-tabs wc-tabs-wrapper">
        <ul class="tabs wc-tabs resp-tabs-list" role="tablist">
        <?php 
        
        // set incremental value to use to check for the first tab which is active on page load	
        $i = 0;
        
        // loop through each tab.
        // Nest the tab content with the tab title
        foreach ( $tabs as $key => $tab ) : ?>

            <li class="<?php echo esc_attr( $key ); ?>_tab <?php if(($i == 0) || ($i=2)) echo 'active-tab'; ?>" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
                <a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
                
                <div class="tab-button <?php if($i == 0) echo 'clicked'; ?>">
                    <?php echo file_get_contents( get_stylesheet_directory_uri() . '/assets/icons/chevron-down.svg' ); ?>
                </div>
            </li>

            <div class="resp-tabs-container">

                <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab <?php if($i == 0) echo 'active'; ?>" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
                    <?php call_user_func( $tab['callback'], $key, $tab ); ?>
                </div>

            </div>

            <?php 
                $i++;
                endforeach; 
            ?>
        </ul>

    </div>
</div>
<?php endif;

