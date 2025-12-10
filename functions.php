<?php
/**
 * Turismo Custom Theme Functions
 */

// Definir constante del directorio del theme
define( 'TURISMO_DIR', get_template_directory() );
define( 'TURISMO_URI', get_template_directory_uri() );

/**
 * 1. Cargar estilos y scripts
 */
function turismo_enqueue_assets() {
    // Cargar Google Fonts - Inter
    wp_enqueue_style(
        'turismo-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );

    // Cargar Font Awesome
    wp_enqueue_style(
        'turismo-font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );

    // Cargar CSS del tema
    wp_enqueue_style(
        'turismo-style',
        TURISMO_URI . '/style.css',
        array( 'turismo-google-fonts', 'turismo-font-awesome' ),
        filemtime( TURISMO_DIR . '/style.css' )
    );

    // Cargar archivo CSS adicional si existe
    if ( file_exists( TURISMO_DIR . '/css/main.css' ) ) {
        wp_enqueue_style(
            'turismo-main',
            TURISMO_URI . '/css/main.css',
            array( 'turismo-style' ),
            filemtime( TURISMO_DIR . '/css/main.css' )
        );
    }
    
    // Cargar jQuery (viene con WordPress)
    wp_enqueue_script( 'jquery' );
    
    // Cargar script personalizado
    wp_enqueue_script( 
        'turismo-main', 
        TURISMO_URI . '/js/main.js', 
        array( 'jquery' ), 
        filemtime( TURISMO_DIR . '/js/main.js' ), 
        true 
    );
    
    // Localizar script para pasar datos de PHP a JS
    wp_localize_script( 'turismo-main', 'turismoData', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'theme_url' => TURISMO_URI,
    ));
}
add_action( 'wp_enqueue_scripts', 'turismo_enqueue_assets' );

/**
 * 2. Soporte para características de WordPress
 */
function turismo_setup() {
    // Agregar soporte para logo personalizado
    add_theme_support( 'custom-logo' );
    
    // Agregar soporte para título del sitio personalizado
    add_theme_support( 'title-tag' );
    
    // Agregar soporte para imagen destacada
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 825, 510, true );
    
    // Agregar soporte para HTML5
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Registrar menús
    register_nav_menus( array(
        'primary' => __( 'Menú Principal', 'turismo-custom' ),
        'footer' => __( 'Menú Footer', 'turismo-custom' ),
    ));
}
add_action( 'after_setup_theme', 'turismo_setup' );

/**
 * 3. Registrar widgets
 */
