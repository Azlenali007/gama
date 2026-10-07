/**
 * SMM Panel - Swiper 3D Carousel Initializer
 * Creates prominent centered 3D card experience with depth, rotation, scale
 */

let hubSwiper = null;

function initHubCarousel() {
  const container = document.querySelector('.swiper-3d-hub');
  if (!container || typeof Swiper === 'undefined') return;

  if (hubSwiper) {
    hubSwiper.destroy(true, true);
  }

  hubSwiper = new Swiper('.swiper-3d-hub', {
    effect: 'coverflow',
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: 'auto',
    initialSlide: 1, // Start on center card: New Order
    speed: 500,
    coverflowEffect: {
      rotate: 22,
      stretch: 0,
      depth: 160,
      modifier: 1.2,
      slideShadows: false, // Clean custom CSS shadows instead of dark murky overlays
    },
    pagination: {
      el: '.hub-carousel-pagination',
      clickable: true,
    },
    keyboard: {
      enabled: true,
    },
    mousewheel: {
      forceToAxis: true,
      sensitivity: 0.8,
    },
    on: {
      slideChange: function () {
        // Trigger subtle haptic vibration if supported on mobile
        if ('vibrate' in navigator) {
          navigator.vibrate(12);
        }
      }
    }
  });

  return hubSwiper;
}

// Auto-run if document is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initHubCarousel);
} else {
  initHubCarousel();
}
