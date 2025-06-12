import './bootstrap';
  class CarouselController {
            constructor() {
                this.wrapper = document.getElementById('carouselWrapper');
                this.items = document.querySelectorAll('.carousel-item');
                this.currentIndex = 0;
                this.itemWidth = 325; // width + gap
                this.autoScrollInterval = null;
                this.isAutoScrolling = true;
                
                this.init();
            }
            
            init() {
                if (!this.wrapper || this.items.length === 0) return;
                
                this.setupEventListeners();
                this.startAutoScroll();
                this.setupTouchEvents();
                this.updateNavigationButtons();
            }
            
            setupEventListeners() {
                // Navigation buttons
                const prevBtn = document.querySelector('.carousel-nav.prev');
                const nextBtn = document.querySelector('.carousel-nav.next');
                
                if (prevBtn) prevBtn.addEventListener('click', () => this.scrollPrev());
                if (nextBtn) nextBtn.addEventListener('click', () => this.scrollNext());
                
                // Pause auto-scroll on hover
                this.wrapper.addEventListener('mouseenter', () => this.stopAutoScroll());
                this.wrapper.addEventListener('mouseleave', () => this.startAutoScroll());
                
                // Keyboard navigation
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft') this.scrollPrev();
                    if (e.key === 'ArrowRight') this.scrollNext();
                });
                
                // Window resize handler
                window.addEventListener('resize', () => this.updateItemWidth());
            }
            
            updateItemWidth() {
                const item = this.items[0];
                if (item) {
                    const styles = window.getComputedStyle(item);
                    this.itemWidth = item.offsetWidth + parseInt(styles.marginRight || 0) + 25;
                }
            }
            
            scrollToIndex(index, smooth = true) {
                if (index < 0 || index >= this.items.length) return;
                
                this.currentIndex = index;
                const scrollPosition = index * this.itemWidth;
                
                if (smooth) {
                    this.wrapper.scrollTo({
                        left: scrollPosition,
                        behavior: 'smooth'
                    });
                } else {
                    this.wrapper.scrollLeft = scrollPosition;
                }
                
                this.updateNavigationButtons();
                this.updateActiveItem();
            }
            
            scrollNext() {
                if (this.currentIndex < this.items.length - 1) {
                    this.scrollToIndex(this.currentIndex + 1);
                } else {
                    // Loop back to start
                    this.scrollToIndex(0);
                }
            }
            
            scrollPrev() {
                if (this.currentIndex > 0) {
                    this.scrollToIndex(this.currentIndex - 1);
                } else {
                    // Loop to end
                    this.scrollToIndex(this.items.length - 1);
                }
            }
            
            updateNavigationButtons() {
                const prevBtn = document.querySelector('.carousel-nav.prev');
                const nextBtn = document.querySelector('.carousel-nav.next');
                
                if (prevBtn && nextBtn) {
                    prevBtn.style.opacity = this.currentIndex === 0 ? '0.5' : '1';
                    nextBtn.style.opacity = this.currentIndex === this.items.length - 1 ? '0.5' : '1';
                }
            }
            
            updateActiveItem() {
                this.items.forEach((item, index) => {
                    item.classList.toggle('active', index === this.currentIndex);
                });
            }
            
            startAutoScroll() {
                if (!this.isAutoScrolling) return;
                
                this.stopAutoScroll();
                this.autoScrollInterval = setInterval(() => {
                    this.scrollNext();
                }, 4000);
            }
            
            stopAutoScroll() {
                if (this.autoScrollInterval) {
                    clearInterval(this.autoScrollInterval);
                    this.autoScrollInterval = null;
                }
            }
            
            setupTouchEvents() {
                let startX = 0;
                let startScrollLeft = 0;
                let isDragging = false;
                
                this.wrapper.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].pageX;
                    startScrollLeft = this.wrapper.scrollLeft;
                    isDragging = true;
                    this.stopAutoScroll();
                }, { passive: true });
                
                this.wrapper.addEventListener('touchmove', (e) => {
                    if (!isDragging) return;
                    
                    const x = e.touches[0].pageX;
                    const walk = (startX - x) * 1.5;
                    this.wrapper.scrollLeft = startScrollLeft + walk;
                }, { passive: true });
                
                this.wrapper.addEventListener('touchend', (e) => {
                    if (!isDragging) return;
                    isDragging = false;
                    
                    const endX = e.changedTouches[0].pageX;
                    const diff = startX - endX;
                    
                    // Threshold for swipe detection
                    if (Math.abs(diff) > 50) {
                        if (diff > 0) {
                            this.scrollNext();
                        } else {
                            this.scrollPrev();
                        }
                    } else {
                        // Snap back to current position
                        this.scrollToIndex(this.currentIndex);
                    }
                    
                    setTimeout(() => this.startAutoScroll(), 1000);
                }, { passive: true });
                
                // Mouse drag support for desktop
                let mouseStartX = 0;
                let mouseStartScrollLeft = 0;
                let isMouseDragging = false;
                
                this.wrapper.addEventListener('mousedown', (e) => {
                    mouseStartX = e.pageX;
                    mouseStartScrollLeft = this.wrapper.scrollLeft;
                    isMouseDragging = true;
                    this.wrapper.style.cursor = 'grabbing';
                    this.stopAutoScroll();
                    e.preventDefault();
                });
                
                document.addEventListener('mousemove', (e) => {
                    if (!isMouseDragging) return;
                    
                    const x = e.pageX;
                    const walk = (mouseStartX - x) * 1.5;
                    this.wrapper.scrollLeft = mouseStartScrollLeft + walk;
                });
                
                document.addEventListener('mouseup', (e) => {
                    if (!isMouseDragging) return;
                    isMouseDragging = false;
                    this.wrapper.style.cursor = 'grab';
                    
                    const endX = e.pageX;
                    const diff = mouseStartX - endX;
                    
                    if (Math.abs(diff) > 50) {
                        if (diff > 0) {
                            this.scrollNext();
                        } else {
                            this.scrollPrev();
                        }
                    } else {
                        this.scrollToIndex(this.currentIndex);
                    }
                    
                    setTimeout(() => this.startAutoScroll(), 1000);
                });
            }
        }
        
        // Initialize carousel when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new CarouselController();
        });
        
        // Utility functions for external use
        function scrollCarousel(direction) {
            const event = new CustomEvent('carouselScroll', { detail: { direction } });
            document.dispatchEvent(event);
        }
        
        // Listen for external scroll events
        document.addEventListener('carouselScroll', (e) => {
            const carousel = document.querySelector('.carousel-wrapper');
            if (carousel) {
                const scrollAmount = 325;
                if (e.detail.direction === 'prev') {
                    carousel.scrollLeft -= scrollAmount;
                } else {
                    carousel.scrollLeft += scrollAmount;
                }
            }
        });
        
        // Intersection Observer for lazy loading and animations
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                } else {
                    entry.target.classList.remove('animate-in');
                }
            });
        }, observerOptions);
        
        // Observe carousel items for animations
        document.addEventListener('DOMContentLoaded', () => {
            const carouselItems = document.querySelectorAll('.carousel-item');
            carouselItems.forEach(item => observer.observe(item));
        });
        
        // Performance optimization: Throttle scroll events
        function throttle(func, wait) {
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
        
        // Enhanced scroll behavior
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.getElementById('carouselWrapper');
            if (wrapper) {
                wrapper.style.cursor = 'grab';
                
                // Smooth scroll behavior
                wrapper.addEventListener('wheel', throttle((e) => {
                    e.preventDefault();
                    wrapper.scrollLeft += e.deltaY;
                }, 16), { passive: false });
            }
        });