function turismo_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar Principal', 'turismo-custom' ),
        'id'            => 'primary-sidebar',
        'description'   => __( 'Sidebar principal del sitio', 'turismo-custom' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar( array(
        'name'          => __( 'Sidebar Footer', 'turismo-custom' ),
        'id'            => 'footer-sidebar',
        'description'   => __( 'Sidebar del footer', 'turismo-custom' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action( 'widgets_init', 'turismo_widgets_init' );

/**
 * 4. Funciones personalizadas para el tema
 */

// Obtener logo personalizado
function turismo_get_logo() {
    $logo_id = get_theme_mod( 'custom_logo' );
    if ( $logo_id ) {
        return wp_get_attachment_url( $logo_id );
    }
    return '';
}

// Obtener URL del home
function turismo_home_url() {
    return home_url( '/' );
}

// Obtener URL del template
function turismo_template_url() {
    return TURISMO_URI;
}

/**
 * 5. Comentarios personalizados
 */
function turismo_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <div id="comment-<?php comment_ID(); ?>" class="comment">
            <div class="comment-author">
                <?php echo get_avatar( $comment, 32 ); ?>
                <strong><?php comment_author_link(); ?></strong> -
                <em><?php comment_date( 'd/m/Y' ); ?></em>
            </div>
            <div class="comment-content">
                <?php comment_text(); ?>
            </div>
            <?php
            comment_reply_link( array_merge( $args, array(
                'depth'      => $depth,
                'max_depth'  => $args['max_depth'],
                'reply_text' => __( 'Responder', 'turismo-custom' )
            )));
            ?>
        </div>
    <?php
}

/**
 * 6. Personalizar login
 */
function turismo_login_logo() {
    $logo = turismo_get_logo();
    if ( $logo ) {
        echo '<style type="text/css">
            #login h1 a, .login h1 a {
                background-image: url(' . esc_url( $logo ) . ');
                background-size: contain;
                background-repeat: no-repeat;
                width: 100%;
                height: 100px;
            }
        </style>';
    }
}
add_action( 'login_enqueue_scripts', 'turismo_login_logo' );

/**
 * 7. Filtro para agregar clases personalizadas al body
 */
function turismo_body_classes( $classes ) {
    if ( is_home() ) {
        $classes[] = 'blog-home';
    }
    if ( is_singular() ) {
        $classes[] = 'singular-' . get_post_type();
    }
    return $classes;
}
add_filter( 'body_class', 'turismo_body_classes' );

/**
 * 8. Soporte para customizer
 */
function turismo_customize_register( $wp_customize ) {
    // Color principal
    $wp_customize->add_setting( 'turismo_primary_color', array(
        'default' => '#0073aa',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
        'turismo_primary_color',
        array(
            'label' => __( 'Color Principal', 'turismo-custom' ),
            'section' => 'colors',
        )
    ));
}
add_action( 'customize_register', 'turismo_customize_register' );

/**
 * 9. Custom Post Type: Videos de YouTube
 */
function turismo_register_videos_cpt() {
    $labels = array(
        'name'               => 'Videos',
        'singular_name'      => 'Video',
        'menu_name'          => 'Videos',
        'add_new'            => 'Añadir Video',
        'add_new_item'       => 'Añadir Nuevo Video',
        'edit_item'          => 'Editar Video',
        'new_item'           => 'Nuevo Video',
        'view_item'          => 'Ver Video',
        'search_items'       => 'Buscar Videos',
        'not_found'          => 'No se encontraron videos',
        'not_found_in_trash' => 'No hay videos en la papelera',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'videos' ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-video-alt3',
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'        => true,
        'taxonomies'          => array( 'categoria-video' ),
    );

    register_post_type( 'video', $args );
}
add_action( 'init', 'turismo_register_videos_cpt' );

/**
 * 10. Taxonomía: Categorías de Videos
 */
function turismo_register_video_taxonomy() {
    $labels = array(
        'name'              => 'Categorías de Video',
        'singular_name'     => 'Categoría de Video',
        'search_items'      => 'Buscar Categorías',
        'all_items'         => 'Todas las Categorías',
        'parent_item'       => 'Categoría Padre',
        'parent_item_colon' => 'Categoría Padre:',
        'edit_item'         => 'Editar Categoría',
        'update_item'       => 'Actualizar Categoría',
        'add_new_item'      => 'Añadir Nueva Categoría',
        'new_item_name'     => 'Nuevo Nombre de Categoría',
        'menu_name'         => 'Categorías',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'categoria-video' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'categoria-video', array( 'video' ), $args );
}
add_action( 'init', 'turismo_register_video_taxonomy' );

/**
 * 11. Meta Box para URL de YouTube
 */
function turismo_add_youtube_meta_box() {
    add_meta_box(
        'turismo_youtube_url',
        'URL del Video de YouTube',
        'turismo_youtube_meta_box_callback',
        'video',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'turismo_add_youtube_meta_box' );

function turismo_youtube_meta_box_callback( $post ) {
    wp_nonce_field( 'turismo_save_youtube_url', 'turismo_youtube_nonce' );
    $youtube_url = get_post_meta( $post->ID, '_youtube_url', true );
    ?>
    <p>
        <label for="turismo_youtube_url">URL del video de YouTube:</label><br>
        <input type="text" id="turismo_youtube_url" name="turismo_youtube_url" value="<?php echo esc_attr( $youtube_url ); ?>" style="width: 100%; padding: 8px; margin-top: 5px;">
        <small style="display: block; margin-top: 5px; color: #666;">Ejemplo: https://www.youtube.com/watch?v=dQw4w9WgXcQ</small>
    </p>
    <?php
}

function turismo_save_youtube_meta( $post_id ) {
    if ( ! isset( $_POST['turismo_youtube_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['turismo_youtube_nonce'], 'turismo_save_youtube_url' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['turismo_youtube_url'] ) ) {
        update_post_meta( $post_id, '_youtube_url', sanitize_text_field( $_POST['turismo_youtube_url'] ) );
    }
}
add_action( 'save_post', 'turismo_save_youtube_meta' );

/**
 * 12. Función helper: Extraer ID de video de YouTube
 */
function turismo_get_youtube_id( $url ) {
    $pattern = '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\?\/]+)/';
    preg_match( $pattern, $url, $matches );
    return isset( $matches[1] ) ? $matches[1] : '';
}

/**
 * 13. Función para obtener videos de una playlist de YouTube (sin API key)
 * Usa el sistema de embed público de YouTube
 */
function turismo_get_playlist_videos( $playlist_id ) {
    // Limpiar el playlist_id de parámetros adicionales
    $playlist_id = preg_replace('/[&?].*$/', '', $playlist_id);

    // Usar transient para cachear por 12 horas
    $transient_key = 'turismo_playlist_' . md5($playlist_id);
    $cached_videos = get_transient($transient_key);

    if ($cached_videos !== false) {
        return $cached_videos;
    }

    // Obtener la página de la playlist de YouTube
    $playlist_url = 'https://www.youtube.com/playlist?list=' . $playlist_id;
    $response = wp_remote_get($playlist_url, array(
        'timeout' => 15,
        'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ));

    if (is_wp_error($response)) {
        return array();
    }

    $html = wp_remote_retrieve_body($response);

    // Buscar datos JSON en el HTML
    if (preg_match('/var ytInitialData = ({.+?});/', $html, $matches)) {
        $data = json_decode($matches[1], true);

        // Navegar por la estructura de datos de YouTube
        $videos = array();
        $contents = null;

        // Intentar obtener los videos de la estructura de datos
        if (isset($data['contents']['twoColumnBrowseResultsRenderer']['tabs'])) {
            foreach ($data['contents']['twoColumnBrowseResultsRenderer']['tabs'] as $tab) {
                if (isset($tab['tabRenderer']['content']['sectionListRenderer']['contents'])) {
                    foreach ($tab['tabRenderer']['content']['sectionListRenderer']['contents'] as $section) {
                        if (isset($section['itemSectionRenderer']['contents'][0]['playlistVideoListRenderer']['contents'])) {
                            $contents = $section['itemSectionRenderer']['contents'][0]['playlistVideoListRenderer']['contents'];
                            break 2;
                        }
                    }
                }
            }
        }

        if ($contents) {
            foreach ($contents as $item) {
                if (isset($item['playlistVideoRenderer'])) {
                    $video = $item['playlistVideoRenderer'];
                    $video_id = $video['videoId'] ?? '';

                    if ($video_id) {
                        // Obtener título
                        $title = '';
                        if (isset($video['title']['runs'][0]['text'])) {
                            $title = $video['title']['runs'][0]['text'];
                        } elseif (isset($video['title']['simpleText'])) {
                            $title = $video['title']['simpleText'];
                        }

                        // Obtener duración
                        $duration = '';
                        if (isset($video['lengthText']['simpleText'])) {
                            $duration = $video['lengthText']['simpleText'];
                        }

                        $videos[] = array(
                            'id' => $video_id,
                            'title' => $title,
                            'thumbnail' => 'https://i.ytimg.com/vi/' . $video_id . '/hqdefault.jpg',
                            'duration' => $duration,
                            'url' => 'https://www.youtube.com/watch?v=' . $video_id,
                        );
                    }
                }
            }
        }

        // Cachear por 12 horas
        if (!empty($videos)) {
            set_transient($transient_key, $videos, 12 * HOUR_IN_SECONDS);
        }

        return $videos;
    }

    return array();
}

/**
 * Incluir el walker personalizado para iconos en el menú
 */
require_once get_template_directory() . '/inc/class-turismo-menu-icons-walker.php';

/**
 * Usar el walker personalizado para el menú principal
 */
function turismo_wp_nav_menu_args($args) {
    if (isset($args['theme_location']) && $args['theme_location'] === 'primary') {
        $args['walker'] = new Turismo_Menu_Icons_Walker();
    }
    return $args;
}
add_filter('wp_nav_menu_args', 'turismo_wp_nav_menu_args');
