// /js/sales.js
document.addEventListener('DOMContentLoaded', function () {
  const itemsContainer = document.getElementById('itemsContainer');
  const addItemBtn = document.getElementById('addItem');
  const grandTotalEl = document.getElementById('grandTotal');
  const salesForm = document.getElementById('salesForm');
  const CSRF = document.querySelector('meta[name="csrf-token"]').content;
  const toastContainer = document.getElementById('toastContainer');

  // ------------------- Toast -------------------
  function showToast(message, type = 'success') {
    if (!toastContainer) return;
    const bg = type === 'success' ? 'bg-emerald-600' : 'bg-red-600';
    const el = document.createElement('div');
    el.className = `relative px-3 py-2 rounded-md text-white ${bg} shadow-lg flex items-center justify-between anim-fade-in-up`;
    el.innerHTML = `
      <span>${message}</span>
      <button class="ml-2 text-white font-bold" style="line-height:1" onclick="this.parentElement.remove()">×</button>
    `;
    toastContainer.appendChild(el);
    setTimeout(() => el.classList.add('opacity-0'), 2400);
    setTimeout(() => el.remove(), 3000);
  }
  

  // ------------------- Number Utilities -------------------
  function parseNumber(str) {
    if (str == null) return NaN;
    const cleaned = String(str).replace(/[^0-9.\-]+/g, '');
    return cleaned === '' ? NaN : parseFloat(cleaned);
  }

  function formatDisplayNumber(n) {
    if (n == null || isNaN(Number(n))) return '';
    return Number(n).toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function formatNaira(n) {
    const disp = formatDisplayNumber(n);
    return disp === '' ? '₦0.00' : '₦' + disp;
  }

  // ------------------- Thermal POS Receipt -------------------
  function printThermalReceipt(items, grandTotal) {
    const receiptContent = `
      <div style="width: 80mm; font-family: monospace; font-size: 12px; padding: 10px;">
        <div style="text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 5px;">LOVE HILLS</div>
        <div style="text-align: center; font-size: 11px; margin-bottom: 10px;">Love Hills Plaza, Mandilas<br>Lagos Island</div>
        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
        <div style="text-align: center; font-weight: bold; margin-bottom: 10px;">SALES RECEIPT</div>
        <div style="font-size: 11px; margin-bottom: 10px;">Date: ${new Date().toLocaleString()}</div>
        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
        <table style="width: 100%; font-size: 11px;">
          <thead>
            <tr>
              <th style="text-align: left;">Item</th>
              <th style="text-align: center;">Qty</th>
              <th style="text-align: right;">Price</th>
              <th style="text-align: right;">Total</th>
            </tr>
          </thead>
          <tbody>
            ${items.map(item => `
              <tr>
                <td style="text-align: left;">${item.product}</td>
                <td style="text-align: center;">${item.quantity}</td>
                <td style="text-align: right;">₦${item.price}</td>
                <td style="text-align: right;">₦${item.total}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>
        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
        <div style="text-align: right; font-weight: bold; font-size: 14px; margin: 10px 0;">TOTAL: ₦${grandTotal}</div>
        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
        <div style="text-align: center; font-size: 11px; margin-top: 15px;">Thank you for shopping with us!</div>
        <div style="text-align: center; font-size: 10px; margin-top: 5px;">Please come again</div>
      </div>
    `;

    const printWindow = window.open('', '', 'width=300,height=600');
    printWindow.document.write('<html><head><title>Receipt</title></head><body>');
    printWindow.document.write(receiptContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
      printWindow.print();
      printWindow.close();
    }, 250);
  }

  // ------------------- Item Card -------------------
  function addItemCard() {
    const tpl = document.getElementById('productOptions');
    const opts = tpl ? tpl.innerHTML : '<option value="" disabled>Select product</option>';

    const row = document.createElement('div');
    row.className = 'item-card flex flex-col md:flex-row md:items-center gap-2 md:gap-3 bg-white rounded-md shadow p-2';
    row.innerHTML = `
      <select name="product[]" class="product-select w-full md:w-[220px] h-10 px-2 rounded-md border border-slate-200 text-sm">${opts}</select>
      <input name="quantity[]" type="number" min="1" class="quantity w-full md:w-20 h-10 px-2 rounded-md border border-slate-200 text-sm" placeholder="Qty" required />
      <input name="price[]" type="text" readonly class="price w-full md:w-28 h-10 px-2 rounded-md border border-slate-200 text-sm bg-slate-50" placeholder="Price" required />
      <div class="flex justify-between md:flex-col md:items-start w-full md:w-28 text-xs text-slate-500">
        <span class="available-qty" data-available="0">Avail: -</span>
        <span class="current-price">Price: —</span>
      </div>
      <div class="flex items-center justify-between md:flex-col md:items-end w-full md:w-28">
        <div class="text-sm text-slate-700">Total</div>
        <div class="item-total font-medium">₦0.00</div>
      </div>
      <button type="button" class="removeItem text-xs text-rose-600 hover:underline md:ml-2">Remove</button>
    `;
    itemsContainer.appendChild(row);
    row.querySelector('.product-select')?.focus();
  }

  // ------------------- Totals -------------------
  function renderLineTotal(card) {
    const qty = Number(card.querySelector('.quantity').value) || 0;
    const priceRaw = parseNumber(card.querySelector('.price').value) || 0;
    card.querySelector('.item-total').textContent = formatNaira(qty * priceRaw);
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
    if (val > available) {
      qtyInput.value = available;
      showToast('Quantity exceeds available stock. Adjusted to max available.', 'error');
    }
  }

  // ------------------- Event Delegation -------------------
  itemsContainer.addEventListener('change', async function (e) {
    const select = e.target.closest('.product-select');
    if (!select) return;

    const card = select.closest('.item-card');
    const option = select.selectedOptions[0];
    const priceInput = card.querySelector('.price');
    const availEl = card.querySelector('.available-qty');
    const currentPriceText = card.querySelector('.current-price');

    const optPrice = option?.dataset?.price;
    if (optPrice != null && optPrice !== '') {
      const num = parseNumber(optPrice);
      priceInput.value = isNaN(num) ? '' : formatDisplayNumber(num);
      currentPriceText.textContent = isNaN(num) ? 'Price: —' : 'Price: ' + formatNaira(num);
      renderLineTotal(card);
      updateTotals();
    }

    const productId = select.value;
    if (!productId) return;

    try {
      const res = await fetch(`/get-product-details/${productId}`);
      const data = await res.json();

      if (data?.success) {
        const available = Number(data.quantity || 0);
        availEl.textContent = `Avail: ${available}`;
        availEl.dataset.available = available;

        if (data.price != null && data.price !== '') {
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
    } catch (err) {
      console.error('Error fetching product details:', err);
      availEl.textContent = 'Avail: N/A';
      availEl.dataset.available = '';
      showToast('Error fetching product details.', 'error');
    }
  });

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

  addItemBtn?.addEventListener('click', addItemCard);

  // ------------------- Submit Form -------------------
  salesForm?.addEventListener('submit', async function (e) {
    e.preventDefault();
    const submitBtn = document.getElementById('submitBtn');
    submitBtn && (submitBtn.disabled = true, submitBtn.textContent = 'Recording...');

    const priceInputs = Array.from(salesForm.querySelectorAll('.price'));
    const backupDisplay = priceInputs.map(i => i.value);
    priceInputs.forEach(inp => {
      const n = parseNumber(inp.value);
      inp.value = isNaN(n) ? '' : n.toFixed(2);
    });

    const formData = new FormData(salesForm);

    try {
      const res = await fetch('/add-sale', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF },
        body: formData
      });
      const data = await res.json();

      if (data.success) {
        showToast('Sale recorded successfully!', 'success');

        const items = Array.from(document.querySelectorAll('.item-card')).map(card => {
          const product = card.querySelector('.product-select').selectedOptions[0]?.textContent || '';
          const quantity = card.querySelector('.quantity').value || '0';
          const priceNum = parseNumber(card.querySelector('.price').value) || 0;
          const totalNum = quantity * priceNum;
          return {
            product,
            quantity,
            price: formatDisplayNumber(priceNum),
            total: formatDisplayNumber(totalNum)
          };
        });

        const grandTotalNum = parseNumber(grandTotalEl.textContent) || 0;
        printThermalReceipt(items, formatDisplayNumber(grandTotalNum));

        salesForm.reset();
        itemsContainer.innerHTML = '';
        grandTotalEl.textContent = '₦0.00';
        addItemCard();
      } else {
        showToast(data.message || 'Failed to record sale.', 'error');
      }
    } catch (err) {
      console.error('submit error', err);
      showToast('Error recording sale.', 'error');
    } finally {
      priceInputs.forEach((inp, i) => document.body.contains(inp) && (inp.value = backupDisplay[i] ?? inp.value));
      submitBtn && (submitBtn.disabled = false, submitBtn.textContent = '✅ Record Sale');
    }
  });

  // ------------------- Init -------------------
  addItemCard();
});
