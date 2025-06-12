document.addEventListener("DOMContentLoaded", function() {
  const track = document.querySelector(".carousel-track");
  const items = document.querySelectorAll(".carousel-item");
  const prevBtn = document.querySelector(".carousel-prev");
  const nextBtn = document.querySelector(".carousel-next");
  
  // Hitung lebar item termasuk margin
  const itemStyle = window.getComputedStyle(items[0]);
  const itemWidth = items[0].offsetWidth + 
                   parseInt(itemStyle.marginLeft) + 
                   parseInt(itemStyle.marginRight);
  
  let currentIndex = 0;
  const visibleItems = 3; // Jumlah item yang terlihat sekaligus
  
  function updateCarousel() {
    // Batasi index agar tidak melebihi jumlah item
    if (currentIndex > items.length - visibleItems) {
      currentIndex = 0;
    }
    if (currentIndex < 0) {
      currentIndex = items.length - visibleItems;
    }
    
    track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
    track.style.transition = "transform 0.5s ease";
  }
  
  function nextSlide() {
    currentIndex++;
    updateCarousel();
  }
  
  function prevSlide() {
    currentIndex--;
    updateCarousel();
  }
  
  // Auto slide
  let autoSlide = setInterval(nextSlide, 3000);
  
  // Pause on hover
  track.addEventListener('mouseenter', () => clearInterval(autoSlide));
  track.addEventListener('mouseleave', () => {
    autoSlide = setInterval(nextSlide, 3000);
  });
  
  // Button controls
  nextBtn.addEventListener('click', () => {
    clearInterval(autoSlide);
    nextSlide();
    autoSlide = setInterval(nextSlide, 3000);
  });
  
  prevBtn.addEventListener('click', () => {
    clearInterval(autoSlide);
    prevSlide();
    autoSlide = setInterval(nextSlide, 3000);
  });
  
  // Responsive adjustment
  window.addEventListener('resize', () => {
    clearInterval(autoSlide);
    updateCarousel();
    autoSlide = setInterval(nextSlide, 3000);
  });
});