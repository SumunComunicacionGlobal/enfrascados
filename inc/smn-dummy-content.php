<?php


add_filter( 'term_description', 'filter_term_description', 10, 1 );
function filter_term_description( $description ) { 

    if (is_admin()) return $description;

    // Si la descripción está vacía, agregamos contenido ficticio
    if ('' == $description ) {
        $description .= '<h2>Esto es un H2</h2>';
        $description .= '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>';
    }

    return $description;
} 