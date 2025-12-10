<?php
/**
 * Categorías para Home
 * Filtros de categorías estilo portal_choix
 */

// Obtener todas las categorías de posts
$categories = get_categories( array(
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 5, // Limitar a 5 categorías principales
    'hide_empty' => true,
));

// Categorías por defecto si no hay suficientes
$default_categories = array(
    array(
        'name' => 'Música',
        'slug' => 'musica',
        'icon' => 'fa-music',
    ),
    array(
        'name' => 'Turismo',
        'slug' => 'turismo',
        'icon' => 'fa-mountain',
    ),
    array(
        'name' => 'Cultura',
        'slug' => 'cultura',
        'icon' => 'fa-landmark',
    ),
    array(
        'name' => 'Aventura',
        'slug' => 'aventura',
        'icon' => 'fa-hiking',
    ),
    array(
        'name' => 'Eventos',
        'slug' => 'eventos',
        'icon' => 'fa-calendar-alt',
    ),
);

// Mapeo de iconos para categorías comunes
$category_icons = array(
    'musica'    => 'fa-music',
    'turismo'   => 'fa-mountain',
    'cultura'   => 'fa-landmark',
    'aventura'  => 'fa-hiking',
    'eventos'   => 'fa-calendar-alt',
    'viajes'    => 'fa-plane',
    'comida'    => 'fa-utensils',
    'arte'      => 'fa-palette',
    'deportes'  => 'fa-football-ball',
    'naturaleza' => 'fa-leaf',
);
?>

<!-- Categorías Card -->
<section class="categorias-home-section">
<div class="categorias-card">
    <h2 class="categorias-titulo">Explorar por Categoría</h2>
    <div class="categorias-grid">

        <!-- Botón "Todos" -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="categoria-btn active"
           data-categoria="todos">
            <i class="fas fa-th"></i> Todos
        </a>

        <?php if ( ! empty( $categories ) ) : ?>
            <?php foreach ( $categories as $category ) : ?>
                <?php
                // Asignar icono basado en el slug de la categoría
                $icon = 'fa-folder';
                foreach ( $category_icons as $slug_pattern => $icon_class ) {
                    if ( strpos( strtolower( $category->slug ), $slug_pattern ) !== false ) {
                        $icon = $icon_class;
                        break;
                    }
                }
                ?>
                <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
                   class="categoria-btn"
                   data-categoria="<?php echo esc_attr( $category->slug ); ?>">
                    <i class="fas <?php echo esc_attr( $icon ); ?>"></i>
                    <?php echo esc_html( $category->name ); ?>
                </a>
            <?php endforeach; ?>
        <?php else : ?>
            <!-- Usar categorías por defecto si no hay suficientes -->
            <?php foreach ( $default_categories as $cat ) : ?>
                <button class="categoria-btn" data-categoria="<?php echo esc_attr( $cat['slug'] ); ?>">
                    <i class="fas <?php echo esc_attr( $cat['icon'] ); ?>"></i>
                    <?php echo esc_html( $cat['name'] ); ?>
                </button>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoriaBtns = document.querySelectorAll('.categoria-btn');

    categoriaBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Si es un botón (no link), prevenir default
            if (this.tagName === 'BUTTON') {
                e.preventDefault();
            }

            // Remover active de todos
            categoriaBtns.forEach(b => b.classList.remove('active'));

            // Agregar active al clickeado
            this.classList.add('active');
        });
    });
});
</script>
