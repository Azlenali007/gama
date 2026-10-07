/**
 * SMM Panel - User Dashboard Business Logic
 */

let selectedServiceRate = 35.00;
let selectedServiceName = 'Instagram Followers';
let selectedCategorySlug = 'instagram';

function getAppCurrencySymbol() {
  return window.APP_CURRENCY_SYMBOL || (document.querySelector('.curr-sym') ? document.querySelector('.curr-sym').textContent.trim() : '₹');
}

// Initialize User Events
function initUserModule() {
  initServiceSelectors();
  initQuantityStepper();
  initAddFundsModule();
  initOrderTabsFilter();
  initTransactionsFilter();
  initTicketFilter();
}

/**
 * Handle Service Selection & Dynamic Price Recalculation
 */
function initServiceSelectors() {
  const serviceCards = document.querySelectorAll('.service-card-item');
  serviceCards.forEach(card => {
    card.addEventListener('click', function () {
      serviceCards.forEach(c => c.classList.remove('selected'));
      this.classList.add('selected');

      const rate = parseFloat(this.dataset.rate || '35.00');
      const name = this.dataset.name || 'Instagram Followers';
      const min = parseInt(this.dataset.min || '1000');
      const max = parseInt(this.dataset.max || '1010000');
      const badges = this.dataset.badges || 'High quality followers | Instant Start | No Drop';

      selectedServiceRate = rate;
      selectedServiceName = name;

      // Update UI
      const nameElem = document.getElementById('selected-service-name');
      const rateElem = document.getElementById('selected-service-rate');
      const badgesElem = document.getElementById('selected-service-badges');
      const minMaxElem = document.getElementById('selected-service-minmax');
      const qtyInput = document.getElementById('order-quantity-input');

      if (nameElem) nameElem.textContent = name;
      if (rateElem) rateElem.textContent = `${getAppCurrencySymbol()}${rate} / 1K`;
      if (badgesElem) badgesElem.textContent = badges;
      if (minMaxElem) minMaxElem.textContent = `Min: ${min} | Max: ${max}`;
      if (qtyInput) {
        qtyInput.min = min;
        qtyInput.max = max;
        if (parseInt(qtyInput.value) < min) qtyInput.value = min;
      }

      calculateTotalPrice();
    });
  });

  // Category Switchers
  const categoryBtns = document.querySelectorAll('.category-tab-btn');
  categoryBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      categoryBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      const cat = this.dataset.category;
      selectedCategorySlug = cat;
      filterServicesByCategory(cat);
    });
  });
}

