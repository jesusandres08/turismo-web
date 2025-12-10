<?php
/**
 * Template for displaying post content
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="post-wrapper">
        <?php
        // Imagen destacada
        if ( has_post_thumbnail() ) {
            ?>
            <div class="post-thumbnail">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail( 'large' ); ?>
                </a>
            </div>
            <?php
        }
        ?>
        
        <div class="post-content">
            <header class="entry-header">
                <h2 class="entry-title">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h2>
                
                <div class="entry-meta">
                    <span class="posted-on">
                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                            <?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?>
                        </time>
                    </span>
                    <span class="byline">
                        <?php _e( 'por', 'turismo-custom' ); ?>
                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                            <?php the_author(); ?>
                        </a>
                    </span>
                </div><!-- .entry-meta -->
            </header><!-- .entry-header -->
            
            <div class="entry-summary">
                <?php
                if ( is_singular() ) {
                    the_content();
                } else {
                    the_excerpt();
                    ?>
                    <a href="<?php the_permalink(); ?>" class="read-more">
                        <?php _e( 'Leer más', 'turismo-custom' ); ?>
                    </a>
                    <?php
                }
                ?>
            </div><!-- .entry-summary -->
            
            <?php
            if ( is_singular() ) {
                ?>
                <footer class="entry-footer">
                    <?php
                    $categories = get_the_category();
                    if ( $categories ) {
                        echo '<div class="entry-categories">';
                        foreach ( $categories as $category ) {
                            echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="category-link">';
                            echo esc_html( $category->name );
                            echo '</a> ';
                        }
                        echo '</div>';
                    }
                    
                    $tags = get_the_tags();
                    if ( $tags ) {
                        echo '<div class="entry-tags">';
                        foreach ( $tags as $tag ) {
                            echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="tag-link">';
                            echo esc_html( $tag->name );
                            echo '</a> ';
                        }
                        echo '</div>';
                    }
                    ?>
                </footer><!-- .entry-footer -->
                <?php
            }
            ?>
        </div><!-- .post-content -->
    </div><!-- .post-wrapper -->
</article><!-- #post-<?php the_ID(); ?> -->