<?php
/**
 * Archive template for Atractivos Turísticos Custom Post Type
 */

get_header();
?>

<main class="site-main atractivos-page">

    <!-- Hero Section -->
    <section class="atractivos-hero">
        <div class="atractivos-hero-content">
            <h1 class="atractivos-hero-titulo">
                <i class="fas fa-map-marked-alt"></i>
                Explora Atractivos Turísticos
            </h1>
            <p class="atractivos-hero-descripcion">Descubre los lugares más increíbles y experiencias únicas</p>
        </div>
    </section>

    <!-- Filtros Section -->
    <section class="atractivos-filtros-section">
        <div class="atractivos-container">
            <div class="filtros-wrapper">

                <!-- Búsqueda -->
                <div class="filtro-item filtro-busqueda">
                    <label for="atractivos-search">
                        <i class="fas fa-search"></i>
                        Buscar
                    </label>
                    <input type="text" id="atractivos-search" placeholder="Buscar por nombre...">
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
                            'taxonomy' => 'ubicacion-atractivo',
                            'hide_empty' => true,
                        ));
                        foreach ($ubicaciones as $ubicacion) {
                            echo '<option value="' . esc_attr($ubicacion->slug) . '">' . esc_html($ubicacion->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Filtro Tipo -->
                <div class="filtro-item">
                    <label for="filtro-tipo">
                        <i class="fas fa-tag"></i>
                        Tipo
                    </label>
                    <select id="filtro-tipo">
                        <option value="">Todos los tipos</option>
                        <?php
                        $tipos = get_terms(array(
                            'taxonomy' => 'tipo-atractivo',
                            'hide_empty' => true,
                        ));
                        foreach ($tipos as $tipo) {
                            echo '<option value="' . esc_attr($tipo->slug) . '">' . esc_html($tipo->name) . '</option>';
                        }
                        ?>
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

    <!-- Grid de Atractivos -->
    <section class="atractivos-grid-section">
        <div class="atractivos-container">

            <!-- Contador de resultados -->
            <div class="atractivos-resultados-info">
                <span id="atractivos-count">0 atractivos encontrados</span>
            </div>

            <!-- Grid -->
            <div class="atractivos-grid" id="atractivos-grid">
                <?php
                $atractivos_args = array(
                    'post_type' => 'atractivo',
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC',
                );

                $atractivos_query = new WP_Query($atractivos_args);

                if ($atractivos_query->have_posts()) :
                    while ($atractivos_query->have_posts()) : $atractivos_query->the_post();

                        // Obtener meta datos
                        $precio_entrada = get_post_meta(get_the_ID(), '_atractivo_precio_entrada', true);
                        $horario = get_post_meta(get_the_ID(), '_atractivo_horario', true);
                        $caracteristicas = get_post_meta(get_the_ID(), '_atractivo_caracteristicas', true);
                        $caracteristicas_array = $caracteristicas ? json_decode($caracteristicas, true) : array();
                        $telefono = get_post_meta(get_the_ID(), '_atractivo_telefono', true);
                        $direccion = get_post_meta(get_the_ID(), '_atractivo_direccion', true);
                        $galeria = get_post_meta(get_the_ID(), '_atractivo_galeria', true);
                        $galeria_urls = $galeria ? array_map('trim', explode(',', $galeria)) : array();

                        // Obtener taxonomías
                        $ubicaciones = get_the_terms(get_the_ID(), 'ubicacion-atractivo');
                        $tipos = get_the_terms(get_the_ID(), 'tipo-atractivo');

                        $ubicacion_slug = $ubicaciones && !is_wp_error($ubicaciones) ? $ubicaciones[0]->slug : '';
                        $tipo_slug = $tipos && !is_wp_error($tipos) ? $tipos[0]->slug : '';
                        ?>

                        <article class="atractivo-card"
                                 data-ubicacion="<?php echo esc_attr($ubicacion_slug); ?>"
                                 data-tipo="<?php echo esc_attr($tipo_slug); ?>"
                                 data-nombre="<?php echo esc_attr(strtolower(get_the_title())); ?>">

                            <!-- Imagen del Atractivo -->
                            <div class="atractivo-card-imagen">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large', array('class' => 'atractivo-img')); ?>
                                <?php else : ?>
                                    <img src="<?php echo esc_url(TURISMO_URI . '/images/placeholder.svg'); ?>"
                                         alt="<?php the_title_attribute(); ?>"
                                         class="atractivo-img">
                                <?php endif; ?>

                                <!-- Badge de Tipo -->
                                <?php if ($tipos && !is_wp_error($tipos)) : ?>
                                    <span class="atractivo-badge"><?php echo esc_html($tipos[0]->name); ?></span>
                                <?php endif; ?>

                                <!-- Galería Icon -->
                                <?php if (!empty($galeria_urls) && count($galeria_urls) > 1) : ?>
                                    <button class="atractivo-galeria-btn" data-atractivo-id="<?php echo get_the_ID(); ?>">
                                        <i class="fas fa-images"></i>
                                        <?php echo count($galeria_urls); ?> fotos
                                    </button>
                                <?php endif; ?>
                            </div>

                            <!-- Contenido de la Card -->
                            <div class="atractivo-card-contenido">

                                <!-- Header con Título y Ubicación -->
                                <div class="atractivo-card-header">
                                    <h3 class="atractivo-titulo">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    <?php if ($ubicaciones && !is_wp_error($ubicaciones)) : ?>
                                        <p class="atractivo-ubicacion">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo esc_html($ubicaciones[0]->name); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <!-- Descripción -->
                                <div class="atractivo-descripcion">
                                    <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                                </div>

                                <!-- Características Destacadas -->
                                <?php if (!empty($caracteristicas_array)) : ?>
                                    <div class="atractivo-caracteristicas">
                                        <?php
                                        $caracteristicas_icons = array(
                                            'estacionamiento' => 'fa-parking',
                                            'guias_turisticos' => 'fa-user-tie',
                                            'accesible' => 'fa-wheelchair',
                                            'restaurante' => 'fa-utensils',
                                            'tienda_souvenirs' => 'fa-shopping-bag',
                                            'banos' => 'fa-restroom',
                                            'area_picnic' => 'fa-tree',
                                            'wifi' => 'fa-wifi',
                                        );

                                        $count = 0;
                                        foreach ($caracteristicas_array as $caracteristica) {
                                            if ($count >= 4) break;
                                            $icon = isset($caracteristicas_icons[$caracteristica]) ? $caracteristicas_icons[$caracteristica] : 'fa-check';
                                            echo '<span class="caracteristica-icon" title="' . esc_attr(ucfirst(str_replace('_', ' ', $caracteristica))) . '">';
                                            echo '<i class="fas ' . esc_attr($icon) . '"></i>';
                                            echo '</span>';
                                            $count++;
                                        }
                                        if (count($caracteristicas_array) > 4) {
                                            echo '<span class="caracteristicas-mas">+' . (count($caracteristicas_array) - 4) . '</span>';
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Footer con Precio y Botones -->
                                <div class="atractivo-card-footer">
                                    <div class="atractivo-precio">
                                        <?php if ($precio_entrada) : ?>
                                            <span class="precio-label">Entrada:</span>
                                            <span class="precio-valor"><?php echo esc_html($precio_entrada); ?></span>
                                        <?php else : ?>
                                            <span class="precio-consultar">Consultar precio</span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="atractivo-acciones">
                                        <a href="<?php the_permalink(); ?>" class="btn-ver-atractivo">
                                            Ver detalles
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>

                            </div><!-- .atractivo-card-contenido -->

                        </article>

                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <div class="no-atractivos-mensaje">
                        <i class="fas fa-map-marked-alt"></i>
                        <h3>No hay atractivos disponibles</h3>
                        <p>Agrega nuevos atractivos turísticos desde el panel de administración.</p>
                    </div>
                <?php endif; ?>
            </div><!-- .atractivos-grid -->

            <!-- Mensaje sin resultados -->
            <div class="no-resultados-mensaje" id="no-resultados" style="display: none;">
                <i class="fas fa-search"></i>
                <h3>No se encontraron atractivos</h3>
                <p>Intenta ajustar los filtros para ver más resultados.</p>
            </div>

        </div><!-- .atractivos-container -->
    </section>

</main><!-- .site-main -->

<!-- Modal de Galería -->
<div class="atractivo-galeria-modal" id="galeria-modal">
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
                <img id="galeria-imagen-actual" src="" alt="Imagen del atractivo">
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
    const atractivosCards = document.querySelectorAll('.atractivo-card');
    const searchInput = document.getElementById('atractivos-search');
    const filtroUbicacion = document.getElementById('filtro-ubicacion');
    const filtroTipo = document.getElementById('filtro-tipo');
    const limpiarBtn = document.getElementById('limpiar-filtros');
    const atractivosCount = document.getElementById('atractivos-count');
    const noResultados = document.getElementById('no-resultados');

    function filtrarAtractivos() {
        const searchTerm = searchInput.value.toLowerCase();
        const ubicacionValue = filtroUbicacion.value;
        const tipoValue = filtroTipo.value;

        let visibleCount = 0;

        atractivosCards.forEach(card => {
            const nombre = card.getAttribute('data-nombre');
            const ubicacion = card.getAttribute('data-ubicacion');
            const tipo = card.getAttribute('data-tipo');

            let mostrar = true;

            // Filtro de búsqueda
            if (searchTerm && !nombre.includes(searchTerm)) {
                mostrar = false;
            }

            // Filtro de ubicación
            if (ubicacionValue && ubicacion !== ubicacionValue) {
                mostrar = false;
            }

            // Filtro de tipo
            if (tipoValue && tipo !== tipoValue) {
                mostrar = false;
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
        atractivosCount.textContent = visibleCount + (visibleCount === 1 ? ' atractivo encontrado' : ' atractivos encontrados');

        // Mostrar mensaje si no hay resultados
        if (visibleCount === 0) {
            noResultados.style.display = 'flex';
        } else {
            noResultados.style.display = 'none';
        }
    }

    // Event listeners para filtros
    searchInput.addEventListener('input', filtrarAtractivos);
    filtroUbicacion.addEventListener('change', filtrarAtractivos);
    filtroTipo.addEventListener('change', filtrarAtractivos);

    // Limpiar filtros
    limpiarBtn.addEventListener('click', function() {
        searchInput.value = '';
        filtroUbicacion.value = '';
        filtroTipo.value = '';
        filtrarAtractivos();
    });

    // Inicializar contador
    filtrarAtractivos();

    // Galería de imágenes
    const galeriaButtons = document.querySelectorAll('.atractivo-galeria-btn');
    galeriaButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const atractivoId = this.getAttribute('data-atractivo-id');
            openGaleriaModal(atractivoId);
        });
    });
});

// Funciones de galería
function openGaleriaModal(atractivoId) {
    const atractivoCard = document.querySelector(`.atractivo-card[data-atractivo-id="${atractivoId}"]`);
    const galeriaData = <?php
    // Crear array de galerías para JavaScript
    $galeria_data = array();
    $atractivos_query->rewind_posts();
    while ($atractivos_query->have_posts()) : $atractivos_query->the_post();
        $galeria = get_post_meta(get_the_ID(), '_atractivo_galeria', true);
        $galeria_urls = $galeria ? array_map('trim', explode(',', $galeria)) : array();
        if (!empty($galeria_urls)) {
            $galeria_data[get_the_ID()] = $galeria_urls;
        }
    endwhile;
    wp_reset_postdata();
    echo json_encode($galeria_data);
    ?>;

    if (galeriaData[atractivoId]) {
        galeriaActual = galeriaData[atractivoId];
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
