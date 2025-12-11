<?php
/**
 * Index Template
 * Template principal fallback
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div class="site-wrapper">
        <?php get_header(); ?>

        <?php if ( is_home() && ! is_paged() ) : ?>

            <!-- HOME SIMPLIFICADO -->

            <!-- 1. Slider Superior -->
            <?php get_template_part( 'template-parts/slider-hero' ); ?>

            <!-- 2. Noticias -->
            <?php get_template_part( 'template-parts/noticias-home' ); ?>

            <!-- 3. Video Player Card -->
            <?php get_template_part( 'template-parts/videos-home' ); ?>

        <?php else : ?>

            <!-- Para páginas de archivo y otras vistas -->
            <main class="site-main">
                <div class="container">
                    <div class="content-area">
                        <?php
                        if ( have_posts() ) {
                            if ( is_home() || is_archive() ) {
                                echo '<h1 class="page-title">';
                                if ( is_archive() ) {
                                    the_archive_title();
                                } else {
                                    bloginfo( 'name' );
                                }
                                echo '</h1>';
                            }

                            while ( have_posts() ) {
                                the_post();
                                get_template_part( 'template-parts/content', get_post_type() );
                            }

                            the_posts_pagination( array(
                                'prev_text' => __( 'Anterior', 'turismo-custom' ),
                                'next_text' => __( 'Siguiente', 'turismo-custom' ),
                            ));
                        } else {
                            echo '<p>' . __( 'No hay contenido disponible', 'turismo-custom' ) . '</p>';
                        }
                        ?>
                    </div><!-- .content-area -->

                    <?php get_sidebar(); ?>
                </div><!-- .container -->
            </main><!-- .site-main -->

        <?php endif; ?>

        <?php get_footer(); ?>
    </div><!-- .site-wrapper -->

    <?php wp_footer(); ?>
</body>
</html>
