/**
 * Turismo Custom Theme - JavaScript Principal
 * Basado en portal_choix
 */

(function() {
    'use strict';

    // Esperar a que el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Turismo Custom Theme cargado');

        // Inicializar funciones
        initMobileMenu();
        initScrollToTop();
        initSmoothScroll();
        initLazyImages();
        initAnimations();
    });

    /**
     * Menú móvil mejorado
     */
    function initMobileMenu() {
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuOverlay = document.querySelector('.menu-overlay');

        if (menuBtn && mobileMenu && menuOverlay) {
            menuBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Toggle active class
                const isActive = mobileMenu.classList.contains('active');

                if (isActive) {
                    mobileMenu.classList.remove('active');
                    menuOverlay.classList.remove('active');
                    menuBtn.setAttribute('aria-expanded', 'false');
                    menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                    document.body.style.overflow = '';
                } else {
                    mobileMenu.classList.add('active');
                    menuOverlay.classList.add('active');
                    menuBtn.setAttribute('aria-expanded', 'true');
                    menuBtn.innerHTML = '<i class="fas fa-times"></i>';
                    document.body.style.overflow = 'hidden';
                }
            });

            // Cerrar menú al hacer clic en un enlace
            const navLinks = mobileMenu.querySelectorAll('a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.remove('active');
                    menuOverlay.classList.remove('active');
                    menuBtn.setAttribute('aria-expanded', 'false');
                    menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                    document.body.style.overflow = '';
                });
            });

            // Cerrar menú al hacer clic en el overlay
            menuOverlay.addEventListener('click', function() {
                mobileMenu.classList.remove('active');
                menuOverlay.classList.remove('active');
                menuBtn.setAttribute('aria-expanded', 'false');
                menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                document.body.style.overflow = '';
            });
        }
    }

    /**
     * Botón volver arriba
     */
    function initScrollToTop() {
        const btnVolverArriba = document.getElementById('btn-volver-arriba');

        if (btnVolverArriba) {
            // Mostrar/ocultar botón según scroll
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    btnVolverArriba.classList.add('show');
                } else {
                    btnVolverArriba.classList.remove('show');
                }
            });

            // Hacer scroll al inicio
            btnVolverArriba.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    }

    /**
     * Scroll suave para anclas
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');

                // Ignorar enlaces vacíos
                if (href === '#' || href === '#0') {
                    e.preventDefault();
                    return;
                }

                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();

                    // Calcular offset para header fijo
                    const headerHeight = document.querySelector('.site-header')?.offsetHeight || 0;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    /**
     * Lazy loading para imágenes
     */
    function initLazyImages() {
        if ('IntersectionObserver' in window) {
            const images = document.querySelectorAll('img[data-lazy]');

            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.lazy;
                        img.removeAttribute('data-lazy');
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            images.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback para navegadores sin soporte
            const images = document.querySelectorAll('img[data-lazy]');
            images.forEach(img => {
                img.src = img.dataset.lazy;
                img.removeAttribute('data-lazy');
            });
        }
    }

    /**
     * Animaciones al hacer scroll (opcional)
     */
    function initAnimations() {
        if ('IntersectionObserver' in window) {
            const elements = document.querySelectorAll('.post-wrapper, .widget, .page-title');

            const animateObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '0';
                        entry.target.style.transform = 'translateY(20px)';

                        setTimeout(() => {
                            entry.target.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }, 100);

                        animateObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            elements.forEach(el => animateObserver.observe(el));
        }
    }

    /**
     * Destacar item del menú actual
     */
    function highlightCurrentMenuItem() {
        const currentPath = window.location.pathname;
        const menuLinks = document.querySelectorAll('.nav-menu a');

        menuLinks.forEach(link => {
            const linkPath = new URL(link.href).pathname;
            if (linkPath === currentPath) {
                link.parentElement.classList.add('current-menu-item');
            }
        });
    }

    // Ejecutar al cargar
    highlightCurrentMenuItem();

    /**
     * Slider simple (si necesitas uno básico)
     */
    window.initSimpleSlider = function(sliderId, options = {}) {
        const slider = document.getElementById(sliderId);
        if (!slider) return;

        const slides = slider.querySelectorAll('.slide');
        if (slides.length === 0) return;

        let currentSlide = 0;
        const autoplay = options.autoplay !== false;
        const interval = options.interval || 5000;
        let autoplayTimer;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.display = i === index ? 'block' : 'none';
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        function startAutoplay() {
            if (autoplay) {
                autoplayTimer = setInterval(nextSlide, interval);
            }
        }

        function stopAutoplay() {
            clearInterval(autoplayTimer);
        }

        // Botones prev/next si existen
        const prevBtn = slider.querySelector('.slider-prev');
        const nextBtn = slider.querySelector('.slider-next');

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                stopAutoplay();
                prevSlide();
                startAutoplay();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                stopAutoplay();
                nextSlide();
                startAutoplay();
            });
        }

        // Pausar en hover
        slider.addEventListener('mouseenter', stopAutoplay);
        slider.addEventListener('mouseleave', startAutoplay);

        // Iniciar
        showSlide(currentSlide);
        startAutoplay();

        return {
            next: nextSlide,
            prev: prevSlide,
            goTo: (index) => {
                currentSlide = index;
                showSlide(currentSlide);
            }
        };
    };

    /**
     * Función para AJAX personalizado (compatible con WordPress)
     */
    window.turismoAjax = function(data, callback) {
        if (typeof jQuery !== 'undefined' && typeof turismoData !== 'undefined') {
            jQuery.ajax({
                url: turismoData.ajax_url,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (callback) {
                        callback(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });
        } else {
            console.error('jQuery o turismoData no están definidos');
        }
    };

    /**
     * Utilidad: Debounce
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Optimizar scroll events
     */
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                // Aquí puedes agregar funciones que dependan del scroll
                ticking = false;
            });
            ticking = true;
        }
    });

    /**
     * Slider 2 Hero - Autoplay con controles
     */
    function initSlider2() {
        const track = document.querySelector('.slider2-track');
        const slides = document.querySelectorAll('.slider2-slide');
        const prevBtn = document.querySelector('.slider2-arrow.prev');
        const nextBtn = document.querySelector('.slider2-arrow.next');
        const dots = document.querySelectorAll('.slider2-dot');

        if (!track || slides.length === 0) return;

        let currentIndex = 0;
        let autoplayInterval;
        const autoplayDelay = 5000; // 5 segundos

        function goToSlide(index) {
            currentIndex = index;
            const offset = -currentIndex * 100;
            track.style.transform = `translateX(${offset}%)`;

            // Update dots
            dots.forEach((dot, i) => {
                if (i === currentIndex) {
                    dot.classList.add('active');
                    dot.setAttribute('aria-selected', 'true');
                } else {
                    dot.classList.remove('active');
                    dot.setAttribute('aria-selected', 'false');
                }
            });
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % slides.length;
            goToSlide(currentIndex);
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + slides.length) % slides.length;
            goToSlide(currentIndex);
        }

        function startAutoplay() {
            autoplayInterval = setInterval(nextSlide, autoplayDelay);
        }

        function stopAutoplay() {
            clearInterval(autoplayInterval);
        }

        // Event listeners para botones
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                stopAutoplay();
                prevSlide();
                startAutoplay();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                stopAutoplay();
                nextSlide();
                startAutoplay();
            });
        }

        // Event listeners para dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                stopAutoplay();
                goToSlide(index);
                startAutoplay();
            });
        });

        // Pausar autoplay en hover
        const sliderWrapper = document.querySelector('.slider2-wrapper');
        if (sliderWrapper) {
            sliderWrapper.addEventListener('mouseenter', stopAutoplay);
            sliderWrapper.addEventListener('mouseleave', startAutoplay);
        }

        // Soporte para teclado (accesibilidad)
        document.addEventListener('keydown', (e) => {
            if (!sliderWrapper) return;

            // Solo responder si el slider es visible
            const rect = sliderWrapper.getBoundingClientRect();
            const isVisible = rect.top < window.innerHeight && rect.bottom >= 0;

            if (isVisible) {
                if (e.key === 'ArrowLeft') {
                    stopAutoplay();
                    prevSlide();
                    startAutoplay();
                } else if (e.key === 'ArrowRight') {
                    stopAutoplay();
                    nextSlide();
                    startAutoplay();
                }
            }
        });

        // Soporte para swipe en móvil
        let touchStartX = 0;
        let touchEndX = 0;

        if (sliderWrapper) {
            sliderWrapper.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            sliderWrapper.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, { passive: true });
        }

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                stopAutoplay();
                if (diff > 0) {
                    // Swipe left - next slide
                    nextSlide();
                } else {
                    // Swipe right - prev slide
                    prevSlide();
                }
                startAutoplay();
            }
        }

        // Iniciar autoplay
        startAutoplay();

        console.log('✅ Slider2 inicializado con', slides.length, 'slides');
    }

    // Inicializar slider si existe
    if (document.querySelector('.slider2-section')) {
        initSlider2();
    }

    /**
     * Paginación AJAX para noticias
     */
    function initNoticiasPagination() {
        const noticiasList = document.getElementById('noticias-lista');
        const prevBtn = document.querySelector('.noticias-prev');
        const nextBtn = document.querySelector('.noticias-next');

        if (!noticiasList || !prevBtn || !nextBtn) return;

        let currentPage = parseInt(noticiasList.dataset.currentPage) || 1;
        let maxPages = parseInt(noticiasList.dataset.maxPages) || 1;
        let isLoading = false;

        function updateButtons() {
            if (currentPage <= 1) {
                prevBtn.classList.add('disabled');
                prevBtn.disabled = true;
            } else {
                prevBtn.classList.remove('disabled');
                prevBtn.disabled = false;
            }

            if (currentPage >= maxPages) {
                nextBtn.classList.add('disabled');
                nextBtn.disabled = true;
            } else {
                nextBtn.classList.remove('disabled');
                nextBtn.disabled = false;
            }
        }

        function loadNoticias(page) {
            if (isLoading) return;

            isLoading = true;
            prevBtn.disabled = true;
            nextBtn.disabled = true;

            // Agregar clase de carga
            noticiasList.style.opacity = '0.5';

            jQuery.ajax({
                url: turismoData.ajax_url,
                type: 'POST',
                data: {
                    action: 'load_noticias',
                    paged: page
                },
                success: function(response) {
                    if (response.success) {
                        // Actualizar contenido
                        noticiasList.innerHTML = response.data.html;

                        // Actualizar página actual
                        currentPage = response.data.current_page;
                        maxPages = response.data.max_pages;

                        // Actualizar data attributes
                        noticiasList.dataset.currentPage = currentPage;
                        noticiasList.dataset.maxPages = maxPages;

                        // Actualizar botones
                        updateButtons();

                        // Restaurar opacidad
                        noticiasList.style.opacity = '1';

                        // Scroll suave a la sección de noticias
                        const noticiasSection = document.querySelector('.noticias-home-section');
                        if (noticiasSection) {
                            const headerHeight = document.querySelector('.site-header')?.offsetHeight || 0;
                            const targetPosition = noticiasSection.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;

                            window.scrollTo({
                                top: targetPosition,
                                behavior: 'smooth'
                            });
                        }
                    }
                },
                error: function() {
                    console.error('Error al cargar noticias');
                    noticiasList.style.opacity = '1';
                },
                complete: function() {
                    isLoading = false;
                }
            });
        }

        // Event listeners
        prevBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                loadNoticias(currentPage - 1);
            }
        });

        nextBtn.addEventListener('click', function() {
            if (currentPage < maxPages) {
                loadNoticias(currentPage + 1);
            }
        });

        console.log('✅ Paginación de noticias inicializada');
    }

    // Inicializar paginación de noticias si existe
    if (document.getElementById('noticias-lista')) {
        initNoticiasPagination();
    }

    /**
     * Galería de Videos - Reproductor con Playlist
     */
    function initVideoGallery() {
        const playlistItems = document.querySelectorAll('.playlist-item');
        const mainPlayer = document.getElementById('main-video-player');
        const currentTitle = document.getElementById('current-video-title');
        const currentDescription = document.getElementById('current-video-description');

        if (!playlistItems.length || !mainPlayer) return;

        playlistItems.forEach(item => {
            item.addEventListener('click', function() {
                const videoId = this.getAttribute('data-video-id');
                const videoTitle = this.getAttribute('data-video-title');
                const videoExcerpt = this.getAttribute('data-video-excerpt');
                const videoCategory = this.getAttribute('data-video-category');
                const videoDate = this.getAttribute('data-video-date');
                const videoAuthor = this.getAttribute('data-video-author');

                // Cambiar video en el reproductor
                mainPlayer.src = `https://www.youtube.com/embed/${videoId}?rel=0&autoplay=1`;

                // Actualizar información
                if (currentTitle) {
                    currentTitle.textContent = videoTitle;
                }

                if (currentDescription) {
                    currentDescription.textContent = videoExcerpt;
                }

                // Actualizar categoría si existe
                const categoryEl = document.querySelector('.player-category');
                if (categoryEl && videoCategory) {
                    categoryEl.innerHTML = `<i class="fas fa-tag"></i> ${videoCategory}`;
                }

                // Actualizar meta
                const metaEl = document.querySelector('.player-meta');
                if (metaEl) {
                    metaEl.innerHTML = `
                        <span><i class="fas fa-calendar-alt"></i> ${videoDate}</span>
                        <span><i class="fas fa-user"></i> ${videoAuthor}</span>
                    `;
                }

                // Actualizar botón de modal
                const modalBtn = document.querySelector('.modal-btn');
                if (modalBtn) {
                    modalBtn.setAttribute('onclick', `openVideoModal('${videoId}')`);
                }

                // Marcar como activo
                playlistItems.forEach(i => i.classList.remove('active'));
                this.classList.add('active');

                // Scroll al inicio del reproductor en móvil
                if (window.innerWidth <= 768) {
                    const playerContainer = document.querySelector('.player-container');
                    if (playerContainer) {
                        playerContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });

        console.log('✅ Galería de videos inicializada con', playlistItems.length, 'videos');
    }

    // Inicializar galería de videos si existe
    if (document.querySelector('.video-player-card')) {
        initVideoGallery();
    }

    /**
     * Console log de bienvenida
     */
    console.log('%c🎨 Turismo Custom Theme ', 'background: #003F87; color: #f2c300; padding: 8px 16px; border-radius: 4px; font-weight: bold;');
    console.log('%cDesarrollado con ❤️ para ofrecer la mejor experiencia', 'color: #666; font-style: italic;');

})();

/**
 * Función para copiar enlace al portapapeles
 * Función global accesible desde HTML
 */
function copyToClipboard(url) {
    // Método moderno usando Clipboard API
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(function() {
            showCopyFeedback('¡Enlace copiado!', true);
        }).catch(function(err) {
            console.error('Error al copiar:', err);
            fallbackCopyToClipboard(url);
        });
    } else {
        // Fallback para navegadores antiguos
        fallbackCopyToClipboard(url);
    }
}

/**
 * Método fallback para copiar al portapapeles
 */
function fallbackCopyToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.top = '0';
    textArea.style.left = '0';
    textArea.style.width = '2em';
    textArea.style.height = '2em';
    textArea.style.padding = '0';
    textArea.style.border = 'none';
    textArea.style.outline = 'none';
    textArea.style.boxShadow = 'none';
    textArea.style.background = 'transparent';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        const successful = document.execCommand('copy');
        showCopyFeedback(successful ? '¡Enlace copiado!' : 'Error al copiar', successful);
    } catch (err) {
        console.error('Error al copiar:', err);
        showCopyFeedback('Error al copiar', false);
    }

    document.body.removeChild(textArea);
}

