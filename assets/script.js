/* ══════════════════════════════════════════════════
   CFI College of Law — Main JavaScript
   ══════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', function () {

  /* ─── AOS: Animate on Scroll ─────────────────────── */
  AOS.init({ duration: 750, once: true, offset: 60 });

  /* ─── Navbar: Transparent → Solid on scroll ─────── */
  const nav = document.getElementById('mainNav');
  const handleNavScroll = () => nav.classList.toggle('scrolled', window.scrollY > 60);
  window.addEventListener('scroll', handleNavScroll);
  handleNavScroll(); // run once on load

  /* ─── Hero Carousel: Custom Indicator Sync ───────── */
  const carousel    = document.getElementById('mainCarousel');
  const indicators  = document.querySelectorAll('#heroIndicators button');

  carousel.addEventListener('slid.bs.carousel', function (e) {
    indicators.forEach(b => b.classList.remove('active'));
    indicators[e.to].classList.add('active');
  });

  indicators.forEach(function (btn) {
    btn.addEventListener('click', function () {
      const c = bootstrap.Carousel.getInstance(carousel);
      c.to(+btn.dataset.slide);
    });
  });

  /* ─── Counter Animation ──────────────────────────── */
  let counted = false;

  function animateCounters() {
    if (counted) return;
    counted = true;

    document.querySelectorAll('.count-up').forEach(function (el) {
      const target   = +el.dataset.target;
      const duration = 1800;
      const step     = target / (duration / 16);
      let current    = 0;

      const timer = setInterval(function () {
        current = Math.min(current + step, target);
        const val = Math.floor(current);

        if (target === 98)   el.textContent = val + '%';
        else if (target === 2025) el.textContent = val;
        else                 el.textContent = val + '+';

        if (current >= target) clearInterval(timer);
      }, 16);
    });
  }

  // Start counters after a short delay on page load
  setTimeout(animateCounters, 800);

  /* ─── Infrastructure Carousel ────────────────────── */
  const infraEl    = document.getElementById('infraCarousel');
  const infraC     = new bootstrap.Carousel(infraEl, { ride: false, wrap: true });
  const infraBar   = document.getElementById('infraBar');
  const infraCount = document.getElementById('infraCount');
  const totalInfra = 3;

  function updateInfra(idx) {
    infraBar.style.width = ((idx + 1) / totalInfra * 100) + '%';
    infraCount.textContent =
      String(idx + 1).padStart(2, '0') + ' / ' +
      String(totalInfra).padStart(2, '0');
  }

  document.getElementById('infraPrev').addEventListener('click', function () {
    infraC.prev();
  });
  document.getElementById('infraNext').addEventListener('click', function () {
    infraC.next();
  });

  infraEl.addEventListener('slid.bs.carousel', function (e) {
    updateInfra(e.to);
  });

});