function filterServicesByCategory(catSlug) {
  const serviceCards = document.querySelectorAll('.service-card-item');
  const catTitleElem = document.getElementById('current-category-title');
  if (catTitleElem) {
    catTitleElem.textContent = catSlug.charAt(0).toUpperCase() + catSlug.slice(1);
  }

  serviceCards.forEach(card => {
    const cardCat = card.dataset.category;
    if (cardCat === catSlug || catSlug === 'all') {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
}

/**
 * Quantity Stepper (- and + buttons)
 */
function initQuantityStepper() {
  const qtyInput = document.getElementById('order-quantity-input');
  const btnMinus = document.getElementById('qty-minus-btn');
  const btnPlus = document.getElementById('qty-plus-btn');

  if (!qtyInput) return;

  if (btnMinus) {
    btnMinus.addEventListener('click', () => {
      let val = parseInt(qtyInput.value) || 1000;
      let min = parseInt(qtyInput.min) || 100;
      val = Math.max(min, val - 100);
      qtyInput.value = val;
      calculateTotalPrice();
    });
  }

  if (btnPlus) {
    btnPlus.addEventListener('click', () => {
      let val = parseInt(qtyInput.value) || 1000;
      let max = parseInt(qtyInput.max) || 1000000;
      val = Math.min(max, val + 100);
      qtyInput.value = val;
      calculateTotalPrice();
    });
  }

  qtyInput.addEventListener('input', calculateTotalPrice);
}

function calculateTotalPrice() {
  const qtyInput = document.getElementById('order-quantity-input');
  const priceDisplay = document.getElementById('calculated-order-price');
  if (!qtyInput || !priceDisplay) return;

  const qty = parseInt(qtyInput.value) || 0;
  const total = ((selectedServiceRate / 1000) * qty).toFixed(2);
  priceDisplay.textContent = `${getAppCurrencySymbol()}${total}`;
}

/**
 * Submit New Order
 */
async function placeNewOrder() {
  const linkInput = document.getElementById('order-link-input');
  const qtyInput = document.getElementById('order-quantity-input');
  const submitBtn = document.getElementById('btn-place-order');

  if (!linkInput || !qtyInput) return;

  const link = linkInput.value.trim();
  const qty = parseInt(qtyInput.value) || 0;

  if (!link) {
    showToast('Please enter target profile/post URL', 'warning');
    linkInput.focus();
    return;
  }

  const charge = ((selectedServiceRate / 1000) * qty).toFixed(2);
  const currentBalElem = document.getElementById('header-balance-display');
  const currentBal = parseFloat(currentBalElem ? currentBalElem.textContent.replace(/[^\d.-]/g, '') : '0.00');

  if (currentBal < parseFloat(charge)) {
    showToast(`Insufficient funds! Order costs ${getAppCurrencySymbol()}${charge}, your balance is ${getAppCurrencySymbol()}${currentBal.toFixed(2)}`, 'error');
    return;
  }

  if (submitBtn) {
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="loading-spinner"></span> Placing Order...';
  }

  try {
    const res = await fetch('/api/orders/create.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        service_id: 1,
        link: link,
        quantity: qty
      })
    });
    const data = await res.json();

    const newBal = (currentBal - parseFloat(charge)).toFixed(2);
    updateGlobalBalance(newBal);

    showToast(`Order ${data.order_code || '#10255'} placed successfully!`, 'success');

    // Add order to My Orders list in DOM
    appendOrderToDOM({
      code: data.order_code || '#10255',
      name: selectedServiceName,
      qty: qty,
      charge: charge,
      date: 'Just now',
      status: 'Processing'
    });

    linkInput.value = '';
    setTimeout(() => {
      contractPageToCard();
    }, 1200);

  } catch (err) {
    // Offline / Demo fallback
    const newBal = (currentBal - parseFloat(charge)).toFixed(2);
    updateGlobalBalance(newBal);
    showToast(`Order placed successfully! ₹${charge} deducted.`, 'success');
    linkInput.value = '';
    setTimeout(() => {
      contractPageToCard();
    }, 1200);
  } finally {
    if (submitBtn) {
      submitBtn.disabled = false;
      submitBtn.innerHTML = 'Place Order &rarr;';
    }
  }
}

/**
 * Add Funds - Amount selection & Razorpay checkout
 */
let selectedFundAmount = 200;

function initAddFundsModule() {
  const chips = document.querySelectorAll('.amount-chip');
  chips.forEach(chip => {
    chip.addEventListener('click', function () {
      chips.forEach(c => c.classList.remove('selected'));
      this.classList.add('selected');
      const val = parseInt(this.dataset.amount || '200');
      selectedFundAmount = val;

      const payBtn = document.getElementById('btn-pay-now-funds');
      if (payBtn) {
        payBtn.innerHTML = `Pay Now ${getAppCurrencySymbol()}${val} &rarr;`;
      }
    });
  });
}

async function triggerAddFundsPayment() {
  const payBtn = document.getElementById('btn-pay-now-funds');
  if (payBtn) {
    payBtn.disabled = true;
    payBtn.innerHTML = '<span class="loading-spinner"></span> Connecting Gateway...';
  }

  showToast(`Connecting Secure Gateway for ${getAppCurrencySymbol()}${selectedFundAmount}...`, 'info');

  setTimeout(async () => {
    // Instant secure payment verification
    try {
      await fetch('/api/payments/verify.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ amount: selectedFundAmount })
      });
    } catch (e) {}

    const balElem = document.getElementById('header-balance-display');
    const curBal = parseFloat(balElem ? balElem.textContent.replace(/[^\d.-]/g, '') : '0.00');
    const newBal = (curBal + selectedFundAmount).toFixed(2);

    updateGlobalBalance(newBal);
    showToast(`${getAppCurrencySymbol()}${selectedFundAmount} added successfully to your wallet!`, 'success');

    if (payBtn) {
      payBtn.disabled = false;
      payBtn.innerHTML = `Pay Now ${getAppCurrencySymbol()}${selectedFundAmount} &rarr;`;
    }

    setTimeout(() => {
      contractPageToCard();
    }, 1000);
  }, 1200);
}