/**
 * Mostrar feedback visual al copiar
 */
function showCopyFeedback(message, success) {
    // Crear elemento de feedback
    const feedback = document.createElement('div');
    feedback.className = 'copy-feedback' + (success ? ' success' : ' error');
    feedback.innerHTML = `
        <i class="fas fa-${success ? 'check' : 'times'}-circle"></i>
        <span>${message}</span>
    `;

    document.body.appendChild(feedback);

    // Animar entrada
    setTimeout(() => {
        feedback.classList.add('show');
    }, 10);

    // Remover después de 3 segundos
    setTimeout(() => {
        feedback.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(feedback);
        }, 300);
    }, 3000);
}

/**
 * Funciones globales para el modal de video
 * (Fuera del IIFE para ser accesibles desde HTML)
 */
function openVideoModal(videoId) {
    const modal = document.getElementById('video-modal');
    const modalPlayer = document.getElementById('modal-video-player');

    if (modal && modalPlayer) {
        modalPlayer.src = `https://www.youtube.com/embed/${videoId}?rel=0&autoplay=1`;
        modal.classList.add('active');

        // Prevenir scroll del body
        document.body.style.overflow = 'hidden';

        // Cerrar con tecla ESC
        document.addEventListener('keydown', handleModalEscape);
    }
}

