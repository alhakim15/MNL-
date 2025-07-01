document.addEventListener("DOMContentLoaded", function () {
  const items = document.querySelectorAll('.carousel-item');
  const prevBtn = document.querySelector('.carousel-btn.prev');
  const nextBtn = document.querySelector('.carousel-btn.next');
  
  if (items.length === 0) return;

  let currentIndex = 0;
  let autoRotateInterval;

  function updateCarousel() {
    items.forEach((item, idx) => {
      // Reset all classes
      item.classList.remove('active', 'left', 'right', 'far-left', 'far-right');
      
      // Calculate position relative to current active item
      let position = idx - currentIndex;
      
      // Handle circular positioning for smooth infinite scroll
      if (position > items.length / 2) {
        position -= items.length;
      } else if (position < -items.length / 2) {
        position += items.length;
      }
      
      // Assign classes based on position
      if (position === 0) {
        item.classList.add('active');
      } else if (position === 1) {
        item.classList.add('right');
      } else if (position === -1) {
        item.classList.add('left');
      } else if (position >= 2) {
        item.classList.add('far-right');
      } else if (position <= -2) {
        item.classList.add('far-left');
      }
    });
  }

  function stopAutoRotate() {
    if (autoRotateInterval) {
      clearInterval(autoRotateInterval);
    }
  }

  // Touch support for mobile
  let startX = 0;
  let isDragging = false;

  const carousel = document.querySelector('.carousel-wrapper');
  
  if (carousel) {
    // Touch events
    carousel.addEventListener('touchstart', (e) => {
      startX = e.touches[0].clientX;
      isDragging = true;
      stopAutoRotate();
    });

    carousel.addEventListener('touchmove', (e) => {
      if (!isDragging) return;
      e.preventDefault(); // Prevent scrolling
    });

    carousel.addEventListener('touchend', (e) => {
      if (!isDragging) return;
      isDragging = false;
      
      const endX = e.changedTouches[0].clientX;
      const diffX = startX - endX;
      
      // Minimum swipe distance
      if (Math.abs(diffX) > 50) {
        if (diffX > 0) {
          // Swipe left - next
          currentIndex = (currentIndex + 1) % items.length;
        } else {
          // Swipe right - previous  
          currentIndex = (currentIndex - 1 + items.length) % items.length;
        }
        updateCarousel();
      }
      
      startAutoRotate();
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      stopAutoRotate();
      currentIndex = (currentIndex - 1 + items.length) % items.length;
      updateCarousel();
      startAutoRotate();
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      stopAutoRotate();
      currentIndex = (currentIndex + 1) % items.length;
      updateCarousel();
      startAutoRotate();
    });
  }

  // Add click handlers to carousel items
  items.forEach((item, idx) => {
    item.addEventListener('click', (e) => {
      // Only handle click if not already active and not on the link
      if (!item.classList.contains('active') && !e.target.closest('a')) {
        e.preventDefault();
        stopAutoRotate();
        currentIndex = idx;
        updateCarousel();
        startAutoRotate();
      }
    });
  });

  // Initialize carousel
  updateCarousel();
  startAutoRotate();

  // Pause on hover for desktop
  if (carousel) {
    carousel.addEventListener('mouseenter', stopAutoRotate);
    carousel.addEventListener('mouseleave', startAutoRotate);
  }
});