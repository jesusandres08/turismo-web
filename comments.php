<?php
/**
 * Plantilla de Comentarios
 * Moderna y elegante
 */

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <i class="fas fa-comments"></i>
            <?php
            $comment_count = get_comments_number();
            if ( '1' === $comment_count ) {
                printf( esc_html__( 'Un comentario', 'turismo-custom' ) );
            } else {
                printf(
                    esc_html( _n( '%s comentario', '%s comentarios', $comment_count, 'turismo-custom' ) ),
                    number_format_i18n( $comment_count )
                );
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 50,
                'callback'    => 'turismo_custom_comment',
            ));
            ?>
        </ol>

        <?php
        the_comments_navigation( array(
            'prev_text' => '<i class="fas fa-arrow-left"></i> ' . __( 'Comentarios anteriores', 'turismo-custom' ),
            'next_text' => __( 'Comentarios siguientes', 'turismo-custom' ) . ' <i class="fas fa-arrow-right"></i>',
        ));
        ?>

    <?php endif; ?>

    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="no-comments">
            <i class="fas fa-lock"></i>
            <?php esc_html_e( 'Los comentarios están cerrados.', 'turismo-custom' ); ?>
        </p>
    <?php endif; ?>

    <?php
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );

    comment_form( array(
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title"><i class="fas fa-comment-dots"></i> ',
        'title_reply_after'  => '</h3>',
        'title_reply'        => __( 'Deja un comentario', 'turismo-custom' ),
        'title_reply_to'     => __( 'Responder a %s', 'turismo-custom' ),
        'cancel_reply_link'  => __( 'Cancelar respuesta', 'turismo-custom' ),
        'label_submit'       => __( 'Publicar comentario', 'turismo-custom' ),
        'submit_button'      => '<button type="submit" class="submit-comment-btn"><i class="fas fa-paper-plane"></i> %4$s</button>',
        'comment_field'      => '<p class="comment-form-comment"><label for="comment">' . __( 'Comentario', 'turismo-custom' ) . ' <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="6" maxlength="65525" required="required" placeholder="' . __( 'Escribe tu comentario aquí...', 'turismo-custom' ) . '"></textarea></p>',
        'fields'             => array(
            'author' => '<p class="comment-form-author"><label for="author">' . __( 'Nombre', 'turismo-custom' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . ' placeholder="' . __( 'Tu nombre', 'turismo-custom' ) . '" /></p>',
            'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'turismo-custom' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label><input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" maxlength="100"' . $aria_req . ' placeholder="' . __( 'tu@email.com', 'turismo-custom' ) . '" /></p>',
            'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Sitio web', 'turismo-custom' ) . '</label><input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" placeholder="' . __( 'https://tusitio.com (opcional)', 'turismo-custom' ) . '" /></p>',
        ),
    ));
    ?>

</div>
