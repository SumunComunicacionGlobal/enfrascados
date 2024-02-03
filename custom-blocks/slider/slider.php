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
$id = 'swiper-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}
?>

<div class="tabgroup" id="<?php echo $id; ?>">
    <InnerBlocks />
</div>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
	var swiper = new Swiper('#<?php echo esc_attr($id); ?>', {
    autoHeight: true,
	navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        768: {
          slidesPerView: 1,
        },
        1024: {
          slidesPerView: 2,
        },
      },
	});
</script>