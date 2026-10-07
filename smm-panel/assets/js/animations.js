/**
 * SMM Panel - GSAP Card-To-Page Fluid Morph Animations
 * Seamlessly expands clicked 3D card into full page and contracts back
 */

let activeCardOrigin = null;
let currentActivePageId = null;

/**
 * Animate Card expanding to fill the screen into its respective page
 * @param {HTMLElement} cardElement The card element clicked
 * @param {string} targetPageId The ID of the page container to show
 */
function expandCardToPage(cardElement, targetPageId) {
  const targetPage = document.getElementById(targetPageId);
  const hubSection = document.querySelector('.carousel-hub-section');
  const quickActionsSection = document.querySelector('.quick-actions-container');
  const userWelcomeBar = document.querySelector('.user-welcome-bar');

  if (!targetPage) return;

  // Save current active state
  currentActivePageId = targetPageId;
  activeCardOrigin = cardElement;

  if (typeof gsap === 'undefined') {
    // Graceful fallback if GSAP not yet loaded
    if (hubSection) hubSection.style.display = 'none';
    if (quickActionsSection) quickActionsSection.style.display = 'none';
    targetPage.classList.add('active');
    targetPage.style.opacity = '1';
    window.scrollTo({ top: 0, behavior: 'smooth' });
    return;
  }

  // Get source card coordinates
  const cardRect = cardElement.getBoundingClientRect();
  const containerRect = targetPage.parentElement.getBoundingClientRect();

  // Prepare Target Page
  targetPage.style.display = 'block';
  targetPage.style.visibility = 'hidden';
  targetPage.classList.add('active');

  // Compute scale and translation offsets
  const scaleX = cardRect.width / containerRect.width;
  const scaleY = cardRect.height / 350; // Initial approximate height
  const deltaX = (cardRect.left + cardRect.width / 2) - (containerRect.left + containerRect.width / 2);
  const deltaY = (cardRect.top + cardRect.height / 2) - (containerRect.top + 200);

  targetPage.style.visibility = 'visible';

  // Master GSAP Timeline
  const tl = gsap.timeline({
    onComplete: () => {
      if (hubSection) hubSection.style.display = 'none';
      if (quickActionsSection) quickActionsSection.style.display = 'none';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  });

  // Fade out dashboard hub siblings
  if (hubSection) {
    tl.to([hubSection, quickActionsSection], {
      opacity: 0,
      y: 20,
      scale: 0.95,
      duration: 0.25,
      ease: 'power2.in'
    }, 0);
  }

  // Morph target page from card position
  tl.fromTo(targetPage, {
    opacity: 0.8,
    scaleX: scaleX,
    scaleY: scaleY,
    x: deltaX,
    y: deltaY,
    borderRadius: '28px',
    transformOrigin: 'center center'
  }, {
    opacity: 1,
    scaleX: 1,
    scaleY: 1,
    x: 0,
    y: 0,
    borderRadius: '28px',
    duration: 0.45,
    ease: 'power3.out'
  }, 0.05);

  // Stagger in page elements
  const pageChildren = targetPage.querySelectorAll('.page-back-header, .service-details-card, .order-step-indicator, .funds-current-card, .filter-tabs-row, .order-card-row, .service-box-card');
  if (pageChildren.length > 0) {
    tl.fromTo(pageChildren, {
      opacity: 0,
      y: 15
    }, {
      opacity: 1,
      y: 0,
      duration: 0.3,
      stagger: 0.04,
      ease: 'power2.out'
    }, 0.2);
  }
}

/**
 * Animate page contracting back to card and restoring the 3D carousel hub
 */
function contractPageToCard() {
  if (!currentActivePageId) return;

  const activePage = document.getElementById(currentActivePageId);
  const hubSection = document.querySelector('.carousel-hub-section');
  const quickActionsSection = document.querySelector('.quick-actions-container');

  if (!activePage) return;

  if (typeof gsap === 'undefined' || !activeCardOrigin) {
    activePage.classList.remove('active');
    activePage.style.display = 'none';
    if (hubSection) {
      hubSection.style.display = 'block';
      hubSection.style.opacity = '1';
    }
    if (quickActionsSection) {
      quickActionsSection.style.display = 'block';
      quickActionsSection.style.opacity = '1';
    }
    currentActivePageId = null;
    return;
  }

  const cardRect = activeCardOrigin.getBoundingClientRect();
  const containerRect = activePage.parentElement.getBoundingClientRect();
  const scaleX = cardRect.width / containerRect.width;
  const scaleY = cardRect.height / 350;
  const deltaX = (cardRect.left + cardRect.width / 2) - (containerRect.left + containerRect.width / 2);
  const deltaY = (cardRect.top + cardRect.height / 2) - (containerRect.top + 200);

  if (hubSection) hubSection.style.display = 'block';
  if (quickActionsSection) quickActionsSection.style.display = 'block';

  const tl = gsap.timeline({
    onComplete: () => {
      activePage.classList.remove('active');
      activePage.style.display = 'none';
      currentActivePageId = null;
      if (typeof initHubCarousel === 'function') {
        initHubCarousel();
      }
    }
  });

  // Shrink page back towards origin card
  tl.to(activePage, {
    scaleX: scaleX,
    scaleY: scaleY,
    x: deltaX,
    y: deltaY,
    opacity: 0,
    borderRadius: '28px',
    duration: 0.35,
    ease: 'power2.inOut'
  }, 0);

  // Restore hub and actions
  if (hubSection) {
    tl.to([hubSection, quickActionsSection], {
      opacity: 1,
      y: 0,
      scale: 1,
      duration: 0.35,
      ease: 'power3.out'
    }, 0.1);
  }
}
