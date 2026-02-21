// public/js/stock.js  (updated to stronger toast colors + badge pills)

// -------------- Helpers --------------
function createSpinner(size = 'sm') {
  const s = size === 'sm' ? 'w-4 h-4' : 'w-5 h-5';
  return `<svg class="${s} animate-spin inline-block" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.15" stroke-width="4"></circle>
    <path d="M22 12a10 10 0 00-10-10" stroke="currentColor" stroke-width="4" stroke-linecap="round"></path>
  </svg>`;
}

// SVG icons used in toasts/badges
const ICON_CHECK = `<svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M16 6L8.5 13.5 5 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
const ICON_X = `<svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M6 6l8 8M14 6l-8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
const ICON_WARN = `<svg class="w-4 h-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M10 4v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="10" cy="14" r="1" fill="currentColor"/></svg>`;

// showToast: strong color variants (success = green, error = red, warn = amber)
function showToast(message, type = 'success') {
  const container = document.getElementById('toastContainer');
  if (!container) return console.warn('Missing toast container');

  const id = 'toast-' + Date.now();
  const wrapper = document.createElement('div');
  wrapper.id = id;

  // styling: strong colors for quick scanning
  let style = '';
  let icon = '';
  if (type === 'error') {
    style = 'bg-rose-600 text-white';
    icon = ICON_X;
  } else if (type === 'warn') {
    style = 'bg-amber-500 text-white';
    icon = ICON_WARN;
  } else {
    style = 'bg-emerald-600 text-white';
    icon = ICON_CHECK;
  }

  wrapper.className = `flex items-center gap-3 rounded-lg shadow-md max-w-xs animate-fade-in-up px-4 py-3 ${style}`;
  wrapper.innerHTML = `
    <span class="shrink-0">${icon}</span>
    <div class="flex-1 text-sm">${escapeHtml(message)}</div>
    <button aria-label="Close toast" class="ml-2 p-1 rounded hover:bg-white/10 focus-ring">✕</button>
  `;

  container.appendChild(wrapper);

  // close on button
  const closeBtn = wrapper.querySelector('button');
  closeBtn.addEventListener('click', () => wrapper.remove());

  // auto remove after 3.5s
  setTimeout(() => wrapper.remove(), 3500);
}

function formatPrice(p) {
  if (p === null || p === undefined || p === '') return '—';
  const num = Number(p);
  if (Number.isFinite(num)) {
    return '₦' + num.toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }
  return p;
}

// -------------- DOM helpers for modals --------------
function showModalById(id) { const el = document.getElementById(id); if (el) { el.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); const f = el.querySelector('input, button, select, textarea'); if (f) f.focus(); } }
function hideModalById(id) { const el = document.getElementById(id); if (el) { el.classList.add('hidden'); if (!document.querySelector('[role="dialog"]:not(.hidden)')) document.body.classList.remove('overflow-hidden'); } }

// -------------- Load stock --------------
async function loadStock() {
  try {
    const res = await fetch('/stock-list');
    if (!res.ok) throw new Error('Network error');
    const data = await res.json();

    const table = document.getElementById('stockTable');
    table.innerHTML = '';

    data.forEach((item, index) => {
      const tr = document.createElement('tr');

      // badge pills (strong, accessible)
      let badgeHTML = '';
      if (Number(item.quantity) === 0) {
        badgeHTML = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-600 text-white ml-2">
                       ${ICON_X}<span class="ml-1">Sold Out</span>
                     </span>`;
      } else if (Number(item.quantity) < 10) {
        badgeHTML = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-500 text-white ml-2">
                       ${ICON_WARN}<span class="ml-1">Low Stock</span>
                     </span>`;
      }

      tr.innerHTML = `
        <td class="px-3 py-2 align-top">${index + 1}</td>
        <td class="px-3 py-2 align-top">${escapeHtml(item.product_name ?? 'Unknown')}</td>
        <td class="px-3 py-2 align-top">${item.quantity ?? 0} bundles ${badgeHTML}</td>
        <td class="px-3 py-2 align-top price-cell">${formatPrice(item.price)}</td>
        <td class="px-3 py-2 align-top">
          <button class="delete-button inline-flex items-center gap-2 px-2 py-1 rounded text-rose-600 hover:bg-rose-50 focus-ring" data-id="${item.id}" aria-label="Delete">
            ${trashSvg()}
          </button>
        </td>
        <td class="px-3 py-2 align-top">
          <button class="inc-button inline-flex items-center gap-2 px-2 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 focus-ring" 
                  data-id="${item.id}" data-product="${escapeAttr(item.product_name ?? '')}" data-qty="${item.quantity ?? 0}" aria-label="Increase">
            ➕
          </button>
          <button  style="color:white;background-color:rgba(238, 40, 40, 0.71)" class="price-button inline-flex items-center gap-2 px-2 py-1 rounded bg-amber-100 hover:bg-amber-200 text-amber-800 focus-ring ml-2"
                  data-id="${item.id}" data-price="${item.price ?? ''}" aria-label="Edit price">
            ₦
          </button>
        </td>
      `;

      table.appendChild(tr);
    });
  } catch (err) {
    console.error(err);
    showToast('Failed to load stock data.', 'error');
  }
}

