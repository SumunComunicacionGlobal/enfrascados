<div class="wp-block-navigation__social">
    <?php
        // Obtén el ID del bloque reutilizable
        $block_id = 996; // Reemplaza esto con el ID de tu bloque reutilizable

        $block_post = get_post($block_id);

        if ($block_post) {
            $block_content = $block_post->post_content;
            $blocks = parse_blocks($block_content);
            
            // Renderiza cada bloque
            foreach ($blocks as $block) {
                echo render_block($block);
            }
        }
    ?>
</div>

<div class="wp-block-navigation__search">

    <?php
        echo file_get_contents( get_stylesheet_directory_uri() . '/assets/img/cual-es-tu-motivo.svg' );

        $block_content = '<!-- wp:search {"label":"Buscar","showLabel":false,"placeholder":"Ordenar mi despensa...","buttonText":"Buscar","buttonPosition":"button-inside","buttonUseIcon":true,"query":{"post_type":"product"}} /-->';
        echo do_blocks($block_content);
    ?>
</div>
