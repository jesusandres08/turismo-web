<?php
/**
 * 404 Not Found Template
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
        
        <main class="site-main">
            <div class="container">
                <div class="error-404">
                    <h1><?php _e( 'Página no encontrada', 'turismo-custom' ); ?></h1>
                    <p><?php _e( 'Lo sentimos, la página que buscas no existe.', 'turismo-custom' ); ?></p>
                    <a href="<?php echo esc_url( turismo_home_url() ); ?>" class="button">
                        <?php _e( 'Volver al inicio', 'turismo-custom' ); ?>
                    </a>
                </div>
            </div>
        </main>
        
        <?php get_footer(); ?>
    </div>
    
    <?php wp_footer(); ?>
</body>
</html>