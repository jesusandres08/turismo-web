<?php
/**
 * Archive template for Hoteles Custom Post Type
 */

get_header();
?>

<main class="site-main hoteles-page">

    <!-- Hero Section -->
    <section class="hoteles-hero">
        <div class="hoteles-hero-content">
            <h1 class="hoteles-hero-titulo">
                <i class="fas fa-hotel"></i>
                Encuentra tu Hotel Ideal
            </h1>
            <p class="hoteles-hero-descripcion">Descubre los mejores alojamientos con las mejores comodidades</p>
        </div>
    </section>

    <!-- Filtros Section -->
    <section class="hoteles-filtros-section">
        <div class="hoteles-container">
            <div class="filtros-wrapper">

                <!-- Búsqueda -->
                <div class="filtro-item filtro-busqueda">
                    <label for="hoteles-search">
                        <i class="fas fa-search"></i>
                        Buscar
                    </label>
                    <input type="text" id="hoteles-search" placeholder="Buscar por nombre...">
                </div>

                <!-- Filtro Ubicación -->
                <div class="filtro-item">
                    <label for="filtro-ubicacion">
                        <i class="fas fa-map-marker-alt"></i>
                        Ubicación
                    </label>
                    <select id="filtro-ubicacion">
                        <option value="">Todas las ubicaciones</option>
                        <?php
                        $ubicaciones = get_terms(array(
                            'taxonomy' => 'ubicacion-hotel',
                            'hide_empty' => true,
                        ));
                        foreach ($ubicaciones as $ubicacion) {
                            echo '<option value="' . esc_attr($ubicacion->slug) . '">' . esc_html($ubicacion->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Filtro Categoría -->
                <div class="filtro-item">
                    <label for="filtro-categoria">
                        <i class="fas fa-star"></i>
                        Categoría
                    </label>
                    <select id="filtro-categoria">
                        <option value="">Todas las categorías</option>
                        <?php
                        $categorias = get_terms(array(
                            'taxonomy' => 'categoria-hotel',
                            'hide_empty' => true,
                        ));
                        foreach ($categorias as $categoria) {
                            echo '<option value="' . esc_attr($categoria->slug) . '">' . esc_html($categoria->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Filtro Estrellas -->
                <div class="filtro-item">
                    <label for="filtro-estrellas">
                        <i class="fas fa-award"></i>
                        Calificación
                    </label>
                    <select id="filtro-estrellas">
                        <option value="">Todas</option>
                        <option value="5">5 Estrellas</option>
                        <option value="4">4 Estrellas o más</option>
                        <option value="3">3 Estrellas o más</option>
                    </select>
                </div>

                <!-- Botón Limpiar Filtros -->
                <button id="limpiar-filtros" class="btn-limpiar-filtros">
                    <i class="fas fa-redo"></i>
                    Limpiar
                </button>

            </div>
        </div>
    </section>

    <!-- Grid de Hoteles -->
    <section class="hoteles-grid-section">
        <div class="hoteles-container">

            <!-- Contador de resultados -->
            <div class="hoteles-resultados-info">
                <span id="hoteles-count">0 hoteles encontrados</span>
            </div>

            <!-- Grid -->
            <div class="hoteles-grid" id="hoteles-grid">
                <?php
                $hoteles_args = array(
                    'post_type' => 'hotel',
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC',
                );

                $hoteles_query = new WP_Query($hoteles_args);

                if ($hoteles_query->have_posts()) :
                    while ($hoteles_query->have_posts()) : $hoteles_query->the_post();

                        // Obtener meta datos
                        $precio = get_post_meta(get_the_ID(), '_hotel_precio', true);
                        $estrellas = get_post_meta(get_the_ID(), '_hotel_estrellas', true);
                        $servicios = get_post_meta(get_the_ID(), '_hotel_servicios', true);
                        $servicios_array = $servicios ? json_decode($servicios, true) : array();
                        $telefono = get_post_meta(get_the_ID(), '_hotel_telefono', true);
                        $direccion = get_post_meta(get_the_ID(), '_hotel_direccion', true);
                        $galeria = get_post_meta(get_the_ID(), '_hotel_galeria', true);
                        $galeria_urls = $galeria ? array_map('trim', explode(',', $galeria)) : array();

                        // Obtener taxonomías
                        $ubicaciones = get_the_terms(get_the_ID(), 'ubicacion-hotel');
                        $categorias = get_the_terms(get_the_ID(), 'categoria-hotel');

                        $ubicacion_slug = $ubicaciones && !is_wp_error($ubicaciones) ? $ubicaciones[0]->slug : '';
                        $categoria_slug = $categorias && !is_wp_error($categorias) ? $categorias[0]->slug : '';
                        ?>

                        <article class="hotel-card"
                                 data-ubicacion="<?php echo esc_attr($ubicacion_slug); ?>"
                                 data-categoria="<?php echo esc_attr($categoria_slug); ?>"
                                 data-estrellas="<?php echo esc_attr($estrellas); ?>"
                                 data-nombre="<?php echo esc_attr(strtolower(get_the_title())); ?>">

                            <!-- Imagen del Hotel -->
                            <div class="hotel-card-imagen">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large', array('class' => 'hotel-img')); ?>
                                <?php else : ?>
                                    <img src="<?php echo esc_url(TURISMO_URI . '/images/placeholder.svg'); ?>"
                                         alt="<?php the_title_attribute(); ?>"
                                         class="hotel-img">
                                <?php endif; ?>

                                <!-- Badge de Categoría -->
                                <?php if ($categorias && !is_wp_error($categorias)) : ?>
                                    <span class="hotel-badge"><?php echo esc_html($categorias[0]->name); ?></span>
                                <?php endif; ?>

                                <!-- Galería Icon -->
                                <?php if (!empty($galeria_urls) && count($galeria_urls) > 1) : ?>
                                    <button class="hotel-galeria-btn" data-hotel-id="<?php echo get_the_ID(); ?>">
                                        <i class="fas fa-images"></i>
                                        <?php echo count($galeria_urls); ?> fotos
                                    </button>
                                <?php endif; ?>
                            </div>

                            <!-- Contenido de la Card -->
                            <div class="hotel-card-contenido">

                                <!-- Header con Título y Ubicación -->
                                <div class="hotel-card-header">
                                    <h3 class="hotel-titulo">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    <?php if ($ubicaciones && !is_wp_error($ubicaciones)) : ?>
                                        <p class="hotel-ubicacion">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo esc_html($ubicaciones[0]->name); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <!-- Calificación con Estrellas -->
                                <?php if ($estrellas) : ?>
                                    <div class="hotel-calificacion">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $estrellas) {
                                                echo '<i class="fas fa-star"></i>';
                                            } else {
                                                echo '<i class="far fa-star"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Descripción -->
                                <div class="hotel-descripcion">
                                    <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                                </div>

                                <!-- Servicios Destacados -->
                                <?php if (!empty($servicios_array)) : ?>
                                    <div class="hotel-servicios">
                                        <?php
                                        $servicios_icons = array(
                                            'wifi' => 'fa-wifi',
                                            'estacionamiento' => 'fa-parking',
                                            'piscina' => 'fa-swimming-pool',
                                            'restaurante' => 'fa-utensils',
                                            'gimnasio' => 'fa-dumbbell',
                                            'spa' => 'fa-spa',
                                            'aire_acondicionado' => 'fa-snowflake',
                                            'bar' => 'fa-cocktail',
                                        );

                                        $count = 0;
                                        foreach ($servicios_array as $servicio) {
                                            if ($count >= 4) break;
                                            $icon = isset($servicios_icons[$servicio]) ? $servicios_icons[$servicio] : 'fa-check';
                                            echo '<span class="servicio-icon" title="' . esc_attr(ucfirst(str_replace('_', ' ', $servicio))) . '">';
                                            echo '<i class="fas ' . esc_attr($icon) . '"></i>';
                                            echo '</span>';
                                            $count++;
                                        }
                                        if (count($servicios_array) > 4) {
                                            echo '<span class="servicios-mas">+' . (count($servicios_array) - 4) . '</span>';
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Footer con Precio y Botones -->
                                <div class="hotel-card-footer">
                                    <div class="hotel-precio">
                                        <?php if ($precio) : ?>
                                            <span class="precio-label">Desde</span>
                                            <span class="precio-valor"><?php echo esc_html($precio); ?></span>
                                            <span class="precio-periodo">/ noche</span>
                                        <?php else : ?>
                                            <span class="precio-consultar">Consultar precio</span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="hotel-acciones">
                                        <a href="<?php the_permalink(); ?>" class="btn-ver-hotel">
                                            Ver detalles
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>

                            </div><!-- .hotel-card-contenido -->

                        </article>

                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <div class="no-hoteles-mensaje">
                        <i class="fas fa-hotel"></i>
                        <h3>No hay hoteles disponibles</h3>
                        <p>Agrega nuevos hoteles desde el panel de administración.</p>
                    </div>
                <?php endif; ?>
            </div><!-- .hoteles-grid -->

            <!-- Mensaje sin resultados -->
            <div class="no-resultados-mensaje" id="no-resultados" style="display: none;">
                <i class="fas fa-search"></i>
                <h3>No se encontraron hoteles</h3>
                <p>Intenta ajustar los filtros para ver más resultados.</p>
            </div>

        </div><!-- .hoteles-container -->
    </section>

</main><!-- .site-main -->

<!-- Modal de Galería -->
<div class="hotel-galeria-modal" id="galeria-modal">
    <div class="galeria-modal-overlay" onclick="closeGaleriaModal()"></div>
    <div class="galeria-modal-contenido">
        <button class="galeria-modal-close" onclick="closeGaleriaModal()">
            <i class="fas fa-times"></i>
        </button>
        <div class="galeria-slider">
            <button class="galeria-prev" onclick="galeriaSlide(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="galeria-imagen-container">
                <img id="galeria-imagen-actual" src="" alt="Imagen del hotel">
            </div>
            <button class="galeria-next" onclick="galeriaSlide(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="galeria-contador">
            <span id="galeria-contador-texto">1 / 1</span>
        </div>
    </div>
</div>

<script>
// Variables globales
let galeriaActual = [];
let galeriaIndice = 0;

// Filtros y búsqueda
document.addEventListener('DOMContentLoaded', function() {
    const hotelesCards = document.querySelectorAll('.hotel-card');
    const searchInput = document.getElementById('hoteles-search');
    const filtroUbicacion = document.getElementById('filtro-ubicacion');
    const filtroCategoria = document.getElementById('filtro-categoria');
    const filtroEstrellas = document.getElementById('filtro-estrellas');
    const limpiarBtn = document.getElementById('limpiar-filtros');
    const hotelesCount = document.getElementById('hoteles-count');
    const noResultados = document.getElementById('no-resultados');

    function filtrarHoteles() {
        const searchTerm = searchInput.value.toLowerCase();
        const ubicacionValue = filtroUbicacion.value;
        const categoriaValue = filtroCategoria.value;
        const estrellasValue = filtroEstrellas.value;

        let visibleCount = 0;

        hotelesCards.forEach(card => {
            const nombre = card.getAttribute('data-nombre');
            const ubicacion = card.getAttribute('data-ubicacion');
            const categoria = card.getAttribute('data-categoria');
            const estrellas = parseInt(card.getAttribute('data-estrellas')) || 0;

            let mostrar = true;

            // Filtro de búsqueda
            if (searchTerm && !nombre.includes(searchTerm)) {
                mostrar = false;
            }

            // Filtro de ubicación
            if (ubicacionValue && ubicacion !== ubicacionValue) {
                mostrar = false;
            }

            // Filtro de categoría
            if (categoriaValue && categoria !== categoriaValue) {
                mostrar = false;
            }

            // Filtro de estrellas
            if (estrellasValue) {
                const minEstrellas = parseInt(estrellasValue);
                if (estrellas < minEstrellas) {
                    mostrar = false;
                }
            }

            // Mostrar u ocultar card
            if (mostrar) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Actualizar contador
        hotelesCount.textContent = visibleCount + (visibleCount === 1 ? ' hotel encontrado' : ' hoteles encontrados');

        // Mostrar mensaje si no hay resultados
        if (visibleCount === 0) {
            noResultados.style.display = 'flex';
        } else {
            noResultados.style.display = 'none';
        }
    }

    // Event listeners para filtros
    searchInput.addEventListener('input', filtrarHoteles);
    filtroUbicacion.addEventListener('change', filtrarHoteles);
    filtroCategoria.addEventListener('change', filtrarHoteles);
    filtroEstrellas.addEventListener('change', filtrarHoteles);

    // Limpiar filtros
    limpiarBtn.addEventListener('click', function() {
        searchInput.value = '';
        filtroUbicacion.value = '';
        filtroCategoria.value = '';
        filtroEstrellas.value = '';
        filtrarHoteles();
    });

    // Inicializar contador
    filtrarHoteles();

    // Galería de imágenes
    const galeriaButtons = document.querySelectorAll('.hotel-galeria-btn');
    galeriaButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const hotelId = this.getAttribute('data-hotel-id');
            openGaleriaModal(hotelId);
        });
    });
});

// Funciones de galería
function openGaleriaModal(hotelId) {
    const hotelCard = document.querySelector(`.hotel-card[data-hotel-id="${hotelId}"]`);
    const galeriaData = <?php
    // Crear array de galerías para JavaScript
    $galeria_data = array();
    $hoteles_query->rewind_posts();
    while ($hoteles_query->have_posts()) : $hoteles_query->the_post();
        $galeria = get_post_meta(get_the_ID(), '_hotel_galeria', true);
        $galeria_urls = $galeria ? array_map('trim', explode(',', $galeria)) : array();
        if (!empty($galeria_urls)) {
            $galeria_data[get_the_ID()] = $galeria_urls;
        }
    endwhile;
    wp_reset_postdata();
    echo json_encode($galeria_data);
    ?>;

    if (galeriaData[hotelId]) {
        galeriaActual = galeriaData[hotelId];
        galeriaIndice = 0;
        mostrarGaleriaImagen();
        document.getElementById('galeria-modal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeGaleriaModal() {
    document.getElementById('galeria-modal').classList.remove('active');
    document.body.style.overflow = '';
    galeriaActual = [];
    galeriaIndice = 0;
}

function galeriaSlide(direction) {
    galeriaIndice += direction;
    if (galeriaIndice < 0) galeriaIndice = galeriaActual.length - 1;
    if (galeriaIndice >= galeriaActual.length) galeriaIndice = 0;
    mostrarGaleriaImagen();
}

function mostrarGaleriaImagen() {
    if (galeriaActual.length > 0) {
        document.getElementById('galeria-imagen-actual').src = galeriaActual[galeriaIndice];
        document.getElementById('galeria-contador-texto').textContent = `${galeriaIndice + 1} / ${galeriaActual.length}`;
    }
}

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeGaleriaModal();
    }
});

// Navegación con teclado en galería
document.addEventListener('keydown', function(e) {
    if (document.getElementById('galeria-modal').classList.contains('active')) {
        if (e.key === 'ArrowLeft') galeriaSlide(-1);
        if (e.key === 'ArrowRight') galeriaSlide(1);
    }
});
</script>

<?php
get_footer();
?>
