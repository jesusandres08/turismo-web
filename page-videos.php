<?php
/**
 * Template Name: Galería de Videos
 */

get_header();
?>

<main class="site-main">

    <?php
    // ==========================================
    // CONFIGURA TUS PLAYLISTS AQUÍ
    // ==========================================

    $playlists = array(
        array(
            'nombre'      => 'Destinos Turísticos',
            'slug'        => 'destinos',
            'icono'       => 'fa-map-marked-alt',
            'playlist_id' => 'PLAHOpVjwEVOoAvHUv6Wtqt1ZW-ND9TbZ7', // Tu playlist ID
            'descripcion' => 'Los mejores destinos turísticos',
        ),
        array(
            'nombre'      => 'Gastronomía',
            'slug'        => 'gastronomia',
            'icono'       => 'fa-utensils',
            'playlist_id' => 'PLAHOpVjwEVOoAvHUv6Wtqt1ZW-ND9TbZ7', // Tu playlist ID
            'descripcion' => 'Descubre los sabores auténticos',
        ),
        array(
            'nombre'      => 'Cultura y Tradiciones',
            'slug'        => 'cultura',
            'icono'       => 'fa-theater-masks',
            'playlist_id' => 'PLAHOpVjwEVOoAvHUv6Wtqt1ZW-ND9TbZ7', // Tu playlist ID
            'descripcion' => 'Tradiciones y festividades',
        ),
        array(
            'nombre'      => 'Aventura y Naturaleza',
            'slug'        => 'aventura',
            'icono'       => 'fa-hiking',
            'playlist_id' => 'PLAHOpVjwEVOoAvHUv6Wtqt1ZW-ND9TbZ7', // Tu playlist ID
            'descripcion' => 'Experiencias de aventura',
        ),
    );

    // Obtener categoría seleccionada
    $categoria_actual = isset($_GET['cat']) ? sanitize_text_field($_GET['cat']) : $playlists[0]['slug'];
    $playlist_actual = null;

    foreach ($playlists as $pl) {
        if ($pl['slug'] === $categoria_actual) {
            $playlist_actual = $pl;
            break;
        }
    }

    // Si no se encuentra, usar la primera
    if (!$playlist_actual) {
        $playlist_actual = $playlists[0];
    }

    // Obtener videos de la playlist
    $videos = turismo_get_playlist_videos($playlist_actual['playlist_id']);
    ?>

    <!-- Categorías Card -->
    <section class="categorias-card">
        <h2 class="categorias-titulo">Explorar por Categoría</h2>
        <div class="categorias-grid">
            <?php foreach ($playlists as $playlist) : ?>
                <a href="<?php echo esc_url(add_query_arg('cat', $playlist['slug'])); ?>"
                   class="categoria-btn <?php echo $categoria_actual === $playlist['slug'] ? 'active' : ''; ?>"
                   title="<?php echo esc_attr($playlist['descripcion']); ?>">
                    <i class="fas <?php echo esc_attr($playlist['icono']); ?>"></i>
                    <?php echo esc_html($playlist['nombre']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Video Player Card Premium con Lista de Videos -->
    <section class="video-player-card">
        <div class="player-container">

            <!-- Reproductor Principal -->
            <div class="player-main">
                <div class="player-video-wrapper" id="main-player">
                    <?php if (!empty($videos)) : ?>
                        <iframe
                            id="youtube-iframe"
                            src="https://www.youtube.com/embed/<?php echo esc_attr($videos[0]['id']); ?>?autoplay=0&rel=0"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                        ></iframe>
                    <?php else : ?>
                        <div class="no-videos-message">
                            <i class="fas fa-video-slash"></i>
                            <p>No se pudieron cargar los videos de esta playlist.</p>
                            <small>Verifica que el playlist ID sea correcto y que la playlist sea pública.</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Playlist Sidebar con Lista de Videos -->
            <div class="player-playlist">
                <div class="playlist-videos" id="playlist-container">
                    <?php if (!empty($videos)) : ?>
                        <?php foreach ($videos as $index => $video) : ?>
                            <div class="playlist-item <?php echo $index === 0 ? 'active' : ''; ?>"
                                 data-video-id="<?php echo esc_attr($video['id']); ?>"
                                 data-video-title="<?php echo esc_attr($video['title']); ?>">
                                <span class="playlist-item-index"><?php echo $index + 1; ?></span>
                                <div class="playlist-item-thumbnail">
                                    <img src="<?php echo esc_url($video['thumbnail']); ?>"
                                         alt="<?php echo esc_attr($video['title']); ?>">
                                    <span class="playlist-item-duration">
                                        <?php echo $video['duration'] ? esc_html($video['duration']) : '02:13'; ?>
                                    </span>
                                </div>
                                <div class="playlist-item-info">
                                    <h3 class="playlist-item-title"><?php echo esc_html($video['title']); ?></h3>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="no-videos-message" style="padding: 20px; text-align: center;">
                            <p>No hay videos disponibles</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div><!-- .player-container -->
    </section>

    <!-- Instrucciones de Configuración -->
    <section class="config-instructions" style="max-width: 1400px; margin: 40px auto; padding: 0 50px;">
        <div style="background: linear-gradient(135deg, #e0f2fe, #dbeafe); border-left: 4px solid #003F87; border-radius: 12px; padding: 30px;">
            <h3 style="color: #003F87; margin: 0 0 15px 0; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-cog"></i>
                Cómo configurar tus propias playlists
            </h3>

            <div style="color: #334155; line-height: 1.8;">
                <p><strong>Paso 1:</strong> Obtén el ID de tu playlist de YouTube</p>
                <ol style="margin: 10px 0 20px 20px;">
                    <li>Abre tu playlist en YouTube</li>
                    <li>Copia la URL: <code style="background: white; padding: 2px 8px; border-radius: 4px;">https://www.youtube.com/playlist?list=<span style="color: #f59e0b;">PLrAXtmErZgOe...</span></code></li>
                    <li>El ID es la parte después de <code style="background: white; padding: 2px 8px; border-radius: 4px;">list=</code></li>
                </ol>

                <p><strong>Paso 2:</strong> Edita el archivo del tema</p>
                <ol style="margin: 10px 0 20px 20px;">
                    <li>Ve a: <code style="background: white; padding: 2px 8px; border-radius: 4px;">Apariencia → Editor de archivos del tema</code></li>
                    <li>Selecciona: <code style="background: white; padding: 2px 8px; border-radius: 4px;">page-videos.php</code></li>
                    <li>Busca la línea 16 donde dice <code style="background: white; padding: 2px 8px; border-radius: 4px;">$playlists = array(</code></li>
                    <li>Reemplaza los <code style="background: white; padding: 2px 8px; border-radius: 4px;">playlist_id</code> con tus IDs reales</li>
                    <li>Guarda los cambios</li>
                </ol>
            </div>
        </div>
    </section>

</main><!-- .site-main -->

<!-- Modal para Pantalla Completa -->
<div class="modal-overlay" id="video-modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeVideoModal()" aria-label="Cerrar modal">
            <i class="fas fa-times"></i>
        </button>

        <div class="modal-video">
            <iframe
                id="modal-youtube-iframe"
                src=""
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen
            ></iframe>
        </div>

        <div class="modal-info">
            <div class="modal-header">
                <h2 class="modal-titulo" id="modal-video-title"></h2>
                <span class="modal-categoria">
                    <i class="fas <?php echo esc_attr($playlist_actual['icono']); ?>"></i>
                    Video
                </span>
            </div>
            <div class="modal-actions">
                <button class="modal-btn" onclick="closeVideoModal()">
                    <i class="fas fa-compress"></i>
                    Cerrar pantalla completa
                </button>
                <a href="" id="modal-youtube-link" target="_blank" rel="noopener" class="modal-btn">
                    <i class="fab fa-youtube"></i>
                    Ver en YouTube
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const playlistItems = document.querySelectorAll('.playlist-item');
    const mainIframe = document.getElementById('youtube-iframe');
    const modal = document.getElementById('video-modal');
    const modalIframe = document.getElementById('modal-youtube-iframe');
    const modalTitle = document.getElementById('modal-video-title');
    const modalLink = document.getElementById('modal-youtube-link');

    let currentVideoId = '<?php echo !empty($videos) ? esc_js($videos[0]['id']) : ''; ?>';
    let currentVideoTitle = '<?php echo !empty($videos) ? esc_js($videos[0]['title']) : ''; ?>';

    // Click en item de playlist
    playlistItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            const videoId = this.getAttribute('data-video-id');
            const videoTitle = this.getAttribute('data-video-title');

            // Actualizar iframe principal
            if (mainIframe) {
                mainIframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
            }

            // Actualizar estado activo
            playlistItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            // Guardar video actual
            currentVideoId = videoId;
            currentVideoTitle = videoTitle;
        });

        // Doble click para pantalla completa
        item.addEventListener('dblclick', function() {
            const videoId = this.getAttribute('data-video-id');
            const videoTitle = this.getAttribute('data-video-title');
            openVideoModal(videoId, videoTitle);
        });
    });

    // Función para abrir modal
    window.openVideoModal = function(videoId, videoTitle) {
        if (!videoId) {
            videoId = currentVideoId;
            videoTitle = currentVideoTitle;
        }

        modalIframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
        modalTitle.textContent = videoTitle;
        modalLink.href = `https://www.youtube.com/watch?v=${videoId}`;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    // Función para cerrar modal
    window.closeVideoModal = function() {
        modal.classList.remove('active');
        modalIframe.src = '';
        document.body.style.overflow = '';
    };

    // Click fuera del modal para cerrar
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeVideoModal();
        }
    });

    // Tecla ESC para cerrar
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeVideoModal();
        }
    });

    // Ajustar altura de la playlist
    function adjustPlaylistHeight() {
        const playerWrapper = document.querySelector('.player-video-wrapper');
        const playlistSidebar = document.querySelector('.player-playlist');
        const playlistVideos = document.querySelector('.playlist-videos');

        if (playerWrapper && playlistSidebar && playlistVideos) {
            const videoHeight = playerWrapper.offsetHeight;
            playlistSidebar.style.height = videoHeight + 'px';
            playlistVideos.style.height = videoHeight + 'px';
        }
    }

    window.addEventListener('load', adjustPlaylistHeight);
    window.addEventListener('resize', adjustPlaylistHeight);

    // Ajustar después de un pequeño delay para asegurar que el iframe esté cargado
    setTimeout(adjustPlaylistHeight, 500);
});
</script>

<?php
get_footer();
?>
