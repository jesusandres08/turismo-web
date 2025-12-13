<?php
/**
 * Slider Hero - Destinos Turísticos
 * Slider con diseño split (60% imagen / 40% info)
 */

// Obtener destinos turísticos destacados
$slider_args = array(
    'post_type'      => 'destino',
    'posts_per_page' => 5,
    'post_status'    => 'publish',
    'meta_query'     => array(
        array(
            'key'     => '_destino_destacado',
            'value'   => '1',
            'compare' => '='
        )
    ),
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$slider_query = new WP_Query( $slider_args );

// Si no hay destinos destacados, mostrar todos los destinos
if ( ! $slider_query->have_posts() ) {
    $slider_args = array(
        'post_type'      => 'destino',
        'posts_per_page' => 5,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    $slider_query = new WP_Query( $slider_args );
}

// Si aún no hay destinos, mostrar posts normales temporalmente
if ( ! $slider_query->have_posts() ) {
    $slider_args = array(
        'post_type'      => 'post',
        'posts_per_page' => 5,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    $slider_query = new WP_Query( $slider_args );
}

if ( $slider_query->have_posts() ) :
?>

<!-- Slider Section -->
<section class="slider-destinos-section">
    <div class="slider-destinos-wrapper">
        <div class="slider-destinos-track">

            <?php while ( $slider_query->have_posts() ) : $slider_query->the_post();
                $ubicacion = get_post_meta( get_the_ID(), '_destino_ubicacion', true );
                $descripcion_corta = get_post_meta( get_the_ID(), '_destino_descripcion_corta', true );
                $cta_text = get_post_meta( get_the_ID(), '_destino_cta_text', true );
                $cta_url = get_post_meta( get_the_ID(), '_destino_cta_url', true );
                $categorias = get_the_terms( get_the_ID(), 'categoria-destino' );

                // Defaults
                if ( empty( $cta_text ) ) $cta_text = 'Explorar Destino';
                if ( empty( $cta_url ) ) $cta_url = get_permalink();
                if ( empty( $descripcion_corta ) ) $descripcion_corta = wp_trim_words( get_the_excerpt(), 20, '...' );
            ?>

                <div class="slider-destinos-slide">
                    <a href="<?php echo esc_url( $cta_url ); ?>" class="slide-link">

                        <!-- Imagen del Slide (60%) -->
                        <div class="slide-media">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'large', array( 'alt' => get_the_title() ) ); ?>
                            <?php else : ?>
                                <!-- Placeholder con gradiente si no hay imagen -->
                                <div class="slide-placeholder">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Información del Slide (40%) -->
                        <div class="slide-info">

                            <!-- Tag/Categoría -->
                            <?php if ( $categorias && ! is_wp_error( $categorias ) ) : ?>
                                <span class="slide-tag">
                                    <i class="fas fa-map-marked-alt"></i>
                                    <?php echo esc_html( $categorias[0]->name ); ?>
                                </span>
                            <?php endif; ?>

                            <!-- Título -->
                            <h2 class="slide-title"><?php the_title(); ?></h2>

                            <!-- Ubicación -->
                            <?php if ( $ubicacion ) : ?>
                                <div class="slide-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo esc_html( $ubicacion ); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Descripción -->
                            <p class="slide-excerpt">
                                <?php echo esc_html( $descripcion_corta ); ?>
                            </p>

                            <!-- Botón de Acción -->
                            <span class="slide-btn">
                                <?php echo esc_html( $cta_text ); ?>
                                <i class="fas fa-arrow-right"></i>
                            </span>

                        </div><!-- .slide-info -->

                    </a><!-- .slide-link -->
                </div><!-- .slider-destinos-slide -->

            <?php endwhile; ?>

        </div><!-- .slider-destinos-track -->

        <!-- Controles de Navegación -->
        <button class="slider-destinos-arrow prev" aria-label="Slide anterior">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="slider-destinos-arrow next" aria-label="Siguiente slide">
            <i class="fas fa-chevron-right"></i>
        </button>

    </div><!-- .slider-destinos-wrapper -->

    <!-- Dots de Navegación -->
    <div class="slider-destinos-dots" role="tablist" aria-label="Navegación del slider">
        <?php
        $slide_count = $slider_query->post_count;
        for ( $i = 0; $i < $slide_count; $i++ ) :
        ?>
            <button
                class="slider-destinos-dot <?php echo $i === 0 ? 'active' : ''; ?>"
                data-slide="<?php echo $i; ?>"
                role="tab"
                aria-label="Ir al slide <?php echo $i + 1; ?>"
                aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
            ></button>
        <?php endfor; ?>
    </div>

</section><!-- .slider-destinos-section -->

<?php
wp_reset_postdata();
endif;
?>
