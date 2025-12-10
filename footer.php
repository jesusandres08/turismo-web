    </div><!-- .site-main -->

    <!-- Footer con Gradiente -->
    <footer class="site-footer">

        <!-- Wave Decoration -->
        <div class="footer-wave">
            <svg viewBox="0 0 1200 50" preserveAspectRatio="none">
                <path d="M0,25 Q300,0 600,25 T1200,25 L1200,50 L0,50 Z" fill="#1a365d"/>
            </svg>
        </div>

        <div class="footer-content">
            <div class="container">
                <div class="footer-widgets">

                    <!-- Columna 1: Logo y Descripción -->
                    <div class="footer-widget">
                        <?php if ( has_custom_logo() ) : ?>
                            <div class="footer-logo">
                                <?php the_custom_logo(); ?>
                            </div>
                        <?php else : ?>
                            <h3><?php bloginfo( 'name' ); ?></h3>
                        <?php endif; ?>

                        <p class="footer-slogan">Tu destino turístico ideal</p>
                        <p class="footer-desc">
                            <?php
                            $description = get_bloginfo( 'description' );
                            echo $description ? esc_html( $description ) : 'Descubre los mejores destinos turísticos y vive experiencias inolvidables.';
                            ?>
                        </p>

                        <!-- Redes Sociales -->
                        <div class="footer-social">
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
                    </div>

                    <!-- Columna 2: Enlaces Rápidos -->
                    <div class="footer-widget footer-links">
                        <h3><i class="fas fa-link"></i> Enlaces Rápidos</h3>
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fas fa-chevron-right"></i> Inicio</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/acerca-de' ) ); ?>"><i class="fas fa-chevron-right"></i> Acerca de</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/galerias' ) ); ?>"><i class="fas fa-chevron-right"></i> Galerías</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/noticias' ) ); ?>"><i class="fas fa-chevron-right"></i> Noticias</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/contacto' ) ); ?>"><i class="fas fa-chevron-right"></i> Contacto</a></li>
                        </ul>
                    </div>

                    <!-- Columna 3: Servicios -->
                    <div class="footer-widget footer-links">
                        <h3><i class="fas fa-concierge-bell"></i> Servicios</h3>
                        <ul>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Destinos Turísticos</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Paquetes Vacacionales</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Guías de Viaje</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Eventos Culturales</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Reservaciones</a></li>
                        </ul>
                    </div>

                    <!-- Columna 4: Contacto -->
                    <div class="footer-widget footer-contact">
                        <h3><i class="fas fa-envelope"></i> Contacto</h3>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <p>Av. Principal 123, Centro<br>Ciudad, Estado, C.P. 12345</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <p>+52 123 456 7890</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <p>contacto@turismo.gob.mx</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <p>Lun - Vie: 9:00 AM - 6:00 PM</p>
                            </div>
                        </div>
                    </div>

                    <!-- Si tienes widgets dinámicos, puedes usar esto en lugar de las columnas hardcoded -->
                    <?php
                    /*
                    if ( is_active_sidebar( 'footer-sidebar' ) ) {
                        dynamic_sidebar( 'footer-sidebar' );
                    }
                    */
                    ?>

                </div><!-- .footer-widgets -->
            </div><!-- .container -->
        </div><!-- .footer-content -->

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">

                <!-- Menú Footer (si existe) -->
                <?php if ( has_nav_menu( 'footer' ) ) : ?>
                    <div class="footer-menu">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer',
                            'menu_class'     => 'footer-nav',
                            'fallback_cb'    => false,
                        ));
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Copyright -->
                <div class="site-info">
                    <p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php _e( 'Todos los derechos reservados.', 'turismo-custom' ); ?></p>
                    <p><?php _e( 'Desarrollado con', 'turismo-custom' ); ?> <i class="fas fa-heart" style="color: #f2c300;"></i> <?php _e( 'por el equipo de Turismo', 'turismo-custom' ); ?></p>
                </div>

            </div><!-- .container -->
        </div><!-- .footer-bottom -->

    </footer><!-- .site-footer -->

    <!-- Botón Volver Arriba -->
    <button id="btn-volver-arriba" aria-label="Volver arriba">
        <i class="fas fa-arrow-up"></i>
    </button>

</div><!-- .site-wrapper -->

<?php wp_footer(); ?>
</body>
</html>
