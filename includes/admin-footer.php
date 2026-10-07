<?php
/**
 * Reusable Admin Footer Layout
 */
?>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }

    // Admin Mobile Drawer Toggle
    const burgerBtn = document.getElementById('admin-hamburger-btn');
    const drawer = document.getElementById('admin-mobile-drawer');
    const closeBtn = document.getElementById('admin-drawer-close');

    if (burgerBtn && drawer) {
      burgerBtn.addEventListener('click', () => {
        drawer.classList.add('open');
      });
    }

    if (closeBtn && drawer) {
      closeBtn.addEventListener('click', () => {
        drawer.classList.remove('open');
      });
    }

    if (drawer) {
      drawer.addEventListener('click', (e) => {
        if (e.target === drawer) {
          drawer.classList.remove('open');
        }
      });
    }
  </script>
</body>
</html>
