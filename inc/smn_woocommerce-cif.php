<?php
// exit if accessed directly
defined( 'ABSPATH' ) || exit;

define( 'MINIMUM_CART_AMOUNT_FOR_INVOICE', 400 );

add_filter('woocommerce_form_field_args', function($args, $key, $value) {
    if ( in_array($key, ['billing_last_name', 'shipping_last_name']) ) {
        $args['label'] = __('Primer y segundo apellido', 'smn');
    }
    if ( $key === 'billing_company' ) {
        $args['label'] = __('NIF/DNI', 'smn');
        $args['type'] = 'hidden';
        $args['class'] = 'form-row-wide dnone';
        unset($args['autocomplete']);
    }
    if ( $key === 'billing_nif' ) {
        $args['description'] = __('Verifica que el nombre y NIF de facturación que has indicado son los correctos. La factura se generará con el NIF introducido aquí y el texto que hayas indicado en los campos Nombre y Apellidos.', 'smn');
    }
    return $args;
}, 10, 3);

add_filter( 'woocommerce_checkout_fields', 'smn_agregar_campo_factura_y_modificar_checkout_fields' );
function smn_agregar_campo_factura_y_modificar_checkout_fields( $fields ) {

	$fields['billing']['billing_company']['priority'] = 22;
    $cart_total = WC()->cart->get_total('edit');
	$fields['billing']['billing_company']['required'] = true;

    if ( isset( $fields['shipping']['shipping_nif'] ) ) {
        unset( $fields['shipping']['shipping_nif'] );
    }

    if ( $cart_total > MINIMUM_CART_AMOUNT_FOR_INVOICE ) {
        $fields['billing']['billing_nif']['required'] = true;
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
    if ( !is_checkout() )
        return;
    ?>
    
    <script type="text/javascript">
        jQuery(function($){
            // Función para mostrar/ocultar billing_company
            function toggleBillingCompany() {

                // ignore if no quiere_factura field
                if ( ! $('input[name="quiere_factura"]').length ) return;


                if ($('input[name="quiere_factura"]:checked').val() === 'si') {

					// $('#billing_company_field').show();
					// $('#billing_company').prop('required', true);
                    $('#billing_nif_field').show().find('span.optional').addClass('required').removeClass('optional').text('*');
                    $('#billing_nif').prop('required', true);

                } else {

                    $('#billing_nif_field').hide().find('span.required').addClass('optional').removeClass('required').text('(opcional)');
                    $('#billing_nif').prop('required', false).val('');
					// $('#billing_company_field').hide();
					// $('#billing_company').prop('required', false).val('');
                    $('#billing_company').val(''); // Limpiar el campo billing_company
                    $('#billing_nif').val(''); // Limpiar el campo billing_nif

                }
            }

            // Evento al cambiar la selección
            $('input[name="quiere_factura"]').change(toggleBillingCompany);

            // Llamada inicial para el estado por defecto
            toggleBillingCompany();

            // Sincronizar el campo billing_company con el campo billing_nif
            $('#billing_nif').on('input', function() {
                var nifValue = $(this).val().trim();
                $('#billing_company').val(nifValue);
            });

       });
    </script>
    <?php
}
add_action( 'wp_footer', 'smn_ocultar_mostrar_campo_billing_company_js' );

add_action( 'woocommerce_checkout_process', 'nif_validation' ); 
function nif_validation() {     
    if ( isset($_POST['quiere_factura']) && $_POST['quiere_factura'] === 'si' ) {       

        if ( empty($_POST['billing_nif']) ) {
            wc_add_notice( '<a href="#billing_nif_field">' . __('Por favor, añade tu NIF') . '</a>', "error" );     
        }
    }
}