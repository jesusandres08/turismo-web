<?php
/**
 * Single Post Template
 * Template para mostrar una entrada individual con sidebar
 */

get_header();
?>

<main class="site-main">
    <div class="container">
        <div class="content-area-with-sidebar">

            <!-- Contenido Principal -->
            <div class="primary-content">
                <?php
                while ( have_posts() ) :
                    the_post();
                ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class('single-post-article'); ?>>
                    <div class="post-wrapper">

                        <div class="post-content">
                            <!-- Header del Post -->
                            <header class="entry-header">
                                <h1 class="entry-title"><?php the_title(); ?></h1>

                                <div class="entry-meta">
                                    <span class="posted-on">
                                        <i class="far fa-calendar"></i>
                                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                            <?php echo esc_html( get_the_date( 'd M, Y' ) ); ?>
                                        </time>
                                    </span>
                                    <span class="byline">
                                        <i class="far fa-user"></i>
                                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                                            <?php the_author(); ?>
                                        </a>
                                    </span>
                                    <?php if ( get_comments_number() > 0 ) : ?>
                                    <span class="comments-count">
                                        <i class="far fa-comments"></i>
                                        <?php comments_number( '0 comentarios', '1 comentario', '% comentarios' ); ?>
                                    </span>
                                    <?php endif; ?>
                                </div><!-- .entry-meta -->
                            </header><!-- .entry-header -->

                            <?php
                            // Imagen destacada (más compacta)
                            if ( has_post_thumbnail() ) :
                            ?>
                                <div class="post-thumbnail-single">
                                    <?php the_post_thumbnail( 'large', array( 'class' => 'single-featured-img' ) ); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Contenido del Post -->
                            <div class="entry-content">
                                <?php
                                the_content();

                                wp_link_pages( array(
                                    'before'      => '<div class="page-links">' . __( 'Páginas:', 'turismo-custom' ),
                                    'after'       => '</div>',
                                    'link_before' => '<span class="page-number">',
                                    'link_after'  => '</span>',
                                ));
                                ?>
                            </div><!-- .entry-content -->

                            <!-- Botones de Compartir en Redes Sociales -->
                            <div class="social-share-buttons">
                                <span class="share-label"><i class="fas fa-share-alt"></i> Compartir:</span>

                                <?php
                                $post_url = urlencode( get_permalink() );
                                $post_title = urlencode( get_the_title() );
                                ?>

                                <!-- Facebook -->
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="share-btn share-facebook"
                                   aria-label="Compartir en Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                    <span>Facebook</span>
                                </a>

                                <!-- WhatsApp -->
                                <a href="https://api.whatsapp.com/send?text=<?php echo $post_title; ?>%20<?php echo $post_url; ?>"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="share-btn share-whatsapp"
                                   aria-label="Compartir en WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>WhatsApp</span>
                                </a>

                                <!-- Instagram (Copiar enlace) -->
                                <button type="button"
                                        class="share-btn share-instagram"
                                        onclick="copyToClipboard('<?php echo esc_js( get_permalink() ); ?>')"
                                        aria-label="Copiar enlace para Instagram">
                                    <i class="fab fa-instagram"></i>
                                    <span>Instagram</span>
                                </button>
                            </div><!-- .social-share-buttons -->

                            <!-- Footer del Post -->
                            <footer class="entry-footer">
                                <?php
                                $categories = get_the_category();
                                if ( $categories ) :
                                ?>
                                    <div class="entry-categories">
                                        <strong><i class="fas fa-folder"></i> Categorías:</strong>
                                        <?php
                                        foreach ( $categories as $category ) {
                                            echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="category-link">';
                                            echo '<i class="fas fa-tag"></i> ';
                                            echo esc_html( $category->name );
                                            echo '</a>';
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>

                                <?php
                                $tags = get_the_tags();
                                if ( $tags ) :
                                ?>
                                    <div class="entry-tags">
                                        <strong><i class="fas fa-tags"></i> Etiquetas:</strong>
                                        <?php
                                        foreach ( $tags as $tag ) {
                                            echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="tag-link">';
                                            echo esc_html( $tag->name );
                                            echo '</a>';
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </footer><!-- .entry-footer -->

                        </div><!-- .post-content -->
                    </div><!-- .post-wrapper -->
                </article><!-- #post-<?php the_ID(); ?> -->

                <?php
                // Navegación entre posts con imágenes
                turismo_post_navigation_with_images();

                // Comentarios
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

                endwhile;
                ?>
            </div><!-- .primary-content -->

            <!-- Sidebar -->
            <?php get_sidebar(); ?>

        </div><!-- .content-area-with-sidebar -->
    </div><!-- .container -->
</main><!-- .site-main -->

<?php
get_footer();
