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
        'name'          => __( 'Sidebar Noticias', 'turismo-custom' ),
        'id'            => 'noticias-sidebar',
        'description'   => __( 'Sidebar para la sección de noticias en home', 'turismo-custom' ),
        'before_widget' => '<div id="%1$s" class="sidebar-widget widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
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
 * 5. Comentarios personalizados - Diseño moderno
 */
function turismo_custom_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment-avatar">
                <?php echo get_avatar( $comment, 50 ); ?>
            </div>
            <div class="comment-content-wrapper">
                <div class="comment-meta">
                    <div class="comment-author-name">
                        <?php comment_author_link(); ?>
                        <?php if ( $comment->user_id === get_post()->post_author ) : ?>
                            <span class="author-badge"><i class="fas fa-user-check"></i> Autor</span>
                        <?php endif; ?>
                    </div>
                    <div class="comment-metadata">
                        <i class="far fa-clock"></i>
                        <time datetime="<?php comment_time( 'c' ); ?>">
                            <?php printf( '%s a las %s', get_comment_date( 'd/m/Y' ), get_comment_time() ); ?>
                        </time>
                    </div>
                </div>

                <div class="comment-content">
                    <?php comment_text(); ?>
                </div>

                <div class="comment-actions">
                    <?php
                    comment_reply_link( array_merge( $args, array(
                        'depth'      => $depth,
                        'max_depth'  => $args['max_depth'],
                        'reply_text' => '<i class="fas fa-reply"></i> Responder',
                    )));
                    ?>
                    <?php edit_comment_link( '<i class="fas fa-edit"></i> Editar' ); ?>
                </div>
            </div>
        </article>
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

    // Agregar sección para configuraciones del Home
    $wp_customize->add_section( 'turismo_home_settings', array(
        'title'    => __( 'Configuración del Home', 'turismo-custom' ),
        'priority' => 30,
    ));

    // Setting: Categoría de destinos para mostrar en home
    $wp_customize->add_setting( 'turismo_destinos_home_categoria', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    // Control: Selector de categoría de destinos
    $wp_customize->add_control( 'turismo_destinos_home_categoria', array(
        'label'       => __( 'Categoría de Destinos en Home', 'turismo-custom' ),
        'description' => __( 'Selecciona la categoría de destinos a mostrar en el home. Dejar vacío para mostrar todos.', 'turismo-custom' ),
        'section'     => 'turismo_home_settings',
        'type'        => 'select',
        'choices'     => turismo_get_destinos_categorias_choices(),
    ));

    // Setting: Número de destinos a mostrar
    $wp_customize->add_setting( 'turismo_destinos_home_cantidad', array(
        'default'           => '6',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control( 'turismo_destinos_home_cantidad', array(
        'label'       => __( 'Cantidad de Destinos', 'turismo-custom' ),
        'description' => __( 'Número de destinos a mostrar en el home', 'turismo-custom' ),
        'section'     => 'turismo_home_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 12,
            'step' => 1,
        ),
    ));

    // Agregar sección para configuraciones de contacto
    $wp_customize->add_section( 'turismo_contacto_settings', array(
        'title'    => __( 'Información de Contacto', 'turismo-custom' ),
        'priority' => 35,
    ));

    // Dirección
    $wp_customize->add_setting( 'turismo_contacto_direccion', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control( 'turismo_contacto_direccion', array(
        'label'   => __( 'Dirección', 'turismo-custom' ),
        'section' => 'turismo_contacto_settings',
        'type'    => 'textarea',
    ));

    // Teléfono
    $wp_customize->add_setting( 'turismo_contacto_telefono', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'turismo_contacto_telefono', array(
        'label'   => __( 'Teléfono', 'turismo-custom' ),
        'section' => 'turismo_contacto_settings',
        'type'    => 'text',
    ));

    // Email
    $wp_customize->add_setting( 'turismo_contacto_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control( 'turismo_contacto_email', array(
        'label'   => __( 'Email', 'turismo-custom' ),
        'section' => 'turismo_contacto_settings',
        'type'    => 'email',
    ));

    // Horario
    $wp_customize->add_setting( 'turismo_contacto_horario', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control( 'turismo_contacto_horario', array(
        'label'       => __( 'Horario de Atención', 'turismo-custom' ),
        'description' => __( 'Ejemplo: Lun-Vie: 9:00 AM - 6:00 PM', 'turismo-custom' ),
        'section'     => 'turismo_contacto_settings',
        'type'        => 'textarea',
    ));

    // URL de Google Maps
    $wp_customize->add_setting( 'turismo_contacto_maps_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control( 'turismo_contacto_maps_url', array(
        'label'       => __( 'URL de Google Maps (embed)', 'turismo-custom' ),
        'description' => __( 'URL del iframe de Google Maps. Ejemplo: https://www.google.com/maps/embed?pb=...', 'turismo-custom' ),
        'section'     => 'turismo_contacto_settings',
        'type'        => 'url',
    ));

    // Facebook
    $wp_customize->add_setting( 'turismo_contacto_facebook', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control( 'turismo_contacto_facebook', array(
        'label'   => __( 'Facebook URL', 'turismo-custom' ),
        'section' => 'turismo_contacto_settings',
        'type'    => 'url',
    ));

    // Instagram
    $wp_customize->add_setting( 'turismo_contacto_instagram', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control( 'turismo_contacto_instagram', array(
        'label'   => __( 'Instagram URL', 'turismo-custom' ),
        'section' => 'turismo_contacto_settings',
        'type'    => 'url',
    ));

    // Twitter
    $wp_customize->add_setting( 'turismo_contacto_twitter', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control( 'turismo_contacto_twitter', array(
        'label'   => __( 'Twitter URL', 'turismo-custom' ),
        'section' => 'turismo_contacto_settings',
        'type'    => 'url',
    ));

    // YouTube
    $wp_customize->add_setting( 'turismo_contacto_youtube', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control( 'turismo_contacto_youtube', array(
        'label'   => __( 'YouTube URL', 'turismo-custom' ),
        'section' => 'turismo_contacto_settings',
        'type'    => 'url',
    ));
}
add_action( 'customize_register', 'turismo_customize_register' );

/**
 * Helper: Obtener opciones de categorías de destinos para el Customizer
 */
function turismo_get_destinos_categorias_choices() {
    $choices = array( '' => __( '-- Todas las categorías --', 'turismo-custom' ) );

    $categorias = get_terms( array(
        'taxonomy'   => 'categoria-destino',
        'hide_empty' => false,
    ));

    if ( ! empty( $categorias ) && ! is_wp_error( $categorias ) ) {
        foreach ( $categorias as $categoria ) {
            $choices[ $categoria->slug ] = $categoria->name;
        }
    }

    return $choices;
}

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

/**
 * AJAX para paginación de noticias
 */
function turismo_load_noticias() {
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $noticias_args = array(
        'post_type'      => 'post',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post__not_in'   => array(),
        'paged'          => $paged,
    );

    $noticias_query = new WP_Query($noticias_args);

    ob_start();

    if ($noticias_query->have_posts()) {
        while ($noticias_query->have_posts()) {
            $noticias_query->the_post();
            ?>
            <article class="noticia-card noticia-card-vertical">
                <a href="<?php the_permalink(); ?>" class="noticia-link">
                    <div class="noticia-imagen noticia-imagen-vertical">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('thumbnail', array('class' => 'noticia-img noticia-img-vertical')); ?>
                        <?php else : ?>
                            <img src="<?php echo esc_url(TURISMO_URI . '/images/placeholder.svg'); ?>"
                                 alt="<?php the_title_attribute(); ?>"
                                 class="noticia-img noticia-img-vertical">
                        <?php endif; ?>
                        <?php $categories = get_the_category(); if (!empty($categories)) : $category = $categories[0]; ?>
                            <span class="noticia-categoria">
                                <?php echo esc_html($category->name); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="noticia-contenido noticia-contenido-vertical">
                        <div class="noticia-meta">
                            <span class="noticia-fecha">
                                <i class="far fa-calendar"></i>
                                <?php echo get_the_date('j M, Y'); ?>
                            </span>
                            <span class="noticia-autor">
                                <i class="far fa-user"></i>
                                <?php echo get_the_author(); ?>
                            </span>
                        </div>
                        <h3 class="noticia-titulo">
                            <?php the_title(); ?>
                        </h3>
                        <p class="noticia-extracto">
                            <?php echo wp_trim_words(get_the_excerpt(), 14, '...'); ?>
                        </p>
                        <span class="noticia-leer-mas">Leer más <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
            </article>
            <?php
        }
    }

    $html = ob_get_clean();

    wp_send_json_success(array(
        'html' => $html,
        'max_pages' => $noticias_query->max_num_pages,
        'current_page' => $paged,
    ));

    wp_reset_postdata();
}
add_action('wp_ajax_load_noticias', 'turismo_load_noticias');
add_action('wp_ajax_nopriv_load_noticias', 'turismo_load_noticias');

/**
 * 14. Custom Post Type: Hoteles
 */
function turismo_register_hoteles_cpt() {
    $labels = array(
        'name'               => 'Hoteles',
        'singular_name'      => 'Hotel',
        'menu_name'          => 'Hoteles',
        'add_new'            => 'Añadir Hotel',
        'add_new_item'       => 'Añadir Nuevo Hotel',
        'edit_item'          => 'Editar Hotel',
        'new_item'           => 'Nuevo Hotel',
        'view_item'          => 'Ver Hotel',
        'search_items'       => 'Buscar Hoteles',
        'not_found'          => 'No se encontraron hoteles',
        'not_found_in_trash' => 'No hay hoteles en la papelera',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'hoteles' ),
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-building',
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'        => true,
        'taxonomies'          => array( 'ubicacion-hotel', 'categoria-hotel' ),
    );

    register_post_type( 'hotel', $args );
}
add_action( 'init', 'turismo_register_hoteles_cpt' );

/**
 * 15. Taxonomías: Ubicación y Categoría de Hotel
 */
function turismo_register_hotel_taxonomies() {
    // Taxonomía: Ubicación
    $ubicacion_labels = array(
        'name'              => 'Ubicaciones',
        'singular_name'     => 'Ubicación',
        'search_items'      => 'Buscar Ubicaciones',
        'all_items'         => 'Todas las Ubicaciones',
        'parent_item'       => 'Ubicación Padre',
        'parent_item_colon' => 'Ubicación Padre:',
        'edit_item'         => 'Editar Ubicación',
        'update_item'       => 'Actualizar Ubicación',
        'add_new_item'      => 'Añadir Nueva Ubicación',
        'new_item_name'     => 'Nuevo Nombre de Ubicación',
        'menu_name'         => 'Ubicaciones',
    );

    $ubicacion_args = array(
        'hierarchical'      => true,
        'labels'            => $ubicacion_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'ubicacion-hotel' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'ubicacion-hotel', array( 'hotel' ), $ubicacion_args );

    // Taxonomía: Categoría de Hotel
    $categoria_labels = array(
        'name'              => 'Categorías de Hotel',
        'singular_name'     => 'Categoría',
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

    $categoria_args = array(
        'hierarchical'      => true,
        'labels'            => $categoria_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'categoria-hotel' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'categoria-hotel', array( 'hotel' ), $categoria_args );
}
add_action( 'init', 'turismo_register_hotel_taxonomies' );

/**
 * 16. Meta Boxes para Hoteles
 */
function turismo_add_hotel_meta_boxes() {
    add_meta_box(
        'turismo_hotel_details',
        'Detalles del Hotel',
        'turismo_hotel_details_callback',
        'hotel',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'turismo_add_hotel_meta_boxes' );

function turismo_hotel_details_callback( $post ) {
    wp_nonce_field( 'turismo_save_hotel_details', 'turismo_hotel_nonce' );

    $precio = get_post_meta( $post->ID, '_hotel_precio', true );
    $estrellas = get_post_meta( $post->ID, '_hotel_estrellas', true );
    $servicios = get_post_meta( $post->ID, '_hotel_servicios', true );
    $telefono = get_post_meta( $post->ID, '_hotel_telefono', true );
    $email = get_post_meta( $post->ID, '_hotel_email', true );
    $sitio_web = get_post_meta( $post->ID, '_hotel_sitio_web', true );
    $direccion = get_post_meta( $post->ID, '_hotel_direccion', true );
    $galeria = get_post_meta( $post->ID, '_hotel_galeria', true );

    ?>
    <style>
        .hotel-meta-field { margin-bottom: 20px; }
        .hotel-meta-field label { display: block; font-weight: 600; margin-bottom: 5px; }
        .hotel-meta-field input[type="text"],
        .hotel-meta-field input[type="email"],
        .hotel-meta-field input[type="url"],
        .hotel-meta-field input[type="number"],
        .hotel-meta-field textarea,
        .hotel-meta-field select { width: 100%; padding: 8px; }
        .hotel-meta-field textarea { min-height: 100px; }
        .hotel-servicios-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 10px; }
        .hotel-servicios-grid label { display: flex; align-items: center; gap: 5px; font-weight: normal; }
    </style>

    <div class="hotel-meta-field">
        <label for="hotel_precio">Precio por noche (opcional):</label>
        <input type="text" id="hotel_precio" name="hotel_precio" value="<?php echo esc_attr( $precio ); ?>" placeholder="Ej: $150">
        <small style="color: #666;">Ejemplo: $150, $200-$300, Consultar, etc.</small>
    </div>

    <div class="hotel-meta-field">
        <label for="hotel_estrellas">Calificación (estrellas):</label>
        <select id="hotel_estrellas" name="hotel_estrellas">
            <option value="">Sin calificación</option>
            <option value="1" <?php selected( $estrellas, '1' ); ?>>1 Estrella</option>
            <option value="2" <?php selected( $estrellas, '2' ); ?>>2 Estrellas</option>
            <option value="3" <?php selected( $estrellas, '3' ); ?>>3 Estrellas</option>
            <option value="4" <?php selected( $estrellas, '4' ); ?>>4 Estrellas</option>
            <option value="5" <?php selected( $estrellas, '5' ); ?>>5 Estrellas</option>
        </select>
    </div>

    <div class="hotel-meta-field">
        <label>Servicios disponibles:</label>
        <div class="hotel-servicios-grid">
            <?php
            $servicios_disponibles = array(
                'wifi' => 'WiFi Gratis',
                'estacionamiento' => 'Estacionamiento',
                'piscina' => 'Piscina',
                'restaurante' => 'Restaurante',
                'gimnasio' => 'Gimnasio',
                'spa' => 'Spa',
                'aire_acondicionado' => 'Aire Acondicionado',
                'bar' => 'Bar',
                'recepcion_24h' => 'Recepción 24h',
                'desayuno' => 'Desayuno incluido',
                'pet_friendly' => 'Mascotas permitidas',
                'servicio_habitacion' => 'Servicio a la habitación',
            );

            $servicios_seleccionados = $servicios ? json_decode( $servicios, true ) : array();

            foreach ( $servicios_disponibles as $key => $label ) {
                $checked = in_array( $key, (array) $servicios_seleccionados ) ? 'checked' : '';
                echo '<label><input type="checkbox" name="hotel_servicios[]" value="' . esc_attr( $key ) . '" ' . $checked . '> ' . esc_html( $label ) . '</label>';
            }
            ?>
        </div>
    </div>

    <div class="hotel-meta-field">
        <label for="hotel_telefono">Teléfono:</label>
        <input type="text" id="hotel_telefono" name="hotel_telefono" value="<?php echo esc_attr( $telefono ); ?>" placeholder="Ej: +52 123 456 7890">
    </div>

    <div class="hotel-meta-field">
        <label for="hotel_email">Email:</label>
        <input type="email" id="hotel_email" name="hotel_email" value="<?php echo esc_attr( $email ); ?>" placeholder="info@hotel.com">
    </div>

    <div class="hotel-meta-field">
        <label for="hotel_sitio_web">Sitio Web:</label>
        <input type="url" id="hotel_sitio_web" name="hotel_sitio_web" value="<?php echo esc_attr( $sitio_web ); ?>" placeholder="https://www.hotel.com">
    </div>

    <div class="hotel-meta-field">
        <label for="hotel_direccion">Dirección completa:</label>
        <textarea id="hotel_direccion" name="hotel_direccion" placeholder="Calle, número, colonia, ciudad, estado"><?php echo esc_textarea( $direccion ); ?></textarea>
    </div>

    <div class="hotel-meta-field">
        <label for="hotel_galeria">Galería de imágenes (URLs separadas por comas):</label>
        <textarea id="hotel_galeria" name="hotel_galeria" placeholder="https://ejemplo.com/imagen1.jpg, https://ejemplo.com/imagen2.jpg"><?php echo esc_textarea( $galeria ); ?></textarea>
        <small style="color: #666;">Ingresa las URLs de las imágenes separadas por comas. Puedes subir imágenes a la biblioteca de medios y copiar sus URLs.</small>
    </div>
    <?php
}

function turismo_save_hotel_meta( $post_id ) {
    if ( ! isset( $_POST['turismo_hotel_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['turismo_hotel_nonce'], 'turismo_save_hotel_details' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Guardar precio
    if ( isset( $_POST['hotel_precio'] ) ) {
        update_post_meta( $post_id, '_hotel_precio', sanitize_text_field( $_POST['hotel_precio'] ) );
    }

    // Guardar estrellas
    if ( isset( $_POST['hotel_estrellas'] ) ) {
        update_post_meta( $post_id, '_hotel_estrellas', sanitize_text_field( $_POST['hotel_estrellas'] ) );
    }

    // Guardar servicios
    if ( isset( $_POST['hotel_servicios'] ) ) {
        $servicios = array_map( 'sanitize_text_field', $_POST['hotel_servicios'] );
        update_post_meta( $post_id, '_hotel_servicios', json_encode( $servicios ) );
    } else {
        update_post_meta( $post_id, '_hotel_servicios', json_encode( array() ) );
    }

    // Guardar teléfono
    if ( isset( $_POST['hotel_telefono'] ) ) {
        update_post_meta( $post_id, '_hotel_telefono', sanitize_text_field( $_POST['hotel_telefono'] ) );
    }

    // Guardar email
    if ( isset( $_POST['hotel_email'] ) ) {
        update_post_meta( $post_id, '_hotel_email', sanitize_email( $_POST['hotel_email'] ) );
    }

    // Guardar sitio web
    if ( isset( $_POST['hotel_sitio_web'] ) ) {
        update_post_meta( $post_id, '_hotel_sitio_web', esc_url_raw( $_POST['hotel_sitio_web'] ) );
    }

    // Guardar dirección
    if ( isset( $_POST['hotel_direccion'] ) ) {
        update_post_meta( $post_id, '_hotel_direccion', sanitize_textarea_field( $_POST['hotel_direccion'] ) );
    }

    // Guardar galería
    if ( isset( $_POST['hotel_galeria'] ) ) {
        update_post_meta( $post_id, '_hotel_galeria', sanitize_textarea_field( $_POST['hotel_galeria'] ) );
    }
}
add_action( 'save_post', 'turismo_save_hotel_meta' );

/**
 * 17. Custom Post Type: Restaurantes
 */
function turismo_register_restaurantes_cpt() {
    $labels = array(
        'name'               => 'Restaurantes',
        'singular_name'      => 'Restaurante',
        'menu_name'          => 'Restaurantes',
        'add_new'            => 'Añadir Restaurante',
        'add_new_item'       => 'Añadir Nuevo Restaurante',
        'edit_item'          => 'Editar Restaurante',
        'new_item'           => 'Nuevo Restaurante',
        'view_item'          => 'Ver Restaurante',
        'search_items'       => 'Buscar Restaurantes',
        'not_found'          => 'No se encontraron restaurantes',
        'not_found_in_trash' => 'No hay restaurantes en la papelera',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'restaurantes' ),
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-food',
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'        => true,
        'taxonomies'          => array( 'ubicacion-restaurante', 'tipo-cocina' ),
    );

    register_post_type( 'restaurante', $args );
}
add_action( 'init', 'turismo_register_restaurantes_cpt' );

/**
 * 18. Taxonomías: Ubicación y Tipo de Cocina para Restaurantes
 */
function turismo_register_restaurante_taxonomies() {
    // Taxonomía: Ubicación
    $ubicacion_labels = array(
        'name'              => 'Ubicaciones',
        'singular_name'     => 'Ubicación',
        'search_items'      => 'Buscar Ubicaciones',
        'all_items'         => 'Todas las Ubicaciones',
        'parent_item'       => 'Ubicación Padre',
        'parent_item_colon' => 'Ubicación Padre:',
        'edit_item'         => 'Editar Ubicación',
        'update_item'       => 'Actualizar Ubicación',
        'add_new_item'      => 'Añadir Nueva Ubicación',
        'new_item_name'     => 'Nuevo Nombre de Ubicación',
        'menu_name'         => 'Ubicaciones',
    );

    $ubicacion_args = array(
        'hierarchical'      => true,
        'labels'            => $ubicacion_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'ubicacion-restaurante' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'ubicacion-restaurante', array( 'restaurante' ), $ubicacion_args );

    // Taxonomía: Tipo de Cocina
    $cocina_labels = array(
        'name'              => 'Tipos de Cocina',
        'singular_name'     => 'Tipo de Cocina',
        'search_items'      => 'Buscar Tipos',
        'all_items'         => 'Todos los Tipos',
        'parent_item'       => 'Tipo Padre',
        'parent_item_colon' => 'Tipo Padre:',
        'edit_item'         => 'Editar Tipo',
        'update_item'       => 'Actualizar Tipo',
        'add_new_item'      => 'Añadir Nuevo Tipo',
        'new_item_name'     => 'Nuevo Tipo de Cocina',
        'menu_name'         => 'Tipos de Cocina',
    );

    $cocina_args = array(
        'hierarchical'      => true,
        'labels'            => $cocina_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'tipo-cocina' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'tipo-cocina', array( 'restaurante' ), $cocina_args );
}
add_action( 'init', 'turismo_register_restaurante_taxonomies' );

/**
 * 19. Meta Boxes para Restaurantes
 */
function turismo_add_restaurante_meta_boxes() {
    add_meta_box(
        'turismo_restaurante_details',
        'Detalles del Restaurante',
        'turismo_restaurante_details_callback',
        'restaurante',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'turismo_add_restaurante_meta_boxes' );

function turismo_restaurante_details_callback( $post ) {
    wp_nonce_field( 'turismo_save_restaurante_details', 'turismo_restaurante_nonce' );

    $rango_precio = get_post_meta( $post->ID, '_restaurante_rango_precio', true );
    $calificacion = get_post_meta( $post->ID, '_restaurante_calificacion', true );
    $horario = get_post_meta( $post->ID, '_restaurante_horario', true );
    $telefono = get_post_meta( $post->ID, '_restaurante_telefono', true );
    $email = get_post_meta( $post->ID, '_restaurante_email', true );
    $sitio_web = get_post_meta( $post->ID, '_restaurante_sitio_web', true );
    $direccion = get_post_meta( $post->ID, '_restaurante_direccion', true );
    $menu_url = get_post_meta( $post->ID, '_restaurante_menu_url', true );
    $galeria = get_post_meta( $post->ID, '_restaurante_galeria', true );
    $especialidades = get_post_meta( $post->ID, '_restaurante_especialidades', true );
    $servicios = get_post_meta( $post->ID, '_restaurante_servicios', true );

    ?>
    <style>
        .restaurante-meta-field { margin-bottom: 20px; }
        .restaurante-meta-field label { display: block; font-weight: 600; margin-bottom: 5px; }
        .restaurante-meta-field input[type="text"],
        .restaurante-meta-field input[type="email"],
        .restaurante-meta-field input[type="url"],
        .restaurante-meta-field textarea,
        .restaurante-meta-field select { width: 100%; padding: 8px; }
        .restaurante-meta-field textarea { min-height: 100px; }
        .restaurante-servicios-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 10px; }
        .restaurante-servicios-grid label { display: flex; align-items: center; gap: 5px; font-weight: normal; }
    </style>

    <div class="restaurante-meta-field">
        <label for="restaurante_rango_precio">Rango de Precio:</label>
        <select id="restaurante_rango_precio" name="restaurante_rango_precio">
            <option value="">Seleccionar</option>
            <option value="$" <?php selected( $rango_precio, '$' ); ?>>$ - Económico</option>
            <option value="$$" <?php selected( $rango_precio, '$$' ); ?>>$$ - Moderado</option>
            <option value="$$$" <?php selected( $rango_precio, '$$$' ); ?>>$$$ - Costoso</option>
            <option value="$$$$" <?php selected( $rango_precio, '$$$$' ); ?>>$$$$ - Muy costoso</option>
        </select>
    </div>

    <div class="restaurante-meta-field">
        <label for="restaurante_calificacion">Calificación (estrellas):</label>
        <select id="restaurante_calificacion" name="restaurante_calificacion">
            <option value="">Sin calificación</option>
            <option value="1" <?php selected( $calificacion, '1' ); ?>>1 Estrella</option>
            <option value="2" <?php selected( $calificacion, '2' ); ?>>2 Estrellas</option>
            <option value="3" <?php selected( $calificacion, '3' ); ?>>3 Estrellas</option>
            <option value="4" <?php selected( $calificacion, '4' ); ?>>4 Estrellas</option>
            <option value="5" <?php selected( $calificacion, '5' ); ?>>5 Estrellas</option>
        </select>
    </div>

    <div class="restaurante-meta-field">
        <label for="restaurante_horario">Horario:</label>
        <textarea id="restaurante_horario" name="restaurante_horario" placeholder="Ej: Lun-Dom: 12:00 PM - 11:00 PM"><?php echo esc_textarea( $horario ); ?></textarea>
        <small style="color: #666;">Describe el horario de atención del restaurante</small>
    </div>

    <div class="restaurante-meta-field">
        <label>Servicios disponibles:</label>
        <div class="restaurante-servicios-grid">
            <?php
            $servicios_disponibles = array(
                'wifi' => 'WiFi Gratis',
                'estacionamiento' => 'Estacionamiento',
                'terraza' => 'Terraza',
                'bar' => 'Bar',
                'delivery' => 'Servicio a domicilio',
                'reservaciones' => 'Acepta reservaciones',
                'aire_acondicionado' => 'Aire Acondicionado',
                'musica_vivo' => 'Música en vivo',
                'pet_friendly' => 'Mascotas permitidas',
                'eventos_privados' => 'Eventos privados',
                'buffet' => 'Buffet',
                'desayuno' => 'Desayuno',
            );

            $servicios_seleccionados = $servicios ? json_decode( $servicios, true ) : array();

            foreach ( $servicios_disponibles as $key => $label ) {
                $checked = in_array( $key, (array) $servicios_seleccionados ) ? 'checked' : '';
                echo '<label><input type="checkbox" name="restaurante_servicios[]" value="' . esc_attr( $key ) . '" ' . $checked . '> ' . esc_html( $label ) . '</label>';
            }
            ?>
        </div>
    </div>

    <div class="restaurante-meta-field">
        <label for="restaurante_especialidades">Especialidades del chef:</label>
        <textarea id="restaurante_especialidades" name="restaurante_especialidades" placeholder="Ej: Mole poblano, Tacos al pastor, etc."><?php echo esc_textarea( $especialidades ); ?></textarea>
    </div>

    <div class="restaurante-meta-field">
        <label for="restaurante_telefono">Teléfono:</label>
        <input type="text" id="restaurante_telefono" name="restaurante_telefono" value="<?php echo esc_attr( $telefono ); ?>" placeholder="Ej: +52 123 456 7890">
    </div>

    <div class="restaurante-meta-field">
        <label for="restaurante_email">Email:</label>
        <input type="email" id="restaurante_email" name="restaurante_email" value="<?php echo esc_attr( $email ); ?>" placeholder="info@restaurante.com">
    </div>

    <div class="restaurante-meta-field">
        <label for="restaurante_sitio_web">Sitio Web:</label>
        <input type="url" id="restaurante_sitio_web" name="restaurante_sitio_web" value="<?php echo esc_attr( $sitio_web ); ?>" placeholder="https://www.restaurante.com">
    </div>

    <div class="restaurante-meta-field">
        <label for="restaurante_direccion">Dirección completa:</label>
        <textarea id="restaurante_direccion" name="restaurante_direccion" placeholder="Calle, número, colonia, ciudad, estado"><?php echo esc_textarea( $direccion ); ?></textarea>
    </div>

    <div class="restaurante-meta-field">
        <label for="restaurante_menu_url">URL del Menú (PDF o imagen):</label>
        <input type="url" id="restaurante_menu_url" name="restaurante_menu_url" value="<?php echo esc_attr( $menu_url ); ?>" placeholder="https://ejemplo.com/menu.pdf">
        <small style="color: #666;">Sube el menú a la biblioteca de medios y copia su URL aquí</small>
    </div>

    <div class="restaurante-meta-field">
        <label for="restaurante_galeria">Galería de imágenes (URLs separadas por comas):</label>
        <textarea id="restaurante_galeria" name="restaurante_galeria" placeholder="https://ejemplo.com/imagen1.jpg, https://ejemplo.com/imagen2.jpg"><?php echo esc_textarea( $galeria ); ?></textarea>
        <small style="color: #666;">Ingresa las URLs de las imágenes separadas por comas</small>
    </div>
    <?php
}

function turismo_save_restaurante_meta( $post_id ) {
    if ( ! isset( $_POST['turismo_restaurante_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['turismo_restaurante_nonce'], 'turismo_save_restaurante_details' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Guardar rango de precio
    if ( isset( $_POST['restaurante_rango_precio'] ) ) {
        update_post_meta( $post_id, '_restaurante_rango_precio', sanitize_text_field( $_POST['restaurante_rango_precio'] ) );
    }

    // Guardar calificación
    if ( isset( $_POST['restaurante_calificacion'] ) ) {
        update_post_meta( $post_id, '_restaurante_calificacion', sanitize_text_field( $_POST['restaurante_calificacion'] ) );
    }

    // Guardar horario
    if ( isset( $_POST['restaurante_horario'] ) ) {
        update_post_meta( $post_id, '_restaurante_horario', sanitize_textarea_field( $_POST['restaurante_horario'] ) );
    }

    // Guardar servicios
    if ( isset( $_POST['restaurante_servicios'] ) ) {
        $servicios = array_map( 'sanitize_text_field', $_POST['restaurante_servicios'] );
        update_post_meta( $post_id, '_restaurante_servicios', json_encode( $servicios ) );
    } else {
        update_post_meta( $post_id, '_restaurante_servicios', json_encode( array() ) );
    }

    // Guardar especialidades
    if ( isset( $_POST['restaurante_especialidades'] ) ) {
        update_post_meta( $post_id, '_restaurante_especialidades', sanitize_textarea_field( $_POST['restaurante_especialidades'] ) );
    }

    // Guardar teléfono
    if ( isset( $_POST['restaurante_telefono'] ) ) {
        update_post_meta( $post_id, '_restaurante_telefono', sanitize_text_field( $_POST['restaurante_telefono'] ) );
    }

    // Guardar email
    if ( isset( $_POST['restaurante_email'] ) ) {
        update_post_meta( $post_id, '_restaurante_email', sanitize_email( $_POST['restaurante_email'] ) );
    }

    // Guardar sitio web
    if ( isset( $_POST['restaurante_sitio_web'] ) ) {
        update_post_meta( $post_id, '_restaurante_sitio_web', esc_url_raw( $_POST['restaurante_sitio_web'] ) );
    }

    // Guardar dirección
    if ( isset( $_POST['restaurante_direccion'] ) ) {
        update_post_meta( $post_id, '_restaurante_direccion', sanitize_textarea_field( $_POST['restaurante_direccion'] ) );
    }

    // Guardar menú URL
    if ( isset( $_POST['restaurante_menu_url'] ) ) {
        update_post_meta( $post_id, '_restaurante_menu_url', esc_url_raw( $_POST['restaurante_menu_url'] ) );
    }

    // Guardar galería
    if ( isset( $_POST['restaurante_galeria'] ) ) {
        update_post_meta( $post_id, '_restaurante_galeria', sanitize_textarea_field( $_POST['restaurante_galeria'] ) );
    }
}
add_action( 'save_post', 'turismo_save_restaurante_meta' );

/**
 * 20. Navegación de posts mejorada con imágenes
 */
function turismo_post_navigation_with_images() {
    $prev_post = get_previous_post();
    $next_post = get_next_post();

    if (!$prev_post && !$next_post) {
        return;
    }
    ?>
    <nav class="post-navigation-modern">
        <div class="post-nav-container">

            <?php if ($prev_post) : ?>
                <div class="post-nav-item post-nav-prev">
                    <a href="<?php echo get_permalink($prev_post); ?>" class="post-nav-link">
                        <div class="post-nav-image">
                            <?php if (has_post_thumbnail($prev_post)) : ?>
                                <?php echo get_the_post_thumbnail($prev_post, 'medium'); ?>
                            <?php else : ?>
                                <div class="post-nav-no-image">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="post-nav-content">
                            <span class="post-nav-label">
                                <i class="fas fa-arrow-left"></i>
                                Anterior
                            </span>
                            <h4 class="post-nav-title"><?php echo get_the_title($prev_post); ?></h4>
                            <span class="post-nav-date">
                                <i class="far fa-calendar"></i>
                                <?php echo get_the_date('d M, Y', $prev_post); ?>
                            </span>
                        </div>
                    </a>
                </div>
            <?php else : ?>
                <div class="post-nav-item post-nav-prev post-nav-empty"></div>
            <?php endif; ?>

            <?php if ($next_post) : ?>
                <div class="post-nav-item post-nav-next">
                    <a href="<?php echo get_permalink($next_post); ?>" class="post-nav-link">
                        <div class="post-nav-content">
                            <span class="post-nav-label">
                                Siguiente
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <h4 class="post-nav-title"><?php echo get_the_title($next_post); ?></h4>
                            <span class="post-nav-date">
                                <i class="far fa-calendar"></i>
                                <?php echo get_the_date('d M, Y', $next_post); ?>
                            </span>
                        </div>
                        <div class="post-nav-image">
                            <?php if (has_post_thumbnail($next_post)) : ?>
                                <?php echo get_the_post_thumbnail($next_post, 'medium'); ?>
                            <?php else : ?>
                                <div class="post-nav-no-image">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php else : ?>
                <div class="post-nav-item post-nav-next post-nav-empty"></div>
            <?php endif; ?>

        </div>
    </nav>
    <?php
}

/**
 * 20. Custom Post Type: Destinos Turísticos
 */
function turismo_register_destinos_cpt() {
    $labels = array(
        'name'               => 'Destinos Turísticos',
        'singular_name'      => 'Destino',
        'menu_name'          => 'Destinos',
        'add_new'            => 'Añadir Destino',
        'add_new_item'       => 'Añadir Nuevo Destino',
        'edit_item'          => 'Editar Destino',
        'new_item'           => 'Nuevo Destino',
        'view_item'          => 'Ver Destino',
        'search_items'       => 'Buscar Destinos',
        'not_found'          => 'No se encontraron destinos',
        'not_found_in_trash' => 'No hay destinos en la papelera',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'destinos' ),
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-palmtree',
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'        => true,
        'taxonomies'          => array( 'categoria-destino' ),
    );

    register_post_type( 'destino', $args );
}
add_action( 'init', 'turismo_register_destinos_cpt' );

/**
 * 21. Taxonomía: Categorías de Destinos
 */
function turismo_register_destino_taxonomies() {
    $labels = array(
        'name'              => 'Categorías de Destinos',
        'singular_name'     => 'Categoría',
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
        'rewrite'           => array( 'slug' => 'categoria-destino' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'categoria-destino', array( 'destino' ), $args );
}
add_action( 'init', 'turismo_register_destino_taxonomies' );

/**
 * 22. Meta Boxes para Destinos Turísticos
 */
function turismo_add_destino_meta_boxes() {
    add_meta_box(
        'turismo_destino_details',
        'Detalles del Destino',
        'turismo_destino_details_callback',
        'destino',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'turismo_add_destino_meta_boxes' );

/**
 * 23. Custom Post Type: Atractivos Turísticos
 */
function turismo_register_atractivos_cpt() {
    $labels = array(
        'name'               => 'Atractivos Turísticos',
        'singular_name'      => 'Atractivo',
        'menu_name'          => 'Atractivos Turísticos',
        'add_new'            => 'Añadir Atractivo',
        'add_new_item'       => 'Añadir Nuevo Atractivo',
        'edit_item'          => 'Editar Atractivo',
        'new_item'           => 'Nuevo Atractivo',
        'view_item'          => 'Ver Atractivo',
        'search_items'       => 'Buscar Atractivos',
        'not_found'          => 'No se encontraron atractivos',
        'not_found_in_trash' => 'No hay atractivos en la papelera',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'atractivos' ),
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-location-alt',
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'        => true,
        'taxonomies'          => array( 'ubicacion-atractivo', 'tipo-atractivo' ),
    );

    register_post_type( 'atractivo', $args );
}
add_action( 'init', 'turismo_register_atractivos_cpt' );

/**
 * 24. Taxonomías: Ubicación y Tipo de Atractivo
 */
function turismo_register_atractivo_taxonomies() {
    // Taxonomía: Ubicación
    $ubicacion_labels = array(
        'name'              => 'Ubicaciones',
        'singular_name'     => 'Ubicación',
        'search_items'      => 'Buscar Ubicaciones',
        'all_items'         => 'Todas las Ubicaciones',
        'parent_item'       => 'Ubicación Padre',
        'parent_item_colon' => 'Ubicación Padre:',
        'edit_item'         => 'Editar Ubicación',
        'update_item'       => 'Actualizar Ubicación',
        'add_new_item'      => 'Añadir Nueva Ubicación',
        'new_item_name'     => 'Nuevo Nombre de Ubicación',
        'menu_name'         => 'Ubicaciones',
    );

    $ubicacion_args = array(
        'hierarchical'      => true,
        'labels'            => $ubicacion_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'ubicacion-atractivo' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'ubicacion-atractivo', array( 'atractivo' ), $ubicacion_args );

    // Taxonomía: Tipo de Atractivo
    $tipo_labels = array(
        'name'              => 'Tipos de Atractivo',
        'singular_name'     => 'Tipo',
        'search_items'      => 'Buscar Tipos',
        'all_items'         => 'Todos los Tipos',
        'parent_item'       => 'Tipo Padre',
        'parent_item_colon' => 'Tipo Padre:',
        'edit_item'         => 'Editar Tipo',
        'update_item'       => 'Actualizar Tipo',
        'add_new_item'      => 'Añadir Nuevo Tipo',
        'new_item_name'     => 'Nuevo Tipo de Atractivo',
        'menu_name'         => 'Tipos',
    );

    $tipo_args = array(
        'hierarchical'      => true,
        'labels'            => $tipo_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'tipo-atractivo' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'tipo-atractivo', array( 'atractivo' ), $tipo_args );
}
add_action( 'init', 'turismo_register_atractivo_taxonomies' );

/**
 * 25. Meta Boxes para Atractivos Turísticos
 */
function turismo_add_atractivo_meta_boxes() {
    add_meta_box(
        'turismo_atractivo_details',
        'Detalles del Atractivo',
        'turismo_atractivo_details_callback',
        'atractivo',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'turismo_add_atractivo_meta_boxes' );

function turismo_atractivo_details_callback( $post ) {
    wp_nonce_field( 'turismo_save_atractivo_details', 'turismo_atractivo_nonce' );

    $horario = get_post_meta( $post->ID, '_atractivo_horario', true );
    $precio_entrada = get_post_meta( $post->ID, '_atractivo_precio_entrada', true );
    $telefono = get_post_meta( $post->ID, '_atractivo_telefono', true );
    $sitio_web = get_post_meta( $post->ID, '_atractivo_sitio_web', true );
    $direccion = get_post_meta( $post->ID, '_atractivo_direccion', true );
    $galeria = get_post_meta( $post->ID, '_atractivo_galeria', true );
    $caracteristicas = get_post_meta( $post->ID, '_atractivo_caracteristicas', true );

    ?>
    <style>
        .atractivo-meta-field { margin-bottom: 20px; }
        .atractivo-meta-field label { display: block; font-weight: 600; margin-bottom: 5px; }
        .atractivo-meta-field input[type="text"],
        .atractivo-meta-field input[type="url"],
        .atractivo-meta-field textarea { width: 100%; padding: 8px; }
        .atractivo-meta-field textarea { min-height: 80px; }
        .atractivo-caracteristicas-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 10px; }
        .atractivo-caracteristicas-grid label { display: flex; align-items: center; gap: 5px; font-weight: normal; }
    </style>

    <div class="atractivo-meta-field">
        <label for="atractivo_precio_entrada">Precio de entrada:</label>
        <input type="text" id="atractivo_precio_entrada" name="atractivo_precio_entrada" value="<?php echo esc_attr( $precio_entrada ); ?>" placeholder="Ej: $100, Gratis, Entrada libre">
        <small style="color: #666;">Ejemplo: $100, Gratis, Entrada libre, etc.</small>
    </div>

    <div class="atractivo-meta-field">
        <label for="atractivo_horario">Horario de atención:</label>
        <textarea id="atractivo_horario" name="atractivo_horario" placeholder="Ej: Lun-Dom: 9:00 AM - 6:00 PM"><?php echo esc_textarea( $horario ); ?></textarea>
        <small style="color: #666;">Describe el horario de atención del atractivo</small>
    </div>

    <div class="atractivo-meta-field">
        <label>Características:</label>
        <div class="atractivo-caracteristicas-grid">
            <?php
            $caracteristicas_disponibles = array(
                'estacionamiento' => 'Estacionamiento',
                'guias_turisticos' => 'Guías turísticos',
                'accesible' => 'Accesible',
                'restaurante' => 'Restaurante/Cafetería',
                'tienda_souvenirs' => 'Tienda de souvenirs',
                'banos' => 'Baños',
                'area_picnic' => 'Área de picnic',
                'wifi' => 'WiFi',
                'pet_friendly' => 'Mascotas permitidas',
                'fotografia_permitida' => 'Fotografía permitida',
                'aire_libre' => 'Aire libre',
                'techado' => 'Techado/Interior',
            );

            $caracteristicas_seleccionadas = $caracteristicas ? json_decode( $caracteristicas, true ) : array();

            foreach ( $caracteristicas_disponibles as $key => $label ) {
                $checked = in_array( $key, (array) $caracteristicas_seleccionadas ) ? 'checked' : '';
                echo '<label><input type="checkbox" name="atractivo_caracteristicas[]" value="' . esc_attr( $key ) . '" ' . $checked . '> ' . esc_html( $label ) . '</label>';
            }
            ?>
        </div>
    </div>

    <div class="atractivo-meta-field">
        <label for="atractivo_telefono">Teléfono:</label>
        <input type="text" id="atractivo_telefono" name="atractivo_telefono" value="<?php echo esc_attr( $telefono ); ?>" placeholder="Ej: +52 123 456 7890">
    </div>

    <div class="atractivo-meta-field">
        <label for="atractivo_sitio_web">Sitio Web:</label>
        <input type="url" id="atractivo_sitio_web" name="atractivo_sitio_web" value="<?php echo esc_attr( $sitio_web ); ?>" placeholder="https://www.atractivo.com">
    </div>

    <div class="atractivo-meta-field">
        <label for="atractivo_direccion">Dirección completa:</label>
        <textarea id="atractivo_direccion" name="atractivo_direccion" placeholder="Calle, número, colonia, ciudad, estado"><?php echo esc_textarea( $direccion ); ?></textarea>
    </div>

    <div class="atractivo-meta-field">
        <label for="atractivo_galeria">Galería de imágenes (URLs separadas por comas):</label>
        <textarea id="atractivo_galeria" name="atractivo_galeria" placeholder="https://ejemplo.com/imagen1.jpg, https://ejemplo.com/imagen2.jpg"><?php echo esc_textarea( $galeria ); ?></textarea>
        <small style="color: #666;">Ingresa las URLs de las imágenes separadas por comas. Puedes subir imágenes a la biblioteca de medios y copiar sus URLs.</small>
    </div>
    <?php
}

function turismo_save_atractivo_meta( $post_id ) {
    if ( ! isset( $_POST['turismo_atractivo_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['turismo_atractivo_nonce'], 'turismo_save_atractivo_details' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Guardar precio de entrada
    if ( isset( $_POST['atractivo_precio_entrada'] ) ) {
        update_post_meta( $post_id, '_atractivo_precio_entrada', sanitize_text_field( $_POST['atractivo_precio_entrada'] ) );
    }

    // Guardar horario
    if ( isset( $_POST['atractivo_horario'] ) ) {
        update_post_meta( $post_id, '_atractivo_horario', sanitize_textarea_field( $_POST['atractivo_horario'] ) );
    }

    // Guardar características
    if ( isset( $_POST['atractivo_caracteristicas'] ) ) {
        $caracteristicas = array_map( 'sanitize_text_field', $_POST['atractivo_caracteristicas'] );
        update_post_meta( $post_id, '_atractivo_caracteristicas', json_encode( $caracteristicas ) );
    } else {
        update_post_meta( $post_id, '_atractivo_caracteristicas', json_encode( array() ) );
    }

    // Guardar teléfono
    if ( isset( $_POST['atractivo_telefono'] ) ) {
        update_post_meta( $post_id, '_atractivo_telefono', sanitize_text_field( $_POST['atractivo_telefono'] ) );
    }

    // Guardar sitio web
    if ( isset( $_POST['atractivo_sitio_web'] ) ) {
        update_post_meta( $post_id, '_atractivo_sitio_web', esc_url_raw( $_POST['atractivo_sitio_web'] ) );
    }

    // Guardar dirección
    if ( isset( $_POST['atractivo_direccion'] ) ) {
        update_post_meta( $post_id, '_atractivo_direccion', sanitize_textarea_field( $_POST['atractivo_direccion'] ) );
    }

    // Guardar galería
    if ( isset( $_POST['atractivo_galeria'] ) ) {
        update_post_meta( $post_id, '_atractivo_galeria', sanitize_textarea_field( $_POST['atractivo_galeria'] ) );
    }
}
add_action( 'save_post', 'turismo_save_atractivo_meta' );

function turismo_destino_details_callback( $post ) {
    wp_nonce_field( 'turismo_save_destino_details', 'turismo_destino_nonce' );

    $ubicacion = get_post_meta( $post->ID, '_destino_ubicacion', true );
    $destacado = get_post_meta( $post->ID, '_destino_destacado', true );
    $descripcion_corta = get_post_meta( $post->ID, '_destino_descripcion_corta', true );
    $cta_text = get_post_meta( $post->ID, '_destino_cta_text', true );
    $cta_url = get_post_meta( $post->ID, '_destino_cta_url', true );
    $galeria = get_post_meta( $post->ID, '_destino_galeria', true );

    ?>
    <style>
        .destino-meta-field { margin-bottom: 20px; }
        .destino-meta-field label { display: block; font-weight: 600; margin-bottom: 5px; }
        .destino-meta-field input[type="text"],
        .destino-meta-field input[type="url"],
        .destino-meta-field textarea { width: 100%; padding: 8px; }
        .destino-meta-field textarea { min-height: 80px; }
        .destino-meta-checkbox { display: flex; align-items: center; gap: 10px; }
        .destino-meta-checkbox input[type="checkbox"] { width: auto; }
    </style>

    <div class="destino-meta-field">
        <label for="destino_ubicacion">Ubicación:</label>
        <input type="text" id="destino_ubicacion" name="destino_ubicacion" value="<?php echo esc_attr( $ubicacion ); ?>" placeholder="Ej: Riviera Maya, Quintana Roo">
        <small style="color: #666;">Ciudad, estado o región del destino</small>
    </div>

    <div class="destino-meta-field destino-meta-checkbox">
        <input type="checkbox" id="destino_destacado" name="destino_destacado" value="1" <?php checked( $destacado, '1' ); ?>>
        <label for="destino_destacado">Mostrar en el slider principal (destacado)</label>
    </div>

    <div class="destino-meta-field">
        <label for="destino_descripcion_corta">Descripción corta para slider:</label>
        <textarea id="destino_descripcion_corta" name="destino_descripcion_corta" placeholder="Frase atractiva de 10-15 palabras máximo"><?php echo esc_textarea( $descripcion_corta ); ?></textarea>
        <small style="color: #666;">Texto breve que aparecerá en el slider (máx. 100 caracteres recomendado)</small>
    </div>

    <div class="destino-meta-field">
        <label for="destino_cta_text">Texto del botón (CTA):</label>
        <input type="text" id="destino_cta_text" name="destino_cta_text" value="<?php echo esc_attr( $cta_text ); ?>" placeholder="Ej: Explorar Destino, Descubrir Más">
        <small style="color: #666;">Deja vacío para usar "Explorar Destino" por defecto</small>
    </div>

    <div class="destino-meta-field">
        <label for="destino_cta_url">URL del botón (opcional):</label>
        <input type="url" id="destino_cta_url" name="destino_cta_url" value="<?php echo esc_attr( $cta_url ); ?>" placeholder="https://ejemplo.com/tours">
        <small style="color: #666;">Deja vacío para usar el enlace del destino por defecto</small>
    </div>

    <div class="destino-meta-field">
        <label for="destino_galeria">Galería de imágenes (URLs separadas por comas):</label>
        <textarea id="destino_galeria" name="destino_galeria" placeholder="https://ejemplo.com/imagen1.jpg, https://ejemplo.com/imagen2.jpg"><?php echo esc_textarea( $galeria ); ?></textarea>
        <small style="color: #666;">Ingresa las URLs de las imágenes separadas por comas</small>
    </div>
    <?php
}

function turismo_save_destino_meta( $post_id ) {
    if ( ! isset( $_POST['turismo_destino_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['turismo_destino_nonce'], 'turismo_save_destino_details' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Guardar ubicación
    if ( isset( $_POST['destino_ubicacion'] ) ) {
        update_post_meta( $post_id, '_destino_ubicacion', sanitize_text_field( $_POST['destino_ubicacion'] ) );
    }

    // Guardar destacado
    if ( isset( $_POST['destino_destacado'] ) ) {
        update_post_meta( $post_id, '_destino_destacado', '1' );
    } else {
        update_post_meta( $post_id, '_destino_destacado', '0' );
    }

    // Guardar descripción corta
    if ( isset( $_POST['destino_descripcion_corta'] ) ) {
        update_post_meta( $post_id, '_destino_descripcion_corta', sanitize_textarea_field( $_POST['destino_descripcion_corta'] ) );
    }

    // Guardar CTA text
    if ( isset( $_POST['destino_cta_text'] ) ) {
        update_post_meta( $post_id, '_destino_cta_text', sanitize_text_field( $_POST['destino_cta_text'] ) );
    }

    // Guardar CTA URL
    if ( isset( $_POST['destino_cta_url'] ) ) {
        update_post_meta( $post_id, '_destino_cta_url', esc_url_raw( $_POST['destino_cta_url'] ) );
    }

    // Guardar galería
    if ( isset( $_POST['destino_galeria'] ) ) {
        update_post_meta( $post_id, '_destino_galeria', sanitize_textarea_field( $_POST['destino_galeria'] ) );
    }
}
add_action( 'save_post', 'turismo_save_destino_meta' );

/**
 * 26. Procesar formulario de contacto via AJAX
 */
function turismo_enviar_contacto() {
    // Verificar nonce
    if ( ! isset( $_POST['contacto_nonce'] ) || ! wp_verify_nonce( $_POST['contacto_nonce'], 'turismo_contacto_nonce' ) ) {
        wp_send_json_error( array(
            'message' => 'Error de seguridad. Por favor recarga la página e intenta nuevamente.'
        ));
    }

    // Validar campos requeridos
    $nombre = isset( $_POST['contacto_nombre'] ) ? sanitize_text_field( $_POST['contacto_nombre'] ) : '';
    $email = isset( $_POST['contacto_email'] ) ? sanitize_email( $_POST['contacto_email'] ) : '';
    $asunto = isset( $_POST['contacto_asunto'] ) ? sanitize_text_field( $_POST['contacto_asunto'] ) : '';
    $mensaje = isset( $_POST['contacto_mensaje'] ) ? sanitize_textarea_field( $_POST['contacto_mensaje'] ) : '';
    $telefono = isset( $_POST['contacto_telefono'] ) ? sanitize_text_field( $_POST['contacto_telefono'] ) : '';

    // Validaciones
    if ( empty( $nombre ) || empty( $email ) || empty( $asunto ) || empty( $mensaje ) ) {
        wp_send_json_error( array(
            'message' => 'Por favor completa todos los campos requeridos.'
        ));
    }

    if ( ! is_email( $email ) ) {
        wp_send_json_error( array(
            'message' => 'Por favor ingresa un email válido.'
        ));
    }

    // Preparar el correo
    $admin_email = get_theme_mod( 'turismo_contacto_email', get_option( 'admin_email' ) );

    $email_subject = 'Nuevo mensaje de contacto: ' . $asunto;

    $email_body = "Has recibido un nuevo mensaje desde el formulario de contacto.\n\n";
    $email_body .= "Nombre: {$nombre}\n";
    $email_body .= "Email: {$email}\n";
    if ( ! empty( $telefono ) ) {
        $email_body .= "Teléfono: {$telefono}\n";
    }
    $email_body .= "Asunto: {$asunto}\n\n";
    $email_body .= "Mensaje:\n{$mensaje}\n\n";
    $email_body .= "---\n";
    $email_body .= "Este mensaje fue enviado desde: " . get_bloginfo( 'name' ) . " (" . home_url() . ")";

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . get_bloginfo( 'name' ) . ' <' . $admin_email . '>',
        'Reply-To: ' . $nombre . ' <' . $email . '>'
    );

    // Enviar el correo
    $enviado = wp_mail( $admin_email, $email_subject, $email_body, $headers );

    if ( $enviado ) {
        // Enviar correo de confirmación al usuario
        $user_subject = 'Gracias por contactarnos - ' . get_bloginfo( 'name' );
        $user_body = "Hola {$nombre},\n\n";
        $user_body .= "Gracias por ponerte en contacto con nosotros. Hemos recibido tu mensaje y te responderemos lo antes posible.\n\n";
        $user_body .= "Resumen de tu mensaje:\n";
        $user_body .= "Asunto: {$asunto}\n";
        $user_body .= "Mensaje: {$mensaje}\n\n";
        $user_body .= "Saludos,\n";
        $user_body .= get_bloginfo( 'name' );

        $user_headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . get_bloginfo( 'name' ) . ' <' . $admin_email . '>'
        );

        wp_mail( $email, $user_subject, $user_body, $user_headers );

        wp_send_json_success( array(
            'message' => '¡Mensaje enviado exitosamente! Te responderemos pronto.'
        ));
    } else {
        wp_send_json_error( array(
            'message' => 'Hubo un error al enviar el mensaje. Por favor intenta nuevamente o contáctanos directamente.'
        ));
    }
}
add_action( 'wp_ajax_turismo_enviar_contacto', 'turismo_enviar_contacto' );
add_action( 'wp_ajax_nopriv_turismo_enviar_contacto', 'turismo_enviar_contacto' );

/**
 * 27. Configurar SMTP para envío de correos
 */
function turismo_configurar_smtp( $phpmailer ) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = 'mail.choix.gob.mx';
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Port       = 465;
    $phpmailer->Username   = 'turismo@choix.gob.mx';
    $phpmailer->Password   = 'turismo2025';
    $phpmailer->SMTPSecure = 'ssl';
    $phpmailer->From       = 'turismo@choix.gob.mx';
    $phpmailer->FromName   = get_bloginfo( 'name' );

    // Habilitar debugging (comentar estas líneas en producción)
    $phpmailer->SMTPDebug = 2; // 0 = off, 1 = client, 2 = client and server
    $phpmailer->Debugoutput = function($str, $level) {
        error_log("SMTP Debug level $level: $str");
    };
}
add_action( 'phpmailer_init', 'turismo_configurar_smtp' );

/**
 * 28. Capturar errores de email
 */
function turismo_log_email_errors( $wp_error ) {
    error_log( 'Error al enviar email: ' . $wp_error->get_error_message() );
}
add_action( 'wp_mail_failed', 'turismo_log_email_errors' );
