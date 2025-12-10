<?php
/**
 * Slider Hero - Estilo portal_choix slider2
 * Slider premium con diseño split (60% imagen / 40% info)
 */

// Obtener los últimos posts destacados para el slider
$slider_args = array(
    'post_type'      => 'post',
    'posts_per_page' => 5, // Número de slides
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
);

$slider_query = new WP_Query( $slider_args );

if ( $slider_query->have_posts() ) :
?>

<!-- Slider Section -->
<section class="slider2-section">
    <div class="slider2-wrapper">
        <div class="slider2-track">

            <?php while ( $slider_query->have_posts() ) : $slider_query->the_post(); ?>

                <div class="slider2-slide">
                    <a href="<?php the_permalink(); ?>" class="slide-link">

                        <!-- Imagen del Slide (60%) -->
                        <div class="slide-media">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'full', array( 'alt' => get_the_title() ) ); ?>
                            <?php else : ?>
                                <!-- Placeholder con gradiente si no hay imagen -->
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #003F87, #0067b8); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: 800;">
                                    <i class="fas fa-image" style="font-size: 80px; opacity: 0.3;"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Información del Slide (40%) -->
                        <div class="slide-info">

                            <!-- Tag/Categoría -->
                            <?php
                            $categories = get_the_category();
                            if ( ! empty( $categories ) ) :
                            ?>
                                <span class="slide-tag">
                                    <i class="fas fa-tag"></i>
                                    <?php echo esc_html( $categories[0]->name ); ?>
                                </span>
                            <?php endif; ?>

                            <!-- Título -->
                            <h2 class="slide-title"><?php the_title(); ?></h2>

                            <!-- Extracto -->
                            <p class="slide-excerpt">
                                <?php
                                if ( has_excerpt() ) {
                                    echo wp_trim_words( get_the_excerpt(), 20, '...' );
                                } else {
                                    echo wp_trim_words( get_the_content(), 20, '...' );
                                }
                                ?>
                            </p>

                            <!-- Meta: Fecha y Autor -->
                            <div class="slide-meta">
                                <i class="fas fa-calendar-alt"></i>
                                <?php echo get_the_date( 'd/m/Y' ); ?>
                                <span class="meta-separator">•</span>
                                <i class="fas fa-user"></i>
                                <?php the_author(); ?>
                            </div>

                        </div><!-- .slide-info -->

                    </a><!-- .slide-link -->
                </div><!-- .slider2-slide -->

            <?php endwhile; ?>

        </div><!-- .slider2-track -->

        <!-- Controles de Navegación -->
        <button class="slider2-arrow prev" aria-label="Slide anterior">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="slider2-arrow next" aria-label="Siguiente slide">
            <i class="fas fa-chevron-right"></i>
        </button>

    </div><!-- .slider2-wrapper -->

    <!-- Dots de Navegación -->
    <div class="slider2-dots" role="tablist" aria-label="Navegación del slider">
        <?php
        $slide_count = $slider_query->post_count;
        for ( $i = 0; $i < $slide_count; $i++ ) :
        ?>
            <button
                class="slider2-dot <?php echo $i === 0 ? 'active' : ''; ?>"
                data-slide="<?php echo $i; ?>"
                role="tab"
                aria-label="Ir al slide <?php echo $i + 1; ?>"
                aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
            ></button>
        <?php endfor; ?>
    </div>

</section><!-- .slider2-section -->

<?php
wp_reset_postdata();
endif;
?>
