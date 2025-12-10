<?php
/**
 * Walker personalizado para agregar iconos a los ítems del menú principal
 */
class Turismo_Menu_Icons_Walker extends Walker_Nav_Menu {
    // Asocia iconos a los slugs o títulos
    private $icons = [
        'inicio' => 'fa-solid fa-house',
        'home' => 'fa-solid fa-house',
        'noticias' => 'fa-solid fa-newspaper',
        'videos' => 'fa-solid fa-video',
        'categorias' => 'fa-solid fa-list',
        'contacto' => 'fa-solid fa-envelope',
        'about' => 'fa-solid fa-user',
        'hoteles' => 'fa-solid fa-hotel',
        'restaurantes' => 'fa-solid fa-utensils',
    ];

    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
        $icon_class = '';
        $slug = sanitize_title($item->title);
        if (isset($this->icons[$slug])) {
            $icon_class = $this->icons[$slug];
        }
        $output .= '<li class="menu-item">';
        $output .= '<a href="' . esc_url($item->url) . '" class="menu-link">';
        if ($icon_class) {
            $output .= '<i class="' . esc_attr($icon_class) . '"></i> ';
        }
        $output .= esc_html($item->title);
        $output .= '</a>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
        $output .= '</li>';
    }
}
