document.addEventListener("DOMContentLoaded", function () {
  const items = document.querySelectorAll('.carousel-item');
  const prevBtn = document.querySelector('.carousel-btn.prev');
  const nextBtn = document.querySelector('.carousel-btn.next');
  if (items.length === 0) return;

  let currentIndex = 0;
  let autoRotateInterval;

  function updateCarousel() {
    items.forEach((item, idx) => {
      item.classList.toggle('active', idx === currentIndex);
      // Show only center and adjacent items for better mobile experience
      const isVisible = Math.abs(idx - currentIndex) <= 1;
      item.style.display = isVisible ? 'block' : 'none';
    });
  }

  function startAutoRotate() {
    autoRotateInterval = setInterval(() => {
      currentIndex = (currentIndex + 1) % items.length;
      updateCarousel();
    }, 3500);
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

  updateCarousel();
  startAutoRotate();

  // Pause on hover for desktop
  if (carousel) {
    carousel.addEventListener('mouseenter', stopAutoRotate);
    carousel.addEventListener('mouseleave', startAutoRotate);
  }
});