<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-wrapper">

    <!-- Topbar Gubernamental (Opcional - puedes comentar si no lo necesitas) -->
    <div class="topbar-gob">
        <div class="topbar-left">
            <span><i class="fas fa-phone"></i> +52 123 456 7890</span>
            <span><i class="fas fa-envelope"></i> contacto@turismo.gob.mx</span>
        </div>
        <div class="topbar-right">
            <a href="#" class="btn-predial">Portal del Ciudadano</a>
        </div>
    </div>

    <!-- Header con Logo Centrado -->
    <header class="site-header">
        <div class="top-bar">
            <div class="header-content">

                <!-- Redes Sociales (Izquierda) -->
                <div class="header-social">
                    <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com" target="_blank" rel="noopener" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://youtube.com" target="_blank" rel="noopener" aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>

                <!-- Logo Centrado -->
                <div class="site-branding">
                    <?php if ( has_custom_logo() ) : ?>
                        <div class="logo-card">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php else : ?>
                        <div class="logo-card">
                            <h1 class="site-title">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                    <?php bloginfo( 'name' ); ?>
                                </a>
                            </h1>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Buscador (Derecha) -->
                <div class="header-search">
                    <?php get_search_form(); ?>
                </div>

            </div>
        </div>

        <!-- Menú Principal con Wrapper -->
        <div class="menu-wrapper">
            <nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Menú principal', 'turismo-custom' ); ?>">

                <!-- Botón Menú Móvil -->
                <button id="menu-btn" aria-label="Abrir menú" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Menú Desktop -->
                <?php
                if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'nav-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ));
                } else {
                    echo '<ul class="nav-menu">';
                    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '"><i class="fas fa-home"></i> Inicio</a></li>';
                    echo '<li><a href="#"><i class="fas fa-info-circle"></i> Acerca de</a></li>';
                    echo '<li><a href="#"><i class="fas fa-images"></i> Galerías</a></li>';
                    echo '<li><a href="#"><i class="fas fa-newspaper"></i> Noticias</a></li>';
                    echo '<li><a href="#"><i class="fas fa-envelope"></i> Contacto</a></li>';
                    echo '</ul>';
                }
                ?>

            </nav>
        </div>
    </header>

    <!-- Menú Móvil Lateral -->
    <div id="mobile-menu">
        <?php
        if ( has_nav_menu( 'primary' ) ) {
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'menu_class'     => 'nav-menu',
                'container'      => false,
                'fallback_cb'    => false,
            ));
        } else {
            echo '<ul class="nav-menu">';
            echo '<li><a href="' . esc_url( home_url( '/' ) ) . '"><i class="fas fa-home"></i> Inicio</a></li>';
            echo '<li><a href="#"><i class="fas fa-info-circle"></i> Acerca de</a></li>';
            echo '<li><a href="#"><i class="fas fa-images"></i> Galerías</a></li>';
            echo '<li><a href="#"><i class="fas fa-newspaper"></i> Noticias</a></li>';
            echo '<li><a href="#"><i class="fas fa-envelope"></i> Contacto</a></li>';
            echo '</ul>';
        }
        ?>
    </div>

    <!-- Overlay del menú móvil -->
    <div class="menu-overlay"></div>
