<?php
// exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( !current_user_can( 'manage_options' ) ) {
    return;
}

define( 'MINIMUM_CART_AMOUNT_FOR_INVOICE', 400 );

add_filter('woocommerce_checkout_fields', 'smn_override_checkout_fields');
function smn_override_checkout_fields($fields) {
    $fields['billing']['billing_company']['label'] = __( 'DNI/CIF' );
    return $fields;
}

add_filter( 'woocommerce_checkout_fields', 'smn_agregar_campo_factura_y_modificar_checkout_fields' );
function smn_agregar_campo_factura_y_modificar_checkout_fields( $fields ) {

    $fields['billing']['billing_company']['required'] = true;
    $cart_total = WC()->cart->get_total('edit');

    if ( $cart_total > MINIMUM_CART_AMOUNT_FOR_INVOICE ) {
        return $fields;
    }

    // Añadir el campo de radio personalizado
    $fields['billing']['quiere_factura'] = array(
        'type'      => 'radio',
        'label'     => __('¿Quieres factura?', 'smn' ),
        'class'     => array('form-row-wide'),
        'options'   => array(
            'no'    => __('No', 'smn'),
            'si'    => __('Sí', 'smn'),
        ),
        'required'  => true,
        'priority'  => 21, // Ajustar para que aparezca justo antes de billing_company
    );

    // Ajustar la prioridad de billing_company para asegurar el orden
    if (isset($fields['billing']['billing_company'])) {
        $fields['billing']['billing_company']['priority'] = 22;
    }

    return $fields;
}

// Quitar el error de validación de CIF si no quiere factura
add_action( 'woocommerce_after_checkout_validation', 'smn_validate_cif', 10, 2 );
function smn_validate_cif( $data, $errors ) {

    if ( $data['quiere_factura'] == 'no' ) {
        $errors->remove( 'billing_company_required' );
    }

}

function smn_ocultar_mostrar_campo_billing_company_js() {
    ?>

    <style>
        input[type="radio"] {
            float: left;
            margin-right: 8px;
            margin-top: .25em;
        }
        input[type="radio"] + label {
            line-height: 1.2 !important;
        }
    </style>

    <script type="text/javascript">
        jQuery(function($){
            // Función para mostrar/ocultar billing_company
            function toggleBillingCompany() {

                // ignore if no quiere_factura field
                if ( ! $('input[name="quiere_factura"]').length ) return;


                if ($('input[name="quiere_factura"]:checked').val() === 'si') {
                    $('#billing_company_field').show().find('#billing_company').prop('required', true);
                } else {
                    $('#billing_company_field').hide().find('#billing_company').prop('required', false).val('');
                }
            }

            // Evento al cambiar la selección
            $('input[name="quiere_factura"]').change(toggleBillingCompany);

            // Llamada inicial para el estado por defecto
            toggleBillingCompany();
        });
    </script>
    <?php
}
add_action( 'wp_footer', 'smn_ocultar_mostrar_campo_billing_company_js' );
