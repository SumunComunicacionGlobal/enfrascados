<?php
/**
 * Title: Hero for pages
 * Slug: enfrascados/hero-page
 * Categories: Header
 * Block Types: core/template-part/hero
 */
?>

<?php
$hero = get_field('hero_pages'); // Reemplaza 'nombre_del_campo_acf' con el nombre de tu campo ACF

if($hero) : // Si el valor del campo ACF es verdadero
?>
    <div class="wp-block-group"> HERO AQUI<!-- Tu div aquí --> </div>
<?php
endif;
?>


<?php
if( get_field('hero_pages') ) {
    // Do something.
    echo 'Hello';
}
?>
<!-- wp:group {"align":"wide","style":{"elements":{"link":{"color":{"text":"var:preset|color|background"}}},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|80"},"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"backgroundColor":"primary","textColor":"background","layout":{"type":"constrained"},"metadata":{"name":"Hero Page"}} -->
<div class="wp-block-group alignwide has-background-color has-primary-background-color has-text-color has-background has-link-color" id="hero" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--80);padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:post-title {"level":1} /-->

<!-- wp:woocommerce/breadcrumbs {"align":""} /--></div>
<!-- /wp:group -->