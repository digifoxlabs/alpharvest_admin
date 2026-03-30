import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

/* ============================================================
   ALP HARVEST – Main JavaScript
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  /* ----------------------------------------------------------
     NAVBAR: scroll state
  ---------------------------------------------------------- */
  const navbar = document.getElementById('navbar');

  function handleNavbarScroll() {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  }

  window.addEventListener('scroll', handleNavbarScroll, { passive: true });
  handleNavbarScroll(); // run once on load


  /* ----------------------------------------------------------
     MOBILE DRAWER
  ---------------------------------------------------------- */
  const hamburger  = document.getElementById('hamburger');
  const drawer     = document.getElementById('mobile-drawer');
  const overlay    = document.getElementById('drawer-overlay');

  function openDrawer() {
    drawer.classList.add('open');
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    drawer.classList.remove('open');
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  hamburger.addEventListener('click', openDrawer);
  overlay.addEventListener('click', closeDrawer);

  // Close drawer on any nav-link click
  document.querySelectorAll('#mobile-drawer a').forEach(link => {
    link.addEventListener('click', closeDrawer);
  });

  // Expose globally for inline onclick (accordion buttons)
  window.closeDrawer = closeDrawer;


  /* ----------------------------------------------------------
     MOBILE ACCORDION
  ---------------------------------------------------------- */
  function toggleMobileAccordion(subId, chevronId) {
    const sub     = document.getElementById(subId);
    const chevron = document.getElementById(chevronId);
    const isOpen  = sub.classList.contains('open');

    sub.classList.toggle('open', !isOpen);
    if (chevron) chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
  }

  // Expose globally so inline onclick="toggleMobile(...)" works
  window.toggleMobile = toggleMobileAccordion;


  /* ----------------------------------------------------------
     CAROUSEL
  ---------------------------------------------------------- */
  let currentSlide = 0;
  const totalSlides = 3;
  const track = document.getElementById('carousel-track');
  const dots  = document.querySelectorAll('.carousel-dot');

  // function updateCarousel() {
  //   track.style.transform = `translateX(-${currentSlide * 100}%)`;
  //   dots.forEach((d, i) => {
  //     d.classList.toggle('active', i === currentSlide);
  //   });
  // }

  function updateCarousel() {
  if (!track) return;

  track.style.transform = `translateX(-${currentSlide * 100}%)`;

  dots.forEach((d, i) => {
    d.classList.toggle('active', i === currentSlide);
  });
}

  function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    updateCarousel();
  }

  function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    updateCarousel();
  }

  // Expose for inline onclick
  window.nextSlide   = nextSlide;
  window.prevSlide   = prevSlide;
  window.goToSlide   = (n) => { currentSlide = n; updateCarousel(); };

  // Auto-play
  let autoPlay = setInterval(nextSlide, 4500);

  // Pause on hover
  const carouselWrapper = document.querySelector('.carousel-wrapper');
  if (carouselWrapper) {
    carouselWrapper.addEventListener('mouseenter', () => clearInterval(autoPlay));
    carouselWrapper.addEventListener('mouseleave', () => {
      autoPlay = setInterval(nextSlide, 4500);
    });
  }

  updateCarousel();


  /* ----------------------------------------------------------
     PROGRESS BAR + BACK TO TOP
  ---------------------------------------------------------- */
  const progressBar  = document.getElementById('progress-bar');
  const backToTop    = document.getElementById('back-to-top');
  const ring         = document.getElementById('progress-ring');
  const CIRCUMFERENCE = 150.8;

  function handleScrollProgress() {
    const scrollTop  = window.scrollY;
    const docHeight  = document.documentElement.scrollHeight - window.innerHeight;
    const progress   = docHeight > 0 ? scrollTop / docHeight : 0;

    // Top progress bar
    progressBar.style.width = (progress * 100) + '%';

    // Ring
    if (ring) {
      ring.style.strokeDashoffset = CIRCUMFERENCE * (1 - progress);
    }

    // Show/hide back-to-top
    backToTop.classList.toggle('visible', scrollTop > 300);
  }

  window.addEventListener('scroll', handleScrollProgress, { passive: true });

  backToTop.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });


  /* ----------------------------------------------------------
     SCROLL REVEAL
  ---------------------------------------------------------- */
  const reveals = document.querySelectorAll('.reveal');
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('visible');
        revealObserver.unobserve(e.target); // fire only once
      }
    });
  }, { threshold: 0.1 });

  reveals.forEach(r => revealObserver.observe(r));

});