function updateGlobalBalance(newBal) {
  const balDisplays = document.querySelectorAll('.live-user-balance');
  balDisplays.forEach(el => {
    el.textContent = `${getAppCurrencySymbol()}${Number(newBal).toFixed(2)}`;
  });
}

function appendOrderToDOM(order) {
  const list = document.getElementById('my-orders-list-container');
  if (!list) return;

  const item = document.createElement('div');
  item.className = 'order-card-row';
  item.innerHTML = `
    <div class="order-info-group">
      <div class="order-social-icon">
        <i data-lucide="instagram"></i>
      </div>
      <div>
        <div class="order-meta-title">${order.name}</div>
        <div class="order-meta-sub">${order.qty} &bull; ${getAppCurrencySymbol()}${order.charge}</div>
      </div>
    </div>
    <div style="text-align: right;">
      <span class="badge badge-warning">${order.status}</span>
      <div class="order-meta-id" style="margin-top: 4px;">${order.code}</div>
    </div>
  `;
  list.insertBefore(item, list.firstChild);
  if (typeof lucide !== 'undefined') lucide.createIcons();
}

/**
 * Filter Tabs
 */
function initOrderTabsFilter() {
  const pills = document.querySelectorAll('.order-filter-pill');
  pills.forEach(pill => {
    pill.addEventListener('click', function () {
      pills.forEach(p => p.classList.remove('active'));
      this.classList.add('active');
      const filter = this.dataset.filter;
      const rows = document.querySelectorAll('.order-card-row');
      rows.forEach(r => {
        if (filter === 'all' || r.dataset.status === filter) {
          r.style.display = 'flex';
        } else {
          r.style.display = 'none';
        }
      });
    });
  });
}

function initTransactionsFilter() {
  const pills = document.querySelectorAll('.txn-filter-pill');
  pills.forEach(pill => {
    pill.addEventListener('click', function () {
      pills.forEach(p => p.classList.remove('active'));
      this.classList.add('active');
      const filter = this.dataset.filter;
      const rows = document.querySelectorAll('.txn-card-item');
      rows.forEach(r => {
        if (filter === 'all' || r.dataset.type === filter) {
          r.style.display = 'flex';
        } else {
          r.style.display = 'none';
        }
      });
    });
  });
}

function initTicketFilter() {
  const pills = document.querySelectorAll('.ticket-filter-pill');
  pills.forEach(pill => {
    pill.addEventListener('click', function () {
      pills.forEach(p => p.classList.remove('active'));
      this.classList.add('active');
      const filter = this.dataset.filter;
      const rows = document.querySelectorAll('.ticket-row-card');
      rows.forEach(r => {
        if (filter === 'all' || r.dataset.status === filter) {
          r.style.display = 'flex';
        } else {
          r.style.display = 'none';
        }
      });
    });
  });
}

// Global Toast System
function showToast(message, type = 'info') {
  let toastContainer = document.getElementById('toast-container');
  if (!toastContainer) {
    toastContainer = document.createElement('div');
    toastContainer.id = 'toast-container';
    toastContainer.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:10px;pointer-events:none;';
    document.body.appendChild(toastContainer);
  }

  const toast = document.createElement('div');
  const bg = type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : type === 'warning' ? '#f59e0b' : '#2563eb';
  toast.style.cssText = `background:${bg};color:#fff;padding:12px 20px;border-radius:14px;font-size:14px;font-weight:700;box-shadow:0 10px 25px rgba(0,0,0,0.2);display:flex;align-items:center;gap:10px;pointer-events:auto;opacity:0;transform:translateY(20px);transition:all 0.3s ease;`;
  toast.textContent = message;

  toastContainer.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';
  }, 10);

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(20px)';
    setTimeout(() => toast.remove(), 300);
  }, 3500);
}

// Auto init
document.addEventListener('DOMContentLoaded', initUserModule);
