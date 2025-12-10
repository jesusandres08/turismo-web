
<?php
/**
 * Videos Destacados para Home
 * Muestra un reproductor de playlist con lista de videos en la página de inicio
 */

// ==========================================
// CONFIGURA TU PLAYLIST DESTACADA AQUÍ
// ==========================================
$playlist_destacada = array(
    'nombre'      => 'Videos Destacados',
    'playlist_id' => 'PLAHOpVjwEVOoAvHUv6Wtqt1ZW-ND9TbZ7', // Reemplaza con tu playlist
    'descripcion' => 'Descubre los mejores destinos turísticos',
    'icono'       => 'fa-video',
);

// Obtener videos de la playlist
$videos = turismo_get_playlist_videos($playlist_destacada['playlist_id']);
?>

<!-- Sección Videos Destacados -->
<section class="videos-home-section">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">

        <!-- Reproductor de Videos con Lista -->
        <div class="videos-home-player">
            <div class="player-container">

                <!-- Reproductor Principal -->
                <div class="player-main">
                    <div class="player-video-wrapper" id="home-video-player">
                        <?php if (!empty($videos)) : ?>
                            <iframe
                                id="home-youtube-iframe"
                                src="https://www.youtube.com/embed/<?php echo esc_attr($videos[0]['id']); ?>?autoplay=0&rel=0"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                            ></iframe>
                        <?php else : ?>
                            <div class="no-videos-message">
                                <i class="fas fa-video-slash"></i>
                                <p>No se pudieron cargar los videos.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Playlist Sidebar -->
                <div class="player-playlist">
                    <div class="playlist-videos" id="home-playlist-container">
                        <?php if (!empty($videos)) : ?>
                            <?php
                            // Limitar a máximo 5 videos para el home
                            $videos_home = array_slice($videos, 0, 5);
                            ?>
                            <?php foreach ($videos_home as $index => $video) : ?>
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
                            <div class="no-videos-message" style="padding: 20px; text-align: center; position: relative; color: #666;">
                                <p>No hay videos disponibles</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div><!-- .player-container -->

        </div>

    </div>
</section>

<!-- Modal para Pantalla Completa (Home) -->
<div class="modal-overlay" id="home-video-modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeHomeVideoModal()" aria-label="Cerrar modal">
            <i class="fas fa-times"></i>
        </button>

        <div class="modal-video">
            <iframe
                id="home-modal-youtube-iframe"
                src=""
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen
            ></iframe>
        </div>

        <div class="modal-info">
            <div class="modal-header">
                <h2 class="modal-titulo" id="home-modal-video-title"></h2>
                <span class="modal-categoria">
                    <i class="fas <?php echo esc_attr($playlist_destacada['icono']); ?>"></i>
                    Video
                </span>
            </div>
            <div class="modal-actions">
                <button class="modal-btn" onclick="closeHomeVideoModal()">
                    <i class="fas fa-compress"></i>
                    Cerrar
                </button>
                <a href="" id="home-modal-youtube-link" target="_blank" rel="noopener" class="modal-btn">
                    <i class="fab fa-youtube"></i>
                    Ver en YouTube
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const homePlaylistItems = document.querySelectorAll('#home-playlist-container .playlist-item');
    const homeIframe = document.getElementById('home-youtube-iframe');
    const homeModal = document.getElementById('home-video-modal');
    const homeModalIframe = document.getElementById('home-modal-youtube-iframe');
    const homeModalTitle = document.getElementById('home-modal-video-title');
    const homeModalLink = document.getElementById('home-modal-youtube-link');

    let currentHomeVideoId = '<?php echo !empty($videos) ? esc_js($videos[0]['id']) : ''; ?>';
    let currentHomeVideoTitle = '<?php echo !empty($videos) ? esc_js($videos[0]['title']) : ''; ?>';

    // Click en item de playlist
    homePlaylistItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            const videoId = this.getAttribute('data-video-id');
            const videoTitle = this.getAttribute('data-video-title');

            // Actualizar iframe principal
            if (homeIframe) {
                homeIframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
            }

            // Actualizar estado activo
            homePlaylistItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            // Guardar video actual
            currentHomeVideoId = videoId;
            currentHomeVideoTitle = videoTitle;
        });

        // Doble click para pantalla completa
        item.addEventListener('dblclick', function() {
            const videoId = this.getAttribute('data-video-id');
            const videoTitle = this.getAttribute('data-video-title');
            openHomeVideoModal(videoId, videoTitle);
        });
    });

    // Función para abrir modal
    window.openHomeVideoModal = function(videoId, videoTitle) {
        if (!videoId) {
            videoId = currentHomeVideoId;
            videoTitle = currentHomeVideoTitle;
        }

        homeModalIframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
        homeModalTitle.textContent = videoTitle;
        homeModalLink.href = `https://www.youtube.com/watch?v=${videoId}`;
        homeModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    // Función para cerrar modal
    window.closeHomeVideoModal = function() {
        homeModal.classList.remove('active');
        homeModalIframe.src = '';
        document.body.style.overflow = '';
    };

    // Click fuera del modal para cerrar
    homeModal.addEventListener('click', function(e) {
        if (e.target === homeModal) {
            closeHomeVideoModal();
        }
    });

    // Tecla ESC para cerrar
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && homeModal.classList.contains('active')) {
            closeHomeVideoModal();
        }
    });

    // Ajustar altura de la playlist
    function adjustHomePlaylistHeight() {
        const playerWrapper = document.querySelector('#home-video-player');
        const playlistSidebar = document.querySelector('#home-playlist-container').parentElement;
        const playlistVideos = document.querySelector('#home-playlist-container');

        if (playerWrapper && playlistSidebar && playlistVideos) {
            const videoHeight = playerWrapper.offsetHeight;
            playlistSidebar.style.height = videoHeight + 'px';
            playlistVideos.style.height = videoHeight + 'px';
        }
    }

    window.addEventListener('load', adjustHomePlaylistHeight);
    window.addEventListener('resize', adjustHomePlaylistHeight);

    // Ajustar después de un pequeño delay
    setTimeout(adjustHomePlaylistHeight, 500);
});
</script>
