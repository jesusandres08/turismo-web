<?php
/**
 * Template para mostrar un hotel individual
 */

get_header();

while (have_posts()) : the_post();

    // Obtener meta datos
    $precio = get_post_meta(get_the_ID(), '_hotel_precio', true);
    $estrellas = get_post_meta(get_the_ID(), '_hotel_estrellas', true);
    $servicios = get_post_meta(get_the_ID(), '_hotel_servicios', true);
    $servicios_array = $servicios ? json_decode($servicios, true) : array();
    $telefono = get_post_meta(get_the_ID(), '_hotel_telefono', true);
    $email = get_post_meta(get_the_ID(), '_hotel_email', true);
    $sitio_web = get_post_meta(get_the_ID(), '_hotel_sitio_web', true);
    $direccion = get_post_meta(get_the_ID(), '_hotel_direccion', true);
    $galeria = get_post_meta(get_the_ID(), '_hotel_galeria', true);
    $galeria_urls = $galeria ? array_map('trim', explode(',', $galeria)) : array();

    // Obtener taxonomías
    $ubicaciones = get_the_terms(get_the_ID(), 'ubicacion-hotel');
    $categorias = get_the_terms(get_the_ID(), 'categoria-hotel');
    ?>

    <main class="site-main single-hotel-page">

        <!-- Hero del Hotel -->
        <section class="single-hotel-hero">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('full', array('class' => 'single-hotel-hero-img')); ?>
            <?php else : ?>
                <img src="<?php echo esc_url(TURISMO_URI . '/images/placeholder.svg'); ?>"
                     alt="<?php the_title_attribute(); ?>"
                     class="single-hotel-hero-img">
            <?php endif; ?>
            <div class="single-hotel-hero-overlay">
                <div class="single-hotel-hero-content">
                    <!-- Breadcrumb -->
                    <div class="hotel-breadcrumb">
                        <a href="<?php echo home_url('/'); ?>">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                        <span class="separator">/</span>
                        <a href="<?php echo get_permalink(get_page_by_path('hoteles')); ?>">Hoteles</a>
                        <span class="separator">/</span>
                        <span><?php the_title(); ?></span>
                    </div>

                    <h1 class="single-hotel-titulo"><?php the_title(); ?></h1>

                    <div class="single-hotel-meta-hero">
                        <?php if ($ubicaciones && !is_wp_error($ubicaciones)) : ?>
                            <span class="hotel-meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo esc_html($ubicaciones[0]->name); ?>
                            </span>
                        <?php endif; ?>

                        <?php if ($estrellas) : ?>
                            <span class="hotel-meta-item hotel-stars">
                                <?php for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $estrellas ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                } ?>
                            </span>
                        <?php endif; ?>

                        <?php if ($categorias && !is_wp_error($categorias)) : ?>
                            <span class="hotel-meta-item hotel-categoria-badge">
                                <i class="fas fa-tag"></i>
                                <?php echo esc_html($categorias[0]->name); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenido Principal -->
        <div class="single-hotel-container">
            <div class="single-hotel-layout">

                <!-- Columna Principal -->
                <div class="single-hotel-main">

                    <!-- Galería de Imágenes -->
                    <?php if (!empty($galeria_urls)) : ?>
                        <section class="hotel-galeria-section">
                            <h2 class="section-titulo">
                                <i class="fas fa-images"></i>
                                Galería de Fotos
                            </h2>
                            <div class="hotel-galeria-grid">
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
                    <section class="hotel-descripcion-section">
                        <h2 class="section-titulo">
                            <i class="fas fa-info-circle"></i>
                            Acerca de este hotel
                        </h2>
                        <div class="hotel-descripcion-content">
                            <?php the_content(); ?>
                        </div>
                    </section>

                    <!-- Servicios e Instalaciones -->
                    <?php if (!empty($servicios_array)) : ?>
                        <section class="hotel-servicios-section">
                            <h2 class="section-titulo">
                                <i class="fas fa-concierge-bell"></i>
                                Servicios e Instalaciones
                            </h2>
                            <div class="servicios-grid">
                                <?php
                                $servicios_data = array(
                                    'wifi' => array('icon' => 'fa-wifi', 'label' => 'WiFi Gratis'),
                                    'estacionamiento' => array('icon' => 'fa-parking', 'label' => 'Estacionamiento'),
                                    'piscina' => array('icon' => 'fa-swimming-pool', 'label' => 'Piscina'),
                                    'restaurante' => array('icon' => 'fa-utensils', 'label' => 'Restaurante'),
                                    'gimnasio' => array('icon' => 'fa-dumbbell', 'label' => 'Gimnasio'),
                                    'spa' => array('icon' => 'fa-spa', 'label' => 'Spa'),
                                    'aire_acondicionado' => array('icon' => 'fa-snowflake', 'label' => 'Aire Acondicionado'),
                                    'bar' => array('icon' => 'fa-cocktail', 'label' => 'Bar'),
                                    'recepcion_24h' => array('icon' => 'fa-clock', 'label' => 'Recepción 24h'),
                                    'desayuno' => array('icon' => 'fa-coffee', 'label' => 'Desayuno incluido'),
                                    'pet_friendly' => array('icon' => 'fa-paw', 'label' => 'Mascotas permitidas'),
                                    'servicio_habitacion' => array('icon' => 'fa-bell', 'label' => 'Servicio a la habitación'),
                                );

                                foreach ($servicios_array as $servicio) :
                                    if (isset($servicios_data[$servicio])) :
                                        $data = $servicios_data[$servicio];
                                        ?>
                                        <div class="servicio-item">
                                            <i class="fas <?php echo esc_attr($data['icon']); ?>"></i>
                                            <span><?php echo esc_html($data['label']); ?></span>
                                        </div>
                                    <?php endif;
                                endforeach;
                                ?>
                            </div>
                        </section>
                    <?php endif; ?>

                </div><!-- .single-hotel-main -->

                <!-- Sidebar -->
                <aside class="single-hotel-sidebar">

                    <!-- Card de Reserva -->
                    <div class="reserva-card">
                        <?php if ($precio) : ?>
                            <div class="reserva-precio">
                                <span class="precio-desde">Desde</span>
                                <span class="precio-cantidad"><?php echo esc_html($precio); ?></span>
                                <span class="precio-periodo">/ noche</span>
                            </div>
                        <?php endif; ?>

                        <a href="#contacto" class="btn-reservar">
                            <i class="fas fa-calendar-check"></i>
                            Reservar Ahora
                        </a>

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

                        <?php if ($email) : ?>
                            <div class="contacto-item">
                                <i class="fas fa-envelope"></i>
                                <div class="contacto-info">
                                    <span class="contacto-label">Email</span>
                                    <a href="mailto:<?php echo esc_attr($email); ?>">
                                        <?php echo esc_html($email); ?>
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
                    <a href="<?php echo get_permalink(get_page_by_path('hoteles')); ?>" class="btn-volver">
                        <i class="fas fa-arrow-left"></i>
                        Ver todos los hoteles
                    </a>

                </aside><!-- .single-hotel-sidebar -->

            </div><!-- .single-hotel-layout -->
        </div><!-- .single-hotel-container -->

    </main>

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
