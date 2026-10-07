/**
 * SMM Panel - Admin Panel Business Logic
 * Provider Management, Service Mapping, Orders
 */

function initAdminModule() {
  initProviderModal();
}

function initProviderModal() {
  const openBtn = document.getElementById('btn-open-add-provider');
  const modal = document.getElementById('modal-add-provider');
  const closeBtn = document.getElementById('btn-close-provider-modal');
  const form = document.getElementById('form-add-provider');

  if (openBtn && modal) {
    openBtn.addEventListener('click', () => modal.classList.add('active'));
  }
  if (closeBtn && modal) {
    closeBtn.addEventListener('click', () => modal.classList.remove('active'));
  }

  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const name = document.getElementById('prov-name').value;
      const url = document.getElementById('prov-url').value;
      const key = document.getElementById('prov-key').value;

      try {
        const res = await fetch('/api/admin/providers.php?action=create', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name, api_url: url, api_key: key })
        });
        const data = await res.json();
        if (data.success) {
          showToast('Provider added and API connected successfully!', 'success');
          modal.classList.remove('active');
          setTimeout(() => location.reload(), 800);
        } else {
          showToast(data.message || 'Error connecting to provider API', 'error');
        }
      } catch (err) {
        showToast('Connection error: could not contact server', 'error');
        modal.classList.remove('active');
      }
    });
  }
}

async function syncProviderBalance(providerId, btnElem) {
  if (btnElem) {
    btnElem.innerHTML = '<span class="loading-spinner"></span> Syncing...';
  }

  try {
    const res = await fetch('/api/admin/providers.php?action=sync_balance', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: providerId })
    });
    const data = await res.json();
    if (data.success) {
      showToast(data.message, 'success');
      const balElem = document.getElementById(`prov-bal-${providerId}`);
      if (balElem) balElem.textContent = `$${data.balance.toFixed(2)}`;
    } else {
      showToast(data.message || 'Sync failed', 'error');
    }
  } catch (err) {
    showToast('Balance sync error: could not connect to provider API', 'error');
  } finally {
    if (btnElem) {
      btnElem.innerHTML = '<i data-lucide="refresh-cw"></i> Sync';
      if (typeof lucide !== 'undefined') lucide.createIcons();
    }
  }
}

async function toggleProviderStatus(providerId, currentStatus) {
  const newStatus = currentStatus === 'active' ? 'disabled' : 'active';
  try {
    await fetch('/api/admin/providers.php?action=toggle_status', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: providerId, status: newStatus })
    });
    showToast(`Provider marked as ${newStatus}`, 'success');
    setTimeout(() => location.reload(), 600);
  } catch (e) {
    showToast(`Status toggled to ${newStatus}`, 'info');
  }
}

document.addEventListener('DOMContentLoaded', initAdminModule);
