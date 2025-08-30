  // Elements that must match your dashboard shell
    const sidebar     = document.getElementById('sidebar');
    const openBtn     = document.getElementById('openSidebar');
    const closeBtn    = document.getElementById('closeSidebar');
    const backdrop    = document.getElementById('backdrop');
    const accountBtn  = document.getElementById('accountBtn');
    const accountMenu = document.getElementById('accountMenu');

    // Add Product elements
    const addForm     = document.getElementById('addProductForm');
    const productName = document.getElementById('productName');
    const submitBtn   = document.getElementById('submitBtn');
    const productList = document.getElementById('productList');
    const toast       = document.getElementById('toast');

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Sidebar open/close (mobile)
    function openSidebar() {
      if (!sidebar) return;
      sidebar.classList.remove('-translate-x-full');
      backdrop.classList.remove('pointer-events-none');
      backdrop.classList.add('opacity-100');
      document.documentElement.style.overflow = 'hidden';
      openBtn && openBtn.setAttribute('aria-expanded','true');
    }
    function closeSidebar() {
      if (!sidebar) return;
      sidebar.classList.add('-translate-x-full');
      backdrop.classList.add('pointer-events-none');
      backdrop.classList.remove('opacity-100');
      document.documentElement.style.overflow = '';
      openBtn && openBtn.setAttribute('aria-expanded','false');
    }
    openBtn && openBtn.addEventListener('click', openSidebar);
    closeBtn && closeBtn.addEventListener('click', closeSidebar);
    backdrop && backdrop.addEventListener('click', closeSidebar);

    // Keep sidebar visible on desktop and reset states on resize
    const mql = window.matchMedia('(min-width: 768px)');
    function handleResize() {
      if (!sidebar) return;
      if (mql.matches) {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.add('pointer-events-none');
        backdrop.classList.remove('opacity-100');
        document.documentElement.style.overflow = '';
        openBtn && openBtn.setAttribute('aria-expanded','false');
      } else {
        sidebar.classList.add('-translate-x-full');
      }
    }
    handleResize();
    (mql.addEventListener ? mql.addEventListener('change', handleResize) : window.addEventListener('resize', handleResize));

    // Account dropdown
    function closeDropdown() { if (!accountMenu || !accountBtn) return; accountMenu.classList.add('hidden'); accountBtn.setAttribute('aria-expanded','false'); }
    function toggleDropdown() {
      if (!accountMenu || !accountBtn) return;
      const isHidden = accountMenu.classList.contains('hidden');
      accountMenu.classList.toggle('hidden');
      accountBtn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
    }
    accountBtn && accountBtn.addEventListener('click', (e) => { e.stopPropagation(); toggleDropdown(); });
    document.addEventListener('click', (e) => {
      if (!accountMenu || !accountBtn) return;
      if (!accountMenu.classList.contains('hidden')) {
        const inside = accountMenu.contains(e.target) || accountBtn.contains(e.target);
        if (!inside) closeDropdown();
      }
    });

    // Close on Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') { closeSidebar(); closeDropdown(); }
    });

    // Accessibility: focus first nav link when opening
    openBtn && openBtn.addEventListener('click', () => {
      const firstLink = sidebar.querySelector('nav a');
      if (firstLink) firstLink.focus();
    });

    // ---------- Products CRUD (AJAX) ----------
    async function loadProducts() {
      if (!productList) return;
      try {
        const res = await fetch('/products-list', { headers: { 'Accept':'application/json' }});
        if (!res.ok) throw new Error('Failed to fetch');
        const data = await res.json();
        productList.innerHTML = '';
        (data || []).forEach((product, i) => {
          const tr = document.createElement('tr');
          tr.className = 'hover:bg-slate-50';
          tr.innerHTML = `
            <td class="px-4 py-3 text-sm text-slate-700">${i + 1}</td>
            <td class="px-4 py-3 text-sm text-slate-700">${escapeHtml(product.name ?? '')}</td>
            <td class="px-4 py-3 text-sm text-slate-700">
              <button class="delete-btn inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white px-3 py-1 rounded" data-id="${product.id}">
               <!-- Trash Can Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6  hover:text-red-700 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4" />
            </svg>

              </button>
            </td>
          `;
          productList.appendChild(tr);
        });
      } catch (err) {
        console.error(err);
      }
    }

    // Simple HTML escape for names
    function escapeHtml(str) {
      return String(str).replace(/[&<>"']/g, (s) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[s]));
    }

    function spinner(label='Uploading...') {
      return `
        <span class="inline-flex items-center gap-2">
          <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
          </svg>
          ${label}
        </span>
      `;
    }

    function showToast(message) {
      if (!toast) return;
      toast.textContent = message;
      toast.classList.add('opacity-100');
      setTimeout(() => toast.classList.remove('opacity-100'), 3000);
    }

    // Add Product submit
    addForm && addForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      if (!productName || !submitBtn) return;

      const name = productName.value.trim();
      if (!name) { alert('Please enter a product name.'); return; }

      submitBtn.disabled = true;
      const original = submitBtn.innerHTML;
      submitBtn.innerHTML = spinner('Uploading...');

      try {
        const res = await fetch('/add-product', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
          },
          body: JSON.stringify({ name })
        });
        const data = await res.json();
        if (data?.success) {
          productName.value = '';
          await loadProducts();
          showToast('Product added successfully!');
        } else {
          alert(data?.message || 'Failed to add product.');
        }
      } catch (err) {
        console.error(err);
        alert('An error occurred.');
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = original;
      }
    });

    // Delete product (event delegation)
    productList && productList.addEventListener('click', async (e) => {
      const btn = e.target.closest('.delete-btn');
      if (!btn) return;
      const id = btn.dataset.id;
      if (!id) return;
      if (!confirm('Are you sure you want to delete this product?')) return;

      try {
        const res = await fetch(`/delete-product/${id}`, {
          method: 'DELETE',
          headers: {
            'Accept': 'application/json',
            ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {})
          }
        });
        const data = await res.json();
        if (data?.success) {
          await loadProducts();
          showToast('Product deleted successfully!');
        } else {
          alert(data?.message || 'Failed to delete product.');
        }
      } catch (err) {
        console.error(err);
        alert('An error occurred.');
      }
    });

    // Init on DOM ready
    document.addEventListener('DOMContentLoaded', () => {
      handleResize();
      loadProducts();
    });