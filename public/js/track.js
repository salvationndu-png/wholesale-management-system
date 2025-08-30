document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const startDate = form.start_date.value;
    const endDate = form.end_date.value;

    const salesTableBody = document.getElementById('salesTableBody');
    const totalAmountDisplay = document.getElementById('totalAmountDisplay');

    salesTableBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted small">Loading...</td></tr>`;
    totalAmountDisplay.textContent = '';

    fetch(`/track-sales-data?start_date=${startDate}&end_date=${endDate}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let rows = '';
                if (data.sales.length === 0) {
                    rows = `<tr><td colspan="6" class="text-center text-muted small">No sales found for this range.</td></tr>`;
                } else {
                    data.sales.forEach(sale => {
                        const date = new Date(sale.sale_date).toISOString().split('T')[0];
                        const productName = sale.product ? sale.product.name : 'N/A';
                        rows += `
                            <tr>
                                <td>${date}</td>
                                <td>${productName}</td>
                                <td>${sale.quantity}</td>
                                <td>₦${Number(sale.price).toLocaleString()}</td>
                                <td>₦${Number(sale.total).toLocaleString()}</td>
                                <td>${sale.payment_type}</td>
                            </tr>`;
                    });
                }
                salesTableBody.innerHTML = rows;
                totalAmountDisplay.textContent = `Total Sales: ₦${Number(data.totalAmount).toLocaleString()}`;
            } else {
                salesTableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger small">Error fetching sales data.</td></tr>`;
            }
        })
        .catch(error => {
            console.error(error);
            salesTableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger small">Error fetching sales data.</td></tr>`;
        });
});