function closeVideoModal() {
    const modal = document.getElementById('video-modal');
    const modalPlayer = document.getElementById('modal-video-player');

    if (modal && modalPlayer) {
        modal.classList.remove('active');
        modalPlayer.src = '';

        // Restaurar scroll del body
        document.body.style.overflow = '';

        // Remover listener de ESC
        document.removeEventListener('keydown', handleModalEscape);
    }
}

function handleModalEscape(e) {
    if (e.key === 'Escape') {
        closeVideoModal();
    }
}

// Cerrar modal al hacer clic fuera del video
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('video-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeVideoModal();
                closePlaylistModal();
            }
        });
    }
});

/**
 * Funciones para modal de playlists
 */
function openPlaylistModal(playlistId) {
    const modal = document.getElementById('video-modal');
    const modalPlayer = document.getElementById('modal-youtube-player');

    if (modal && modalPlayer) {
        modalPlayer.src = `https://www.youtube.com/embed/videoseries?list=${playlistId}&rel=0&autoplay=1`;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        document.addEventListener('keydown', handleModalEscape);
    }
}

function closePlaylistModal() {
    const modal = document.getElementById('video-modal');
    const modalPlayer = document.getElementById('modal-youtube-player');

    if (modal && modalPlayer) {
        modal.classList.remove('active');
        modalPlayer.src = '';
        document.body.style.overflow = '';
        document.removeEventListener('keydown', handleModalEscape);
    }
}

