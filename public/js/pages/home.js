document.addEventListener("DOMContentLoaded", function () {
  const items = document.querySelectorAll('.carousel-item');
  const prevBtn = document.querySelector('.carousel-btn.prev');
  const nextBtn = document.querySelector('.carousel-btn.next');
  if (items.length === 0) return;

  let currentIndex = 0;

  function updateCarousel() {
    items.forEach((item, idx) => {
      item.classList.toggle('active', idx === currentIndex);
      item.style.display = (Math.abs(idx - currentIndex) <= 1) ? 'block' : 'none'; // tampilkan hanya tengah & samping
    });
  }

  prevBtn.addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + items.length) % items.length;
    updateCarousel();
  });

  nextBtn.addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % items.length;
    updateCarousel();
  });

  setInterval(() => {
    nextBtn.click();
  }, 3500);

  updateCarousel();
});