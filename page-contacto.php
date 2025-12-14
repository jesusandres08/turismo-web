<?php
/**
 * Template Name: Página de Contacto
 * Description: Página de contacto personalizada con formulario, mapa y datos de contacto
 */

get_header();
?>

<main class="site-main contacto-page">

    <!-- Hero Section -->
    <section class="contacto-hero">
        <div class="contacto-hero-overlay"></div>
        <div class="contacto-hero-content">
            <h1 class="contacto-hero-titulo">
                <i class="fas fa-envelope"></i>
                Contáctanos
            </h1>
            <p class="contacto-hero-subtitulo">
                Estamos aquí para ayudarte. Envíanos un mensaje y te responderemos pronto.
            </p>
        </div>
    </section>

    <!-- Sección Principal de Contacto -->
    <section class="contacto-main-section">
        <div class="contacto-container">

            <div class="contacto-layout">

                <!-- Columna Izquierda: Información de Contacto -->
                <aside class="contacto-info-sidebar">

                    <!-- Información General -->
                    <div class="contacto-info-card">
                        <div class="contacto-info-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3>Información de Contacto</h3>

                        <?php
                        $direccion = get_theme_mod('turismo_contacto_direccion', '');
                        $telefono = get_theme_mod('turismo_contacto_telefono', '');
                        $email = 'jesussandres@gmail.com'; // Email fijo para mostrar al público
                        $horario = get_theme_mod('turismo_contacto_horario', '');
                        ?>

                        <?php if ($direccion) : ?>
                            <div class="contacto-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div class="contacto-info-text">
                                    <strong>Dirección</strong>
                                    <p><?php echo nl2br(esc_html($direccion)); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($telefono) : ?>
                            <div class="contacto-info-item">
                                <i class="fas fa-phone"></i>
                                <div class="contacto-info-text">
                                    <strong>Teléfono</strong>
                                    <p><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $telefono)); ?>"><?php echo esc_html($telefono); ?></a></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($email) : ?>
                            <div class="contacto-info-item">
                                <i class="fas fa-envelope"></i>
                                <div class="contacto-info-text">
                                    <strong>Email</strong>
                                    <p><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($horario) : ?>
                            <div class="contacto-info-item">
                                <i class="fas fa-clock"></i>
                                <div class="contacto-info-text">
                                    <strong>Horario de Atención</strong>
                                    <p><?php echo nl2br(esc_html($horario)); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Redes Sociales -->
                    <?php
                    $facebook = get_theme_mod('turismo_contacto_facebook', '');
                    $instagram = get_theme_mod('turismo_contacto_instagram', '');
                    $twitter = get_theme_mod('turismo_contacto_twitter', '');
                    $youtube = get_theme_mod('turismo_contacto_youtube', '');

                    if ($facebook || $instagram || $twitter || $youtube) :
                    ?>
                        <div class="contacto-redes-card">
                            <h3>Síguenos en Redes Sociales</h3>
                            <div class="contacto-redes-links">
                                <?php if ($facebook) : ?>
                                    <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer" class="contacto-red-link facebook">
                                        <i class="fab fa-facebook-f"></i>
                                        <span>Facebook</span>
                                    </a>
                                <?php endif; ?>

                                <?php if ($instagram) : ?>
                                    <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer" class="contacto-red-link instagram">
                                        <i class="fab fa-instagram"></i>
                                        <span>Instagram</span>
                                    </a>
                                <?php endif; ?>

                                <?php if ($twitter) : ?>
                                    <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" class="contacto-red-link twitter">
                                        <i class="fab fa-twitter"></i>
                                        <span>Twitter</span>
                                    </a>
                                <?php endif; ?>

                                <?php if ($youtube) : ?>
                                    <a href="<?php echo esc_url($youtube); ?>" target="_blank" rel="noopener noreferrer" class="contacto-red-link youtube">
                                        <i class="fab fa-youtube"></i>
                                        <span>YouTube</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </aside>

                <!-- Columna Derecha: Formulario de Contacto -->
                <div class="contacto-form-container">

                    <div class="contacto-form-card">
                        <h2>Envíanos un Mensaje</h2>
                        <p class="contacto-form-descripcion">
                            Completa el formulario y nos pondremos en contacto contigo lo antes posible.
                        </p>

                        <!-- Mensajes de respuesta -->
                        <div id="contacto-mensaje" class="contacto-mensaje" style="display: none;"></div>

                        <form id="contacto-form" class="contacto-form" method="post">
                            <?php wp_nonce_field('turismo_contacto_nonce', 'contacto_nonce'); ?>

                            <div class="contacto-form-row">
                                <div class="contacto-form-group">
                                    <label for="contacto_nombre">
                                        <i class="fas fa-user"></i>
                                        Nombre completo <span class="requerido">*</span>
                                    </label>
                                    <input type="text" id="contacto_nombre" name="contacto_nombre" required>
                                </div>

                                <div class="contacto-form-group">
                                    <label for="contacto_email">
                                        <i class="fas fa-envelope"></i>
                                        Email <span class="requerido">*</span>
                                    </label>
                                    <input type="email" id="contacto_email" name="contacto_email" required>
                                </div>
                            </div>

                            <div class="contacto-form-row">
                                <div class="contacto-form-group">
                                    <label for="contacto_telefono">
                                        <i class="fas fa-phone"></i>
                                        Teléfono
                                    </label>
                                    <input type="tel" id="contacto_telefono" name="contacto_telefono">
                                </div>

                                <div class="contacto-form-group">
                                    <label for="contacto_asunto">
                                        <i class="fas fa-tag"></i>
                                        Asunto <span class="requerido">*</span>
                                    </label>
                                    <input type="text" id="contacto_asunto" name="contacto_asunto" required>
                                </div>
                            </div>

                            <div class="contacto-form-group">
                                <label for="contacto_mensaje">
                                    <i class="fas fa-comment"></i>
                                    Mensaje <span class="requerido">*</span>
                                </label>
                                <textarea id="contacto_mensaje" name="contacto_mensaje" rows="6" required></textarea>
                            </div>

                            <button type="submit" class="contacto-submit-btn" id="contacto-submit">
                                <i class="fas fa-paper-plane"></i>
                                Enviar Mensaje
                            </button>
                        </form>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- Mapa de Google Maps -->
    <?php
    $maps_url = get_theme_mod('turismo_contacto_maps_url', '');
    if ($maps_url) :
    ?>
        <section class="contacto-mapa-section">
            <div class="contacto-mapa-container">
                <h2 class="contacto-mapa-titulo">
                    <i class="fas fa-map-marked-alt"></i>
                    Nuestra Ubicación
                </h2>
                <div class="contacto-mapa-wrapper">
                    <iframe
                        src="<?php echo esc_url($maps_url); ?>"
                        width="100%"
                        height="450"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </section>
    <?php endif; ?>

