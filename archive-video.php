<?php
/**
 * Archive Template: Galería de Videos
 * Muestra todos los videos con reproductor y playlist
 */

get_header();

// Query para obtener todas las categorías de videos
$categorias = get_terms( array(
    'taxonomy'   => 'categoria-video',
    'hide_empty' => true,
) );

// Query para obtener todos los videos
$video_args = array(
    'post_type'      => 'video',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
);

// Si hay filtro de categoría
if ( isset( $_GET['cat'] ) && ! empty( $_GET['cat'] ) ) {
    $video_args['tax_query'] = array(
        array(
            'taxonomy' => 'categoria-video',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( $_GET['cat'] ),
        ),
    );
}

$videos_query = new WP_Query( $video_args );
?>

<main class="site-main">

    <!-- Categorías de Videos -->
    <?php if ( ! empty( $categorias ) ) : ?>
    <section class="categorias-card">
        <h2 class="categorias-titulo">Categorías de Videos</h2>
        <div class="categorias-grid">
            <a href="<?php echo get_post_type_archive_link( 'video' ); ?>"
               class="categoria-btn <?php echo ! isset( $_GET['cat'] ) ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i>
                Todos
            </a>
            <?php foreach ( $categorias as $categoria ) : ?>
                <a href="<?php echo add_query_arg( 'cat', $categoria->slug, get_post_type_archive_link( 'video' ) ); ?>"
                   class="categoria-btn <?php echo isset( $_GET['cat'] ) && $_GET['cat'] === $categoria->slug ? 'active' : ''; ?>">
                    <i class="fas fa-folder"></i>
                    <?php echo esc_html( $categoria->name ); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Reproductor de Video con Playlist -->
    <?php if ( $videos_query->have_posts() ) : ?>

    <section class="video-player-card">
        <div class="player-container">

            <!-- Reproductor Principal -->
            <div class="player-main">
                <?php
                // Primer video como predeterminado
                $videos_query->the_post();
                $first_video_url = get_post_meta( get_the_ID(), '_youtube_url', true );
                $first_video_id = turismo_get_youtube_id( $first_video_url );
                ?>

                <div class="player-video-wrapper">
                    <iframe
                        id="main-video-player"
                        src="https://www.youtube.com/embed/<?php echo esc_attr( $first_video_id ); ?>?rel=0"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        title="<?php the_title(); ?>"
                    ></iframe>
                </div>

                <!-- Información del Video Actual -->
                <div class="player-info">
                    <div class="player-header">
                        <h1 class="player-title" id="current-video-title"><?php the_title(); ?></h1>

                        <?php
                        $terms = get_the_terms( get_the_ID(), 'categoria-video' );
                        if ( $terms && ! is_wp_error( $terms ) ) :
                        ?>
                            <span class="player-category">
                                <i class="fas fa-tag"></i>
                                <?php echo esc_html( $terms[0]->name ); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="player-meta">
                        <span>
                            <i class="fas fa-calendar-alt"></i>
                            <?php echo get_the_date( 'd/m/Y' ); ?>
                        </span>
                        <span>
                            <i class="fas fa-user"></i>
                            <?php the_author(); ?>
                        </span>
                    </div>

                    <div class="player-description" id="current-video-description">
                        <?php the_excerpt(); ?>
                    </div>

                    <div class="player-actions">
                        <button class="modal-btn" onclick="openVideoModal('<?php echo esc_js( $first_video_id ); ?>')">
                            <i class="fas fa-expand"></i>
                            Ver en pantalla completa
                        </button>
                        <a href="<?php the_permalink(); ?>" class="modal-btn">
                            <i class="fas fa-info-circle"></i>
                            Ver detalles
                        </a>
                    </div>
                </div>

            </div><!-- .player-main -->

            <!-- Playlist Sidebar -->
            <div class="player-playlist">
                <div class="playlist-header">
                    <h3>
                        <i class="fas fa-list"></i>
                        Lista de reproducción
                    </h3>
                    <span class="playlist-count"><?php echo $videos_query->found_posts; ?> videos</span>
                </div>

                <div class="playlist-videos">
                    <?php
                    // Reiniciar query para mostrar todos los videos
                    $videos_query->rewind_posts();
                    $index = 1;

                    while ( $videos_query->have_posts() ) : $videos_query->the_post();
                        $video_url = get_post_meta( get_the_ID(), '_youtube_url', true );
                        $video_id = turismo_get_youtube_id( $video_url );
                        $thumbnail = has_post_thumbnail()
                            ? get_the_post_thumbnail_url( get_the_ID(), 'medium' )
                            : 'https://img.youtube.com/vi/' . $video_id . '/mqdefault.jpg';
                        $cat_terms = get_the_terms( get_the_ID(), 'categoria-video' );
                    ?>
                        <div class="playlist-item <?php echo $index === 1 ? 'active' : ''; ?>"
                             data-video-id="<?php echo esc_attr( $video_id ); ?>"
                             data-video-title="<?php echo esc_attr( get_the_title() ); ?>"
                             data-video-excerpt="<?php echo esc_attr( get_the_excerpt() ); ?>"
                             data-video-category="<?php echo $cat_terms && ! is_wp_error( $cat_terms ) ? esc_attr( $cat_terms[0]->name ) : ''; ?>"
                             data-video-date="<?php echo get_the_date( 'd/m/Y' ); ?>"
                             data-video-author="<?php the_author(); ?>"
                             data-video-url="<?php the_permalink(); ?>">

                            <span class="playlist-item-index"><?php echo $index; ?></span>

                            <div class="playlist-item-thumbnail">
                                <img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php the_title(); ?>">
                                <div class="playlist-item-play">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>

                            <div class="playlist-item-info">
                                <h4 class="playlist-item-title"><?php the_title(); ?></h4>
                                <p class="playlist-item-meta">
                                    <?php if ( $cat_terms && ! is_wp_error( $cat_terms ) ) : ?>
                                        <span class="playlist-item-category"><?php echo esc_html( $cat_terms[0]->name ); ?></span>
                                    <?php endif; ?>
                                </p>
                            </div>

                        </div>
                    <?php
                        $index++;
                    endwhile;
                    ?>
                </div><!-- .playlist-videos -->

            </div><!-- .player-playlist -->

        </div><!-- .player-container -->
    </section>

    <?php else : ?>
        <div class="container">
            <div class="no-videos" style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-video" style="font-size: 80px; color: #ddd; margin-bottom: 20px;"></i>
                <h2>No hay videos disponibles</h2>
                <p style="color: #666;">Aún no se han publicado videos en esta galería.</p>
            </div>
        </div>
    <?php endif; ?>

</main><!-- .site-main -->

<!-- Modal para pantalla completa -->
<div class="modal-overlay" id="video-modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeVideoModal()" aria-label="Cerrar modal">
            <i class="fas fa-times"></i>
        </button>

        <div class="modal-video">
            <iframe
                id="modal-video-player"
                src=""
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
            ></iframe>
        </div>
    </div>
</div>

<?php
wp_reset_postdata();
get_footer();
?>
