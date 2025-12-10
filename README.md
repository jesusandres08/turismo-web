# Turismo Theme - WordPress Custom Theme

Tema personalizado de WordPress para sitio de turismo, basado en el diseño de Portal Choix Django.

## Características

- ✅ **Slider Hero** - Slider de posts destacados con layout 60%/40%
- ✅ **Galería de Videos** - Reproductor de YouTube con lista de videos de playlist
- ✅ **Sistema de Videos** - Integración con playlists de YouTube sin API key
- ✅ **Diseño Responsive** - Optimizado para móvil, tablet y desktop
- ✅ **Color Scheme** - Colores corporativos (#003F87 + #f2c300)
- ✅ **Font Awesome** - Iconos integrados
- ✅ **Google Fonts** - Tipografía Inter

## Estructura del Home

1. **Header** - Logo, menú principal, búsqueda
2. **Slider** - 5 posts destacados con imagen y extracto
3. **Reproductor de Videos** - Player + lista lateral de videos
4. **Footer** - 4 columnas con degradado

## Instalación

1. Copia la carpeta `turismo-theme` a `/wp-content/themes/`
2. Ve a WordPress Admin → Apariencia → Temas
3. Activa "Turismo Custom Theme"
4. Configura el logo en Apariencia → Personalizar

## Configuración de Videos

### Videos en Home

Archivo: `template-parts/videos-home.php` (línea 13)

```php
$playlist_destacada = array(
    'nombre'      => 'Videos Destacados',
    'playlist_id' => 'TU_PLAYLIST_ID_AQUI',
    'descripcion' => 'Descripción',
    'icono'       => 'fa-video',
);
```

### Galería de Videos Completa

1. Crea una página nueva
2. Selecciona plantilla "Galería de Videos"
3. Edita `page-videos.php` (línea 21) para configurar playlists

## Obtener Playlist ID de YouTube

1. Abre tu playlist en YouTube
2. Copia la URL: `https://www.youtube.com/playlist?list=PLxxxxxxxx`
3. El ID es todo después de `list=`

## Archivos Principales

```
turismo-theme/
├── style.css                    # Estilos base
├── functions.php                # Funciones del tema
├── index.php                    # Template principal
├── header.php                   # Header
├── footer.php                   # Footer
├── sidebar.php                  # Sidebar
├── css/
│   └── main.css                 # Estilos principales
├── js/
│   └── main.js                  # JavaScript
└── template-parts/
    ├── slider-hero.php          # Slider de posts
    ├── videos-home.php          # Videos para home
    └── categorias-home.php      # Categorías (opcional)
```

## Personalización

### Colores

Archivo: `css/main.css` (líneas 7-55)

```css
:root {
    --color-primary: #003F87;      /* Azul principal */
    --color-secondary: #f2c300;     /* Amarillo */
}
```

### Ancho del Contenido

- Slider: 1200px max-width
- Videos: 1200px max-width
- Categorías: 1200px max-width

### Slider

- Altura: 450px
- Posts mostrados: 5 más recientes
- Autoplay: 5 segundos

## Tecnologías

- WordPress 5.0+
- PHP 7.4+
- HTML5
- CSS3 (Custom Properties)
- JavaScript (ES6)
- Font Awesome 6.4.0
- Google Fonts (Inter)

## Navegadores Soportados

- Chrome (últimas 2 versiones)
- Firefox (últimas 2 versiones)
- Safari (últimas 2 versiones)
- Edge (últimas 2 versiones)

## Créditos

- Diseño basado en: Portal Choix (Django)
- Desarrollado para: Turismo Project
- Año: 2025

## Licencia

Uso privado. Todos los derechos reservados.

## Soporte

Para configuración y soporte, consulta los archivos:
- `GALERIA-VIDEOS-LISTA.txt`
- `HOME-PORTAL-CHOIX.txt`
- `INSTRUCCIONES-VIDEOS.txt`
