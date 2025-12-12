<?php
/**
 * Noticias/Posts para Home
 * Grid de posts recientes estilo card
 */

// Obtener posts recientes (excluyendo los del slider)
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$noticias_args = array(
    'post_type'      => 'post',
    'posts_per_page' => 5, // Mostrar 5 posts por página
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post__not_in'   => array(),
    'paged'          => $paged,
);

$noticias_query = new WP_Query( $noticias_args );
?>

<?php if ( $noticias_query->have_posts() ) : ?>

<!-- Sección Noticias/Posts -->
<section class="noticias-home-section">
    <div class="noticias-wrapper">
        <!-- Titular de la sección -->
        <div class="section-header">
            <h2 class="section-title">Últimas Noticias</h2>
            <div class="section-line"></div>
        </div>

        <!-- Noticias + Sidebar en dos columnas -->
        <div class="noticias-sidebar-layout">
            <div class="noticias-col-principal">
                <div class="noticias-lista-vertical" id="noticias-lista" data-max-pages="<?php echo $noticias_query->max_num_pages; ?>" data-current-page="<?php echo $paged; ?>">
                    <?php while ( $noticias_query->have_posts() ) : $noticias_query->the_post(); ?>
                        <article class="noticia-card noticia-card-vertical">
                            <a href="<?php the_permalink(); ?>" class="noticia-link">
                                <div class="noticia-imagen noticia-imagen-vertical">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'noticia-img noticia-img-vertical' ) ); ?>
                                    <?php else : ?>
                                        <img src="<?php echo esc_url( TURISMO_URI . '/images/placeholder.svg' ); ?>"
                                             alt="<?php the_title_attribute(); ?>"
                                             class="noticia-img noticia-img-vertical">
                                    <?php endif; ?>
                                    <?php $categories = get_the_category(); if ( ! empty( $categories ) ) : $category = $categories[0]; ?>
                                        <span class="noticia-categoria">
                                            <?php echo esc_html( $category->name ); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="noticia-contenido noticia-contenido-vertical">
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
                                    <h3 class="noticia-titulo">
                                        <?php the_title(); ?>
                                    </h3>
                                    <p class="noticia-extracto">
                                        <?php echo wp_trim_words( get_the_excerpt(), 12, '...' ); ?>
                                        <span class="noticia-leer-mas">Leer más <i class="fas fa-arrow-right"></i></span>
                                    </p>
                                </div>
                            </a>
                        </article>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
                <?php if ( $noticias_query->max_num_pages > 1 ) : ?>
                <div class="noticias-paginacion" id="noticias-paginacion">
                    <?php $current_paged = $noticias_query->get('paged'); ?>
                    <button class="noticias-pagina-btn noticias-prev<?php if ($current_paged <= 1) echo ' disabled'; ?>"
                        <?php if ($current_paged <= 1): ?>disabled<?php endif; ?>>
                        &laquo; ANTERIOR
                    </button>
                    <button class="noticias-pagina-btn noticias-next<?php if ($current_paged >= $noticias_query->max_num_pages) echo ' disabled'; ?>"
                        <?php if ($current_paged >= $noticias_query->max_num_pages): ?>disabled<?php endif; ?>>
                        SIGUIENTE &raquo;
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <aside class="noticias-sidebar">
                <?php
                if ( is_active_sidebar( 'noticias-sidebar' ) ) {
                    dynamic_sidebar( 'noticias-sidebar' );
                } else {
                ?>
                <div class="sidebar-widget">
                    <h4>Sidebar</h4>
                    <p>Contenido del sidebar aquí.</p>
                </div>
                <?php } ?>
            </aside>
        </div>

    </div>
</section>

<?php endif; ?>