</main>

<script>
// Manejo del formulario de contacto con AJAX
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contacto-form');
    const submitBtn = document.getElementById('contacto-submit');
    const mensaje = document.getElementById('contacto-mensaje');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Deshabilitar botón
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';

            // Ocultar mensajes previos
            mensaje.style.display = 'none';

            // Preparar datos
            const formData = new FormData(form);
            formData.append('action', 'turismo_enviar_contacto');

            // Enviar por AJAX
            fetch(turismoData.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                mensaje.style.display = 'block';
                mensaje.className = 'contacto-mensaje ' + (data.success ? 'success' : 'error');
                mensaje.innerHTML = '<i class="fas fa-' + (data.success ? 'check-circle' : 'exclamation-circle') + '"></i> ' + data.data.message;

                if (data.success) {
                    form.reset();
                }

                // Scroll al mensaje
                mensaje.scrollIntoView({ behavior: 'smooth', block: 'center' });
            })
            .catch(error => {
                mensaje.style.display = 'block';
                mensaje.className = 'contacto-mensaje error';
                mensaje.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error al enviar el mensaje. Por favor intenta nuevamente.';
            })
            .finally(() => {
                // Rehabilitar botón
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Mensaje';
            });
        });
    }
});
</script>

<?php
get_footer();
?>
