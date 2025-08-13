$(document).ready(function(){
    $('#autoWidth').lightSlider({
        autoWidth: true,
        loop: true,
        onSliderLoad: function(){ 
            $('#autoWidth').removeClass('cS-hidden');
        },
        responsive: [
            {
                breakpoint: 800,
                settings: {
                    item: 2,
                    slideMove: 1,
                    slideMargin: 6,
                }
            },
            {
                breakpoint: 480,
                settings: {
                    item: 1,
                    slideMove: 1
                }
            }
        ],
        // Configuration personnalisée pour le slider
        item: 3,
        slideMove: 1,
        slideMargin: 20,
        speed: 600,
        auto: true,
        pauseOnHover: true,
        pause: 4000,
        mode: 'slide',
        easing: 'ease',
        cssEasing: 'ease',
        easing: 'ease',
        cssEasing: 'ease',
        // Navigation
        pager: true,
        gallery: false,
        galleryMargin: 5,
        thumbMargin: 5,
        currentPagerPosition: 'middle',
        // Contrôles
        controls: true,
        prevHtml: '<i class="fas fa-chevron-left"></i>',
        nextHtml: '<i class="fas fa-chevron-right"></i>',
        // Callbacks
        onBeforeStart: function (el) {
            // Animation avant le démarrage
            $('.box').addClass('sliding');
        },
        onAfterStart: function (el) {
            // Animation après le démarrage
            setTimeout(function() {
                $('.box').removeClass('sliding');
            }, 300);
        },
        onBeforeNextSlide: function (el) {
            // Animation avant le slide suivant
            $('.box').addClass('next-slide');
        },
        onAfterNextSlide: function (el) {
            // Animation après le slide suivant
            setTimeout(function() {
                $('.box').removeClass('next-slide');
            }, 300);
        },
        onBeforePrevSlide: function (el) {
            // Animation avant le slide précédent
            $('.box').addClass('prev-slide');
        },
        onAfterPrevSlide: function (el) {
            // Animation après le slide précédent
            setTimeout(function() {
                $('.box').removeClass('prev-slide');
            }, 300);
        }
    });
    
    // Ajout d'effets interactifs supplémentaires
    $('.box').on('mouseenter', function() {
        $(this).addClass('hovered');
        // Ajouter un effet de parallaxe subtil
        $(this).css('transform', 'scale(1.05) translateY(-10px)');
    });
    
    $('.box').on('mouseleave', function() {
        $(this).removeClass('hovered');
        $(this).css('transform', 'scale(1) translateY(0)');
    });
    
    // Animation des détails au clic
    $('.details').on('click', function() {
        const $details = $(this);
        const $box = $details.closest('.box');
        
        if ($box.hasClass('expanded')) {
            // Réduire la carte
            $box.removeClass('expanded');
            $details.find('p').css('max-height', '80px');
            $box.css('height', '610px');
        } else {
            // Étendre la carte
            $box.addClass('expanded');
            $details.find('p').css('max-height', '200px');
            $box.css('height', '700px');
        }
    });
    
    // Effet de ripple sur les cartes
    $('.box').on('click', function(e) {
        const $ripple = $('<span class="ripple"></span>');
        const $box = $(this);
        
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        $ripple.css({
            width: size,
            height: size,
            left: x,
            top: y
        });
        
        $box.append($ripple);
        
        setTimeout(function() {
            $ripple.remove();
        }, 600);
    });
    
    // Auto-play avec pause au survol
    let autoPlayInterval;
    
    function startAutoPlay() {
        autoPlayInterval = setInterval(function() {
            $('#autoWidth').goToNextSlide();
        }, 5000);
    }
    
    function stopAutoPlay() {
        clearInterval(autoPlayInterval);
    }
    
    // Démarrer l'auto-play
    startAutoPlay();
    
    // Pause au survol
    $('.container').hover(
        function() { stopAutoPlay(); },
        function() { startAutoPlay(); }
    );
    
    // Navigation au clavier
    $(document).keydown(function(e) {
        switch(e.which) {
            case 37: // Flèche gauche
                $('#autoWidth').goToPrevSlide();
                break;
            case 39: // Flèche droite
                $('#autoWidth').goToNextSlide();
                break;
            case 32: // Espace
                e.preventDefault();
                if (autoPlayInterval) {
                    stopAutoPlay();
                } else {
                    startAutoPlay();
                }
                break;
        }
    });
    
    // Indicateur de progression
    function updateProgress() {
        const $slider = $('#autoWidth');
        const currentSlide = $slider.find('.lSSlideWrapper .lSSlide').index($slider.find('.lSSlideWrapper .lSSlide.active'));
        const totalSlides = $slider.find('.lSSlideWrapper .lSSlide').length;
        const progress = ((currentSlide + 1) / totalSlides) * 100;
        
        // Créer ou mettre à jour la barre de progression
        let $progressBar = $('.progress-bar');
        if ($progressBar.length === 0) {
            $progressBar = $('<div class="progress-bar"></div>');
            $('.container').append($progressBar);
        }
        
        $progressBar.css('width', progress + '%');
    }
    
    // Mettre à jour la progression à chaque changement de slide
    $('#autoWidth').on('afterNextSlide', updateProgress);
    $('#autoWidth').on('afterPrevSlide', updateProgress);
    
    // Initialiser la progression
    updateProgress();
});

// Styles CSS supplémentaires pour les animations
const additionalStyles = `
<style>
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(52, 152, 219, 0.3);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

.box.expanded {
    transition: height 0.3s ease;
}

.box.expanded .details p {
    transition: max-height 0.3s ease;
}

.box.sliding {
    transform: scale(0.95);
    opacity: 0.8;
}

.box.next-slide {
    animation: slideNext 0.3s ease;
}

.box.prev-slide {
    animation: slidePrev 0.3s ease;
}

@keyframes slideNext {
    0% { transform: translateX(-20px); opacity: 0.5; }
    100% { transform: translateX(0); opacity: 1; }
}

@keyframes slidePrev {
    0% { transform: translateX(20px); opacity: 0.5; }
    100% { transform: translateX(0); opacity: 1; }
}

.progress-bar {
    position: fixed;
    top: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, #3498db, #2980b9);
    transition: width 0.3s ease;
    z-index: 1001;
}

.box.hovered {
    z-index: 10;
}

/* Amélioration de l'accessibilité */
.box:focus {
    outline: 3px solid #3498db;
    outline-offset: 2px;
}

/* Mode sombre/clair automatique */
@media (prefers-color-scheme: light) {
    .container {
        background: linear-gradient(135deg, #ecf0f1 0%, #bdc3c7 100%);
    }
    
    .box {
        background: #ffffff;
        color: #2c3e50;
        border-color: rgba(52, 152, 219, 0.3);
    }
    
    .details p {
        color: #34495e;
    }
    
    .marvel {
        background: linear-gradient(45deg, #3498db, #2980b9);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
}
</style>
`;

// Injecter les styles supplémentaires
$('head').append(additionalStyles);
