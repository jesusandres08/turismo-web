<?php
/**
 * Template para mostrar un atractivo turístico individual
 */

get_header();

while (have_posts()) : the_post();

    // Obtener meta datos
    $precio_entrada = get_post_meta(get_the_ID(), '_atractivo_precio_entrada', true);
    $horario = get_post_meta(get_the_ID(), '_atractivo_horario', true);
    $caracteristicas = get_post_meta(get_the_ID(), '_atractivo_caracteristicas', true);
    $caracteristicas_array = $caracteristicas ? json_decode($caracteristicas, true) : array();
    $telefono = get_post_meta(get_the_ID(), '_atractivo_telefono', true);
    $sitio_web = get_post_meta(get_the_ID(), '_atractivo_sitio_web', true);
    $direccion = get_post_meta(get_the_ID(), '_atractivo_direccion', true);
    $galeria = get_post_meta(get_the_ID(), '_atractivo_galeria', true);
    $galeria_urls = $galeria ? array_map('trim', explode(',', $galeria)) : array();

    // Obtener taxonomías
    $ubicaciones = get_the_terms(get_the_ID(), 'ubicacion-atractivo');
    $tipos = get_the_terms(get_the_ID(), 'tipo-atractivo');
    ?>

    <main class="site-main single-atractivo-page">

        <!-- Hero del Atractivo -->
        <section class="single-atractivo-hero">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('full', array('class' => 'single-atractivo-hero-img')); ?>
            <?php else : ?>
                <img src="<?php echo esc_url(TURISMO_URI . '/images/placeholder.svg'); ?>"
                     alt="<?php the_title_attribute(); ?>"
                     class="single-atractivo-hero-img">
            <?php endif; ?>
            <div class="single-atractivo-hero-overlay">
                <div class="single-atractivo-hero-content">
                    <!-- Breadcrumb -->
                    <div class="atractivo-breadcrumb">
                        <a href="<?php echo home_url('/'); ?>">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                        <span class="separator">/</span>
                        <a href="<?php echo get_post_type_archive_link('atractivo'); ?>">Atractivos Turísticos</a>
                        <span class="separator">/</span>
                        <span><?php the_title(); ?></span>
                    </div>

                    <h1 class="single-atractivo-titulo"><?php the_title(); ?></h1>

                    <div class="single-atractivo-meta-hero">
                        <?php if ($ubicaciones && !is_wp_error($ubicaciones)) : ?>
                            <span class="atractivo-meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo esc_html($ubicaciones[0]->name); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ($tipos && !is_wp_error($tipos)) : ?>
                            <span class="atractivo-meta-item atractivo-tipo-badge">
                                <i class="fas fa-tag"></i>
                                <?php echo esc_html($tipos[0]->name); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ($precio_entrada) : ?>
                            <span class="atractivo-meta-item atractivo-precio-badge">
                                <i class="fas fa-ticket-alt"></i>
                                <?php echo esc_html($precio_entrada); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenido Principal -->
        <div class="single-atractivo-container">
            <div class="single-atractivo-layout">

                <!-- Columna Principal -->
                <div class="single-atractivo-main">

                    <!-- Galería de Imágenes -->
                    <?php if (!empty($galeria_urls)) : ?>
                        <section class="atractivo-galeria-section">
                            <h2 class="section-titulo">
                                <i class="fas fa-images"></i>
                                Galería de Fotos
                            </h2>
                            <div class="atractivo-galeria-grid">
                                <?php foreach ($galeria_urls as $index => $url) : ?>
                                    <div class="galeria-item" onclick="openGaleriaModal(<?php echo $index; ?>)">
                                        <img src="<?php echo esc_url($url); ?>" alt="Foto <?php echo $index + 1; ?>">
                                        <div class="galeria-item-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <!-- Descripción -->
                    <section class="atractivo-descripcion-section">
                        <h2 class="section-titulo">
                            <i class="fas fa-info-circle"></i>
                            Acerca de este atractivo
                        </h2>
                        <div class="atractivo-descripcion-content">
                            <?php the_content(); ?>
                        </div>
                    </section>

                    <!-- Características -->
                    <?php if (!empty($caracteristicas_array)) : ?>
                        <section class="atractivo-caracteristicas-section">
                            <h2 class="section-titulo">
                                <i class="fas fa-check-circle"></i>
                                Características y Servicios
                            </h2>
                            <div class="caracteristicas-grid">
                                <?php
                                $caracteristicas_data = array(
                                    'estacionamiento' => array('icon' => 'fa-parking', 'label' => 'Estacionamiento'),
                                    'guias_turisticos' => array('icon' => 'fa-user-tie', 'label' => 'Guías turísticos'),
                                    'accesible' => array('icon' => 'fa-wheelchair', 'label' => 'Accesible'),
                                    'restaurante' => array('icon' => 'fa-utensils', 'label' => 'Restaurante/Cafetería'),
                                    'tienda_souvenirs' => array('icon' => 'fa-shopping-bag', 'label' => 'Tienda de souvenirs'),
                                    'banos' => array('icon' => 'fa-restroom', 'label' => 'Baños'),
                                    'area_picnic' => array('icon' => 'fa-tree', 'label' => 'Área de picnic'),
                                    'wifi' => array('icon' => 'fa-wifi', 'label' => 'WiFi'),
                                    'pet_friendly' => array('icon' => 'fa-paw', 'label' => 'Mascotas permitidas'),
                                    'fotografia_permitida' => array('icon' => 'fa-camera', 'label' => 'Fotografía permitida'),
                                    'aire_libre' => array('icon' => 'fa-cloud-sun', 'label' => 'Aire libre'),
                                    'techado' => array('icon' => 'fa-home', 'label' => 'Techado/Interior'),
                                );

                                foreach ($caracteristicas_array as $caracteristica) :
                                    if (isset($caracteristicas_data[$caracteristica])) :
                                        $data = $caracteristicas_data[$caracteristica];
                                        ?>
                                        <div class="caracteristica-item">
                                            <i class="fas <?php echo esc_attr($data['icon']); ?>"></i>
                                            <span><?php echo esc_html($data['label']); ?></span>
                                        </div>
                                    <?php endif;
                                endforeach;
                                ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <!-- Horario -->
                    <?php if ($horario) : ?>
                        <section class="atractivo-horario-section">
                            <h2 class="section-titulo">
                                <i class="fas fa-clock"></i>
                                Horario de Atención
                            </h2>
                            <div class="atractivo-horario-content">
                                <p><?php echo nl2br(esc_html($horario)); ?></p>
                            </div>
                        </section>
                    <?php endif; ?>

                </div><!-- .single-atractivo-main -->

                <!-- Sidebar -->
                <aside class="single-atractivo-sidebar">

                    <!-- Card de Información -->
                    <div class="info-card">
                        <?php if ($precio_entrada) : ?>
                            <div class="info-precio">
                                <span class="precio-label">Precio de entrada</span>
                                <span class="precio-cantidad"><?php echo esc_html($precio_entrada); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($horario) : ?>
                            <div class="info-horario">
                                <i class="fas fa-clock"></i>
                                <div class="info-detalle">
                                    <span class="info-label">Horario</span>
                                    <p><?php echo nl2br(esc_html($horario)); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($sitio_web) : ?>
                            <a href="<?php echo esc_url($sitio_web); ?>" target="_blank" rel="noopener" class="btn-sitio-web">
                                <i class="fas fa-external-link-alt"></i>
                                Visitar Sitio Web
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Card de Contacto -->
                    <div class="contacto-card" id="contacto">
                        <h3 class="contacto-titulo">
                            <i class="fas fa-phone"></i>
                            Información de Contacto
                        </h3>

                        <?php if ($telefono) : ?>
                            <div class="contacto-item">
                                <i class="fas fa-phone-alt"></i>
                                <div class="contacto-info">
                                    <span class="contacto-label">Teléfono</span>
                                    <a href="tel:<?php echo esc_attr(str_replace(' ', '', $telefono)); ?>">
                                        <?php echo esc_html($telefono); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($direccion) : ?>
                            <div class="contacto-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div class="contacto-info">
                                    <span class="contacto-label">Dirección</span>
                                    <p><?php echo esc_html($direccion); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Botón Volver -->
                    <a href="<?php echo get_post_type_archive_link('atractivo'); ?>" class="btn-volver">
                        <i class="fas fa-arrow-left"></i>
                        Ver todos los atractivos
                    </a>

                </aside><!-- .single-atractivo-sidebar -->

            </div><!-- .single-atractivo-layout -->
        </div><!-- .single-atractivo-container -->

    </main>

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
    // Galería de imágenes
    const galeriaImagenes = <?php echo json_encode($galeria_urls); ?>;
    let galeriaIndice = 0;

    function openGaleriaModal(index) {
        galeriaIndice = index;
        mostrarGaleriaImagen();
        document.getElementById('galeria-modal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeGaleriaModal() {
        document.getElementById('galeria-modal').classList.remove('active');
        document.body.style.overflow = '';
    }

    function galeriaSlide(direction) {
        galeriaIndice += direction;
        if (galeriaIndice < 0) galeriaIndice = galeriaImagenes.length - 1;
        if (galeriaIndice >= galeriaImagenes.length) galeriaIndice = 0;
        mostrarGaleriaImagen();
    }

    function mostrarGaleriaImagen() {
        if (galeriaImagenes.length > 0) {
            document.getElementById('galeria-imagen-actual').src = galeriaImagenes[galeriaIndice];
            document.getElementById('galeria-contador-texto').textContent = `${galeriaIndice + 1} / ${galeriaImagenes.length}`;
        }
    }

    // Cerrar con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeGaleriaModal();
        if (document.getElementById('galeria-modal').classList.contains('active')) {
            if (e.key === 'ArrowLeft') galeriaSlide(-1);
            if (e.key === 'ArrowRight') galeriaSlide(1);
        }
    });

    // Smooth scroll para contacto
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
    </script>

<?php endwhile;

get_footer();
?>
