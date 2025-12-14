<?php
/**
 * Template Part: Destinos Turísticos para Home
 * Muestra una grid de destinos filtrados por categoría
 * Configuración desde: Apariencia → Personalizar → Configuración del Home
 */

// Obtener configuración del Customizer
$categoria_slug = get_theme_mod( 'turismo_destinos_home_categoria', '' );
$cantidad = get_theme_mod( 'turismo_destinos_home_cantidad', 6 );

// Query para obtener destinos
$destinos_args = array(
    'post_type'      => 'destino',
    'posts_per_page' => absint( $cantidad ),
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
);

// Si hay una categoría seleccionada, filtrar por ella
if ( ! empty( $categoria_slug ) ) {
    $destinos_args['tax_query'] = array(
        array(
            'taxonomy' => 'categoria-destino',
            'field'    => 'slug',
            'terms'    => $categoria_slug,
        ),
    );
}

$destinos_query = new WP_Query( $destinos_args );

if ( $destinos_query->have_posts() ) :
?>

<!-- Sección de Destinos Turísticos -->
<section class="destinos-home-section">
    <div class="destinos-home-container">

        <!-- Header de la Sección -->
        <div class="destinos-home-header">
            <div class="destinos-home-header-content">
                <h2 class="destinos-home-titulo">
                    <i class="fas fa-compass"></i>
                    Descubre Nuestros Destinos
                </h2>
                <p class="destinos-home-subtitulo">
                    Explora los lugares más increíbles y vive experiencias inolvidables
                </p>
            </div>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'destino' ) ); ?>" class="destinos-home-ver-todos">
                Ver todos los destinos
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <!-- Grid de Destinos -->
        <div class="destinos-home-grid">
            <?php while ( $destinos_query->have_posts() ) : $destinos_query->the_post();
                $ubicacion = get_post_meta( get_the_ID(), '_destino_ubicacion', true );
                $descripcion_corta = get_post_meta( get_the_ID(), '_destino_descripcion_corta', true );
                $categorias = get_the_terms( get_the_ID(), 'categoria-destino' );
            ?>

                <article class="destino-card">
                    <a href="<?php the_permalink(); ?>" class="destino-card-link">

                        <!-- Imagen del Destino -->
                        <div class="destino-card-imagen">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'large', array( 'class' => 'destino-img' ) ); ?>
                            <?php else : ?>
                                <div class="destino-placeholder">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php endif; ?>

                            <!-- Overlay con gradiente -->
                            <div class="destino-card-overlay"></div>

                            <!-- Badge de Categoría -->
                            <?php if ( $categorias && ! is_wp_error( $categorias ) ) : ?>
                                <span class="destino-categoria-badge">
                                    <?php echo esc_html( $categorias[0]->name ); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Contenido de la Card -->
                        <div class="destino-card-contenido">
                            <h3 class="destino-card-titulo">
                                <?php the_title(); ?>
                            </h3>

                            <?php if ( $ubicacion ) : ?>
                                <p class="destino-card-ubicacion">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo esc_html( $ubicacion ); ?>
                                </p>
                            <?php endif; ?>

                            <?php if ( $descripcion_corta ) : ?>
                                <p class="destino-card-descripcion">
                                    <?php echo esc_html( wp_trim_words( $descripcion_corta, 15, '...' ) ); ?>
                                </p>
                            <?php else : ?>
                                <p class="destino-card-descripcion">
                                    <?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?>
                                </p>
                            <?php endif; ?>

                            <span class="destino-card-cta">
                                Explorar destino
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>

                    </a>
                </article>

            <?php endwhile; ?>
        </div><!-- .destinos-home-grid -->

    </div><!-- .destinos-home-container -->
</section><!-- .destinos-home-section -->

<?php
wp_reset_postdata();
endif;
?>
