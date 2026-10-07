/**
 * SMM Panel - Master Application Controller
 * Handles Navigation, 3D Cards, GSAP Transitions, Lucide Icons
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Initialize Lucide Icons
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }

  // 2. Bind 3D Carousel Cards Click -> GSAP Expand
  const hubCards = document.querySelectorAll('.hub-card');
  hubCards.forEach(card => {
    card.addEventListener('click', (e) => {
      const targetPage = card.dataset.targetPage;
      if (targetPage) {
        expandCardToPage(card, targetPage);
      }
    });
  });

  // 3. Bind Quick Actions (4 options) -> GSAP Expand
  const quickActionItems = document.querySelectorAll('.quick-action-item');
  quickActionItems.forEach(item => {
    item.addEventListener('click', () => {
      const targetPage = item.dataset.targetPage;
      if (targetPage) {
        expandCardToPage(item, targetPage);
      }
    });
  });

  // 4. Bind Page Back Buttons -> GSAP Contract
  const backButtons = document.querySelectorAll('.back-btn-link');
  backButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      contractPageToCard();
    });
  });

  // 5. Bind Bottom Dock Items (4 options)
  const dockItems = document.querySelectorAll('.dock-item-btn');
  dockItems.forEach(dock => {
    dock.addEventListener('click', () => {
      dockItems.forEach(d => d.classList.remove('active'));
      dock.classList.add('active');

      const target = dock.dataset.target;
      if (target === 'home') {
        contractPageToCard();
      } else if (target) {
        expandCardToPage(dock, target);
      }
    });
  });

  // 6. Header Add Funds Button shortcut
  const headerAddFundsBtn = document.querySelector('.add-funds-pill-btn');
  if (headerAddFundsBtn) {
    headerAddFundsBtn.addEventListener('click', () => {
      const addFundsCard = document.querySelector('[data-target-page="page-add-funds"]');
      expandCardToPage(headerAddFundsBtn, 'page-add-funds');
    });
  }

  // 7. Re-initialize Lucide Icons on any dynamic update
  const observer = new MutationObserver(() => {
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  });
  observer.observe(document.body, { childList: true, subtree: true });
});
