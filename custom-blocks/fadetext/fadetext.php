<?php

/**
 * Fade text Slider Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'fade-text-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Obtén el valor de los campos ACF
$slides_dots = get_field('slides_dots');
$slides_dots_bool = $slides_dots ? 'true' : 'false';

$text_items = get_field('fade_text_items');


?>
<div id="<?php echo $id; ?>" <?php echo get_block_wrapper_attributes(); ?>>

<?php
if( have_rows('fade_text_items') ):
  
  while( have_rows('fade_text_items') ): the_row(); 
    
    $text = get_sub_field('fade_text_item');
      echo '<p>'.$text.'</p>';
        
  endwhile;
    
endif; ?>

</div>

<!-- Initialize Swiper -->
<script>

jQuery('#<?php echo $id; ?>').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  dots: <?php echo $slides_dots_bool; ?>,
  arrows: false,
  fade: true,
  autoplay: true,
  infinite: true,
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