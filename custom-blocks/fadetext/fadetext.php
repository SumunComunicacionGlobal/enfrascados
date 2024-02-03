<?php

/**
 * Auto Fade Carrousel Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it's parent block.
 */



// Support custom "anchor" values.
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'auto-fade-carrousel-block';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $class_name .= ' align' . $block['align'];
}

$my_block_template = array(
    array( 'core/heading', array(
        'placeholder' => 'Añade un título',
        'level' => 2,
    ) ),
    array( 'core/paragraph', array(
        'placeholder' => 'Añade un párrafo',
    ) ),
);
?>

<div <?php echo $anchor; ?>class="<?php echo esc_attr( $class_name ); ?>">

	<?php the_field( 'fade_text' ); ?>

</div>

    <InnerBlocks template="<?php echo esc_attr( wp_json_encode( $my_block_template ) ); ?>" />
