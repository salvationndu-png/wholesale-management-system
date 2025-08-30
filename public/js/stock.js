// ---------- Helpers ----------
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toastContainer');
    const toastId = 'toast' + Date.now();
    const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white ${bgClass} border-0`;
    toast.id = toastId;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>`;
    toastContainer.appendChild(toast);
    new bootstrap.Toast(toast).show();
    toast.addEventListener('hidden.bs.toast', () => toast.remove());
}

function formatPrice(p) {
    if (p === null || p === undefined || p === '') return '—';
    const num = Number(p);
    if (Number.isFinite(num)) {
        return '₦' + num.toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    return p;
}

// ---------- Load stock ----------
function loadStock() {
    fetch('/stock-list')
        .then(res => res.json())
        .then(data => {
            const table = document.getElementById('stockTable');
            table.innerHTML = '';
            data.forEach((item, index) => {
                const tr = document.createElement('tr');

                // Badge logic
                let qtyBadge = '';
                if (item.quantity == 0) {
                    qtyBadge = ` <span class="badge bg-danger">Sold Out</span>`;
                } else if (item.quantity < 10) {
                    qtyBadge = ` <span class="badge bg-warning text-dark">Low Stock</span>`;
                }

                tr.innerHTML = `
                    <td class="px-3 py-2">${index + 1}</td>
                    <td class="px-3 py-2">${item.product_name ?? 'Unknown'}</td>
                    <td class="px-3 py-2">${item.quantity}${qtyBadge}</td>
                    <td class="px-3 py-2 price-cell">${formatPrice(item.price)}</td>
                    <td class="px-3 py-2">
                        <button class="btn btn-sm text-danger btn-delete" data-id="${item.id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                    <td class="px-3 py-2">
                        <button class="btn btn-sm btn-primary btn-inc" 
                                data-id="${item.id}" 
                                data-product="${(item.product_name ?? '').replace(/"/g,'&quot;')}" 
                                data-qty="${item.quantity}">
                            ➕
                        </button>
                        <button class="btn btn-sm btn-warning btn-edit-price" 
                                data-id="${item.id}" 
                                data-price="${item.price}">
                            💲
                        </button>
                    </td>
                `;

                table.appendChild(tr);
            });
        })
        .catch(() => showToast('Failed to load stock data.', 'error'));
}

// ---------- Event delegation for table actions ----------
document.getElementById('stockTable').addEventListener('click', function(e) {
    const delBtn = e.target.closest('.btn-delete');
    const incBtn = e.target.closest('.btn-inc');
    const priceBtn = e.target.closest('.btn-edit-price');

    if (delBtn) {
        deleteStock(delBtn.dataset.id, delBtn);
        return;
    }
    if (incBtn) {
        openIncrementModal(incBtn.dataset.id, incBtn.dataset.product, incBtn.dataset.qty);
        return;
    }
    if (priceBtn) {
        openPriceModal(priceBtn.dataset.id, priceBtn.dataset.price);
        return;
    }
});

// ---------- Delete stock ----------
function deleteStock(stockId, btn) {
    if (!confirm('Are you sure you want to delete this stock entry?')) return;
    const original = btn.innerHTML;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span>`;
    btn.disabled = true;

    fetch(`/delete-stock/${stockId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) { loadStock(); showToast('Stock deleted successfully!'); }
        else { showToast(data.message || 'Failed to delete stock.', 'error'); }
    })
    .catch(() => showToast('An error occurred while deleting stock.', 'error'))
    .finally(() => { btn.innerHTML = original; btn.disabled = false; });
}

// ---------- Submit new stock ----------
document.getElementById('stockForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Uploading...`;

    const data = {
        product_id: document.getElementById('productSelect').value,
        quantity: document.getElementById('quantityInput').value,
        price: document.getElementById('priceInput').value,
        date: document.getElementById('dateInput').value,
    };

    fetch('/add-stock', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadStock();
            document.getElementById('stockForm').reset();
            document.getElementById('dateInput').value = '{{ now()->toDateString() }}';
            showToast('Stock added successfully!');
        } else { showToast(data.message || 'Failed to add stock.', 'error'); }
    })
    .catch(() => showToast('An error occurred while adding stock.', 'error'))
    .finally(() => { submitBtn.disabled = false; submitBtn.textContent = 'Add Stock'; });
});

// ---------- Increment modal ----------
let currentStockId = null;
function openIncrementModal(stockId, productName, currentQuantity) {
    currentStockId = stockId;
    document.getElementById('modalProductName').textContent = productName || '';
    document.getElementById('modalCurrentQuantity').textContent = currentQuantity || '0';
    document.getElementById('additionalQuantity').value = '';
    new bootstrap.Modal(document.getElementById('incrementModal')).show();
}
document.getElementById('saveIncrementBtn').addEventListener('click', function() {
    const additionalQuantity = document.getElementById('additionalQuantity').value;
    if (!additionalQuantity || additionalQuantity <= 0) return showToast('Enter a valid quantity', 'error');
    this.disabled = true; this.textContent = 'Saving...';

    fetch(`/update-stock/${currentStockId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ additional_quantity: additionalQuantity })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Stock updated!');
            loadStock();
            bootstrap.Modal.getInstance(document.getElementById('incrementModal')).hide();
        } else { showToast(data.message || 'Failed to update stock.', 'error'); }
    })
    .catch(() => showToast('An error occurred.', 'error'))
    .finally(() => { this.disabled = false; this.textContent = 'Save'; });
});

// ---------- Edit price modal ----------
let currentPriceStockId = null;
function openPriceModal(stockId, currentPrice) {
    currentPriceStockId = stockId;
    const num = Number(currentPrice);
    document.getElementById('editPriceInput').value = Number.isFinite(num) ? num.toFixed(2) : (currentPrice ?? '');
    new bootstrap.Modal(document.getElementById('priceModal')).show();
}

document.getElementById('savePriceBtn').addEventListener('click', function() {
    const newPriceRaw = document.getElementById('editPriceInput').value?.toString().trim();

    if (!newPriceRaw) return showToast('Enter valid price', 'error');

    this.disabled = true; this.textContent = 'Saving...';

    fetch(`/update-price/${currentPriceStockId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ price: newPriceRaw })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Price updated!');
            loadStock();
            bootstrap.Modal.getInstance(document.getElementById('priceModal')).hide();
        } else { showToast(data.message || 'Failed to update price.', 'error'); }
    })
    .catch(() => showToast('Error updating price.', 'error'))
    .finally(() => { this.disabled = false; this.textContent = 'Save'; });
});

// ---------- Initial load ----------
document.addEventListener('DOMContentLoaded', loadStock);