// -------------- Utility small helpers --------------
function escapeHtml(str = '') {
  return String(str).replace(/[&<>"']/g, (m) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}
function escapeAttr(s = '') {
  return String(s).replace(/"/g, '&quot;');
}
function trashSvg() {
  return `<svg class="w-4 h-4 text-current" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5.5 5.5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1 0-2h3.1a1 1 0 0 1 .95-.684h2.9A1 1 0 0 1 9.4 2H12.5a1 1 0 0 1 1 1zm-10 1v9a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4H4.5z"/></svg>`;
}

// -------------- Event delegation for table actions --------------
document.addEventListener('click', function (e) {
  const del = e.target.closest('.delete-button');
  const inc = e.target.closest('.inc-button');
  const price = e.target.closest('.price-button');

  if (del) {
    deleteStock(del.dataset.id, del);
    return;
  }
  if (inc) {
    openIncrementModal(inc.dataset.id, inc.dataset.product, inc.dataset.qty);
    return;
  }
  if (price) {
    openPriceModal(price.dataset.id, price.dataset.price);
    return;
  }
});

// -------------- Delete stock --------------
async function deleteStock(stockId, btn) {
  if (!confirm('Are you sure you want to delete this stock entry?')) return;
  const original = btn.innerHTML;
  btn.innerHTML = createSpinner('sm');
  btn.disabled = true;

  try {
    const res = await fetch(`/delete-stock/${stockId}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
    });
    const data = await res.json();
    if (data.success) {
      await loadStock();
      showToast('Stock deleted successfully!', 'success');
    } else {
      showToast(data.message || 'Failed to delete stock.', 'error');
    }
  } catch (err) {
    console.error(err);
    showToast('An error occurred while deleting stock.', 'error');
  } finally {
    btn.innerHTML = original;
    btn.disabled = false;
  }
}

// -------------- Submit new stock --------------
document.addEventListener('DOMContentLoaded', () => {
  const stockForm = document.getElementById('stockForm');
  if (stockForm) {
    stockForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      const submitBtn = document.getElementById('submitBtn');
      const originalText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = `${createSpinner('sm')} Uploading...`;

      const payload = {
        product_id: document.getElementById('productSelect').value,
        quantity: document.getElementById('quantityInput').value,
        price: document.getElementById('priceInput').value,
        date: document.getElementById('dateInput').value,
      };

      try {
        const res = await fetch('/add-stock', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
          body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.success) {
          await loadStock();
          stockForm.reset();
          document.getElementById('dateInput').value = new Date().toISOString().slice(0,10);
          showToast('Stock added successfully!', 'success');
        } else {
          showToast(data.message || 'Failed to add stock.', 'error');
        }
      } catch (err) {
        console.error(err);
        showToast('An error occurred while adding stock.', 'error');
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
      }
    });
  }

  // Initial load
  loadStock();
});

// -------------- Increment modal logic --------------
let currentStockId = null;
function openIncrementModal(stockId, productName, currentQuantity) {
  currentStockId = stockId;
  document.getElementById('modalProductName').textContent = productName || '';
  document.getElementById('modalCurrentQuantity').textContent = currentQuantity ?? '0';
  document.getElementById('additionalQuantity').value = '';
  showModalById('incrementModal');
}

document.getElementById('saveIncrementBtn').addEventListener('click', async function () {
  const btn = this;
  const additionalQuantity = Number(document.getElementById('additionalQuantity').value);
  if (!additionalQuantity || additionalQuantity <= 0) { showToast('Enter a valid quantity', 'error'); return; }

  const originalText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = `${createSpinner('sm')} Saving...`;

  try {
    const res = await fetch(`/update-stock/${currentStockId}`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
      body: JSON.stringify({ additional_quantity: additionalQuantity })
    });
    const data = await res.json();
    if (data.success) {
      showToast('Stock updated!', 'success');
      await loadStock();
      hideModalById('incrementModal');
    } else {
      showToast(data.message || 'Failed to update stock.', 'error');
    }
  } catch (err) {
    console.error(err);
    showToast('An error occurred.', 'error');
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalText;
  }
});

// -------------- Edit price modal --------------
let currentPriceStockId = null;
function openPriceModal(stockId, currentPrice) {
  currentPriceStockId = stockId;
  const num = Number(currentPrice);
  document.getElementById('editPriceInput').value = Number.isFinite(num) ? num.toFixed(2) : (currentPrice ?? '');
  showModalById('priceModal');
}

document.getElementById('savePriceBtn').addEventListener('click', async function () {
  const btn = this;
  const newPriceRaw = (document.getElementById('editPriceInput').value || '').toString().trim();
  if (!newPriceRaw) { showToast('Enter valid price', 'error'); return; }

  const originalText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = `${createSpinner('sm')} Saving...`;

  try {
    const res = await fetch(`/update-price/${currentPriceStockId}`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
      body: JSON.stringify({ price: newPriceRaw })
    });
    const data = await res.json();
    if (data.success) {
      showToast('Price updated!', 'success');
      await loadStock();
      hideModalById('priceModal');
    } else {
      showToast(data.message || 'Failed to update price.', 'error');
    }
  } catch (err) {
    console.error(err);
    showToast('Error updating price.', 'error');
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalText;
  }
});
