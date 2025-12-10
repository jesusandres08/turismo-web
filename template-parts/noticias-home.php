<?php
/**
 * Noticias/Posts para Home
 * Grid de posts recientes estilo card
 */

// Obtener posts recientes (excluyendo los del slider)
$noticias_args = array(
    'post_type'      => 'post',
    'posts_per_page' => 6, // Mostrar 6 posts en grid 3x2
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post__not_in'   => array(), // Puedes excluir los posts del slider si quieres
);

$noticias_query = new WP_Query( $noticias_args );
?>

<?php if ( $noticias_query->have_posts() ) : ?>

<!-- Sección Noticias/Posts -->
<section class="noticias-home-section">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">

        <!-- Header de Sección -->
        <div class="noticias-header">
            <div>
                <span class="noticias-badge">
                    <i class="fas fa-newspaper"></i>
                    Noticias
                </span>
                <h2 class="noticias-title">Últimas Publicaciones</h2>
                <p class="noticias-description">Mantente informado sobre turismo, eventos y novedades</p>
            </div>
            <a href="<?php echo esc_url( home_url( '/noticias' ) ); ?>" class="noticias-link">
                Ver todas
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <!-- Grid de Noticias -->
        <div class="noticias-grid">
            <?php while ( $noticias_query->have_posts() ) : $noticias_query->the_post(); ?>

                <article class="noticia-card">
                    <a href="<?php the_permalink(); ?>" class="noticia-link">

                        <!-- Imagen -->
                        <div class="noticia-imagen">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'medium_large', array( 'class' => 'noticia-img' ) ); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url( TURISMO_URI . '/images/placeholder.jpg' ); ?>"
                                     alt="<?php the_title_attribute(); ?>"
                                     class="noticia-img">
                            <?php endif; ?>

                            <!-- Categoría Badge -->
                            <?php
                            $categories = get_the_category();
                            if ( ! empty( $categories ) ) :
                                $category = $categories[0];
                            ?>
                                <span class="noticia-categoria">
                                    <?php echo esc_html( $category->name ); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Contenido -->
                        <div class="noticia-contenido">
                            <!-- Meta -->
                            <div class="noticia-meta">
                                <span class="noticia-fecha">
                                    <i class="far fa-calendar"></i>
                                    <?php echo get_the_date( 'j M, Y' ); ?>
                                </span>
                                <span class="noticia-autor">
                                    <i class="far fa-user"></i>
                                    <?php echo get_the_author(); ?>
                                </span>
                            </div>

                            <!-- Título -->
                            <h3 class="noticia-titulo">
                                <?php the_title(); ?>
                            </h3>

                            <!-- Extracto -->
                            <p class="noticia-extracto">
                                <?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?>
                            </p>

                            <!-- Leer más -->
                            <span class="noticia-leer-mas">
                                Leer más <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>

                    </a>
                </article>

            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>

    </div>
</section>

<?php endif; ?>
