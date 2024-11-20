<?php

/**
 * Slider Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'slick-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Obtén el valor de los campos ACF
$slides_to_show = get_field('slides_to_show');

$slides_dots = get_field('slides_dots');
$slides_dots_bool = $slides_dots ? 'true' : 'false';

$slides_arrows = get_field('slides_arrows');
$slides_arrows_bool = $slides_arrows ? 'true' : 'false';

$slides_fade = get_field('slides_arrows');
$slides_fade_bool = $slides_fade ? 'true' : 'false';

?>
<div id="<?php echo $id; ?>" <?php echo get_block_wrapper_attributes(); ?>>
    <InnerBlocks />
</div>

<?php if ( !is_admin() ) { ?>

    <!-- Initialize Swiper -->
    <script>
    
    jQuery('#<?php echo $id; ?> .acf-innerblocks-container').slick({
      slidesToShow: <?php echo $slides_to_show; ?>,
      slidesToScroll: 1,
      dots: <?php echo $slides_dots_bool; ?>,
      arrows: <?php echo $slides_arrows_bool; ?>,
      fade: <?php echo $slides_fade_bool; ?>,
      autoplay: true,
      infinite: false,
      speed: 500,
      
      responsive: [
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        } ]
    });
    
    
    </script>

<?php } ?>
