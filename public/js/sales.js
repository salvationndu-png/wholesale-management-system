// /js/sales.js
document.addEventListener('DOMContentLoaded', function () {
  const itemsContainer = document.getElementById('itemsContainer');
  const addItemBtn = document.getElementById('addItem');
  const grandTotalEl = document.getElementById('grandTotal');
  const salesForm = document.getElementById('salesForm');
  const CSRF = document.querySelector('meta[name="csrf-token"]').content;
  const toastContainer = document.getElementById('toastContainer');

  function showToast(message, type = 'success') {
    const bg = type === 'success' ? 'bg-emerald-600' : 'bg-rose-600';
    const el = document.createElement('div');
    el.className = `px-3 py-2 rounded-md text-white ${bg} shadow-lg anim-fade-in-up`;
    el.textContent = message;
    toastContainer.appendChild(el);
    setTimeout(() => el.classList.add('opacity-0'), 2400);
    setTimeout(() => el.remove(), 3000);
  }

  function parseNumber(str) {
    if (str === undefined || str === null) return NaN;
    const cleaned = String(str).replace(/[^0-9.\-]+/g, '');
    return cleaned === '' ? NaN : parseFloat(cleaned);
  }

  function formatDisplayNumber(n) {
    if (n === null || n === undefined || isNaN(Number(n))) return '';
    return Number(n).toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function formatNaira(n) {
    const disp = formatDisplayNumber(n);
    return disp === '' ? '₦0.00' : '₦' + disp;
  }

  // Responsive item row: stacked on small, horizontal on md+
  function addItemCard() {
    const tpl = document.getElementById('productOptions');
    const opts = tpl ? tpl.innerHTML : '<option value="" disabled>Select product</option>';

    const row = document.createElement('div');

    row.className = 'item-card flex flex-col md:flex-row md:items-center gap-2 md:gap-3 bg-white rounded-md shadow p-2';
    row.innerHTML = `
      <select name="product[]" class="product-select w-full md:w-[220px] h-10 px-2 rounded-md border border-slate-200 text-sm">
        ${opts}
      </select>

      <input name="quantity[]" type="number" min="1" class="quantity w-full md:w-20 h-10 px-2 rounded-md border border-slate-200 text-sm" placeholder="Qty" required />

      <input name="price[]" type="text" readonly class="price w-full md:w-28 h-10 px-2 rounded-md border border-slate-200 text-sm bg-slate-50" placeholder="Price" required />

      <div class="flex justify-between md:flex-col md:items-start w-full md:w-28 text-xs text-slate-500">
        <span class="available-qty">Avail: -</span>
        <span class="current-price">Price: —</span>
      </div>

      <div class="flex items-center justify-between md:flex-col md:items-end w-full md:w-28">
        <div class="text-sm text-slate-700">Total</div>
        <div class="item-total font-medium">₦0.00</div>
      </div>

      <button type="button" class="removeItem text-xs text-rose-600 hover:underline md:ml-2">Remove</button>
    `;

    itemsContainer.appendChild(row);

    // focus the product-select for fast entry
    const sel = row.querySelector('.product-select');
    sel && sel.focus();
  }

  function renderLineTotal(card) {
    const qty = Number(card.querySelector('.quantity').value) || 0;
    const priceRaw = parseNumber(card.querySelector('.price').value) || 0;
    const total = qty * priceRaw;
    const out = card.querySelector('.item-total');
    if (out) out.textContent = formatNaira(total);
  }

  function updateTotals() {
    let total = 0;
    document.querySelectorAll('.item-card').forEach(card => {
      const qty = Number(card.querySelector('.quantity').value) || 0;
      const priceRaw = parseNumber(card.querySelector('.price').value) || 0;
      total += qty * priceRaw;
    });
    grandTotalEl.textContent = formatNaira(total);
  }

  function clampQtyToAvailable(card) {
    const qtyInput = card.querySelector('.quantity');
    const availEl = card.querySelector('.available-qty');
    const available = Number(availEl.dataset.available || 0);
    const val = qtyInput.valueAsNumber || 0;
    if (available > 0 && val > available) {
      qtyInput.value = available;
      showToast('Quantity exceeds available stock. Adjusted to max available.', 'error');
    }
  }

  // delegated change on product select
  itemsContainer.addEventListener('change', function (e) {
    const select = e.target.closest('.product-select');
    if (!select) return;

    const card = select.closest('.item-card');
    const option = select.selectedOptions[0];
    const priceInput = card.querySelector('.price');
    const availEl = card.querySelector('.available-qty');
    const currentPriceText = card.querySelector('.current-price');

    // fallback immediate price from option dataset
    const optPrice = option?.dataset?.price;
    if (optPrice !== undefined && optPrice !== '') {
      const num = parseNumber(optPrice);
      priceInput.value = isNaN(num) ? '' : formatDisplayNumber(num);
      currentPriceText.textContent = isNaN(num) ? 'Price: —' : 'Price: ' + formatNaira(num);
      renderLineTotal(card);
      updateTotals();
    } else {
      priceInput.value = '';
      currentPriceText.textContent = 'Price: —';
    }

    const productId = select.value;
    if (!productId) return;

    fetch(`/get-product-details/${productId}`)
      .then(r => r.json())
      .then(data => {
        if (data && data.success) {
          const available = Number(data.quantity || 0);
          availEl.textContent = `Avail: ${available}`;
          availEl.dataset.available = available;

          if (data.price !== undefined && data.price !== null && data.price !== '') {
            const pnum = parseNumber(data.price);
            priceInput.value = isNaN(pnum) ? '' : formatDisplayNumber(pnum);
            currentPriceText.textContent = isNaN(pnum) ? 'Price: —' : 'Price: ' + formatNaira(pnum);
          }

          clampQtyToAvailable(card);
          renderLineTotal(card);
          updateTotals();
        } else {
          availEl.textContent = 'Avail: N/A';
          availEl.dataset.available = '';
          showToast(data?.message || 'Could not fetch product details.', 'error');
        }
      })
      .catch(err => {
        console.error('Error fetching product details:', err);
        availEl.textContent = 'Avail: N/A';
        availEl.dataset.available = '';
        showToast('Error fetching product details.', 'error');
      });
  });

  // quantity / price input & remove
  itemsContainer.addEventListener('input', function (e) {
    const t = e.target;
    if (t.classList.contains('quantity') || t.classList.contains('price')) {
      const card = t.closest('.item-card');
      if (t.classList.contains('quantity')) clampQtyToAvailable(card);
      renderLineTotal(card);
      updateTotals();
    }
  });

  itemsContainer.addEventListener('click', function (e) {
    const rem = e.target.closest('.removeItem');
    if (rem) {
      rem.closest('.item-card').remove();
      updateTotals();
    }
  });

  addItemBtn && addItemBtn.addEventListener('click', function () {
    addItemCard();
  });

  // submit
  if (salesForm) {
    salesForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const submitBtn = document.getElementById('submitBtn');
      if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Recording...'; }

      // convert displayed price strings to plain numbers
      const priceInputs = Array.from(salesForm.querySelectorAll('.price'));
      const backupDisplay = priceInputs.map(i => i.value);
      priceInputs.forEach(inp => {
        const n = parseNumber(inp.value);
        inp.value = isNaN(n) ? '' : n.toFixed(2);
      });

      const formData = new FormData(salesForm);

      fetch('/add-sale', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF },
        body: formData
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          showToast('Sale recorded successfully!', 'success');
          salesForm.reset();
          itemsContainer.innerHTML = '';
          grandTotalEl.textContent = '₦0.00';
          addItemCard();
        } else {
          showToast(data.message || 'Failed to record sale.', 'error');
        }
      })
      .catch(err => {
        console.error('submit error', err);
        showToast('Error recording sale.', 'error');
      })
      .finally(() => {
        // restore display values if still in DOM
        priceInputs.forEach((inp, i) => {
          if (document.body.contains(inp)) inp.value = backupDisplay[i] ?? inp.value;
        });
        if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = '✅ Record Sale'; }
      });
    });
  }

  // init
  addItemCard();
});
