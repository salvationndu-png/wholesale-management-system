<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Track Sales - Lovehills</title>

  <!-- Tailwind -->
  <!-- <script src="https://cdn.tailwindcss.com"></script> -->

  <style>
    /* from your reference */
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(6px) } to { opacity: 1; transform: none } }
    .anim-fade-in-up { animation: fadeInUp .28s cubic-bezier(.2,.9,.2,1) both; }
    .no-scroll::-webkit-scrollbar { display: none; } .no-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    .focus-ring:focus { outline: 3px solid rgba(99,241,182,.58); outline-offset: 2px; }
    /* small visual niceties */
    .table-row-appear { animation: fadeInUp .18s ease both; }
    thead th.sticky { position: sticky; top: 0; z-index: 10; background: white; }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

  <!-- SIDEBAR (IDENTICAL STRUCTURE & IDS TO YOUR DASHBOARD REFERENCE) -->
  <aside id="sidebar"
         class="fixed inset-y-0 left-0 z-40 w-72 md:w-64 -translate-x-full md:translate-x-0
                bg-blue-600 text-white shadow-xl md:shadow-none
                transition-transform duration-300 ease-out flex flex-col">

    <!-- brand + mobile close -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-white/20">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-md bg-blue-500 grid place-items-center text-white font-bold">LH</div>
        <div class="text-lg font-semibold tracking-wider">Lovehills</div>
      </div>
      <!-- close for mobile -->
      <button id="closeSidebar" class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-md hover:bg-blue-500 focus-ring"
              aria-label="Close menu">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>

    <!-- nav -->
    <nav class="flex-1 overflow-y-auto no-scroll py-4 px-3 space-y-1">
      <a href="{{ url('home') }}" class="group flex items-center gap-3 rounded-lg px-4 py-3 transition hover:bg-blue-500 hover:translate-x-0.5 focus-ring">
        <span class="w-8 h-8 flex items-center justify-center rounded-md bg-blue-500">
          <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 13h8V3H3v10zM13 21h8V11h-8v10zM13 3v6h8V3h-8zM3 21h8v-8H3v8z"></path></svg>
        </span>
        <span class="font-medium">Dashboard</span>
      </a>

      <a href="{{ url('product') }}" class="group flex items-center gap-3 rounded-lg px-4 py-3 transition hover:bg-blue-500 hover:translate-x-0.5 focus-ring">
        <span class="w-8 h-8 flex items-center justify-center rounded-md bg-blue-500">
          <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 5v14M5 12h14"/></svg>
        </span>
        <span class="font-medium">Add Product</span>
      </a>

      <a href="{{ url('stock') }}" class="group flex items-center gap-3 rounded-lg px-4 py-3 transition hover:bg-blue-500 hover:translate-x-0.5 focus-ring">
        <span class="w-8 h-8 flex items-center justify-center rounded-md bg-blue-500">
          <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4M3 13l9 4 9-4"/></svg>
        </span>
        <span class="font-medium">Record Stock</span>
      </a>

      <a href="{{ url('sales') }}" class="group flex items-center gap-3 rounded-lg px-4 py-3 transition hover:bg-blue-500 hover:translate-x-0.5 focus-ring">
        <span class="w-8 h-8 flex items-center justify-center rounded-md bg-blue-500">
          <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 3h18v2H3zM5 7h14v14H5z"/></svg>
        </span>
        <span class="font-medium">Record Sale</span>
      </a>

      <!-- Track / View Sales - mark active -->
      <a href="{{ url('track') }}" aria-current="page"
         class="group flex items-center gap-3 rounded-lg px-4 py-3 transition hover:bg-blue-500 hover:translate-x-0.5 focus-ring nav-accent">
        <span class="w-8 h-8 flex items-center justify-center rounded-md bg-blue-500">
          <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 3v18h18"/></svg>
        </span>
        <span class="font-medium">View Sales</span>
      </a>
    </nav>

    <!-- bottom logout -->
    <div class="px-4 py-4 border-t border-white/20">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-md bg-rose-600 hover:bg-rose-700 transition focus-ring">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
          Logout
        </button>
      </form>
    </div>
  </aside>

  <!-- mobile overlay -->
  <div id="backdrop" class="fixed inset-0 bg-black/40 z-30 opacity-0 pointer-events-none transition-opacity"></div>

  <!-- MAIN (pad-left on md to avoid overlap) -->
  <div class="min-h-screen md:pl-64 flex flex-col">

    <!-- TOPBAR (same as dashboard reference) -->
    <header class="sticky top-0 z-20 border-b">
      <div class="h-16 flex items-center justify-between px-4 sm:px-6
                  bg-white/60 backdrop-blur supports-[backdrop-filter]:bg-white/50">
        <!-- mobile toggle -->
        <button id="openSidebar" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-200 hover:bg-slate-100 transition"
                aria-label="Open menu" aria-expanded="false">
          <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <h1 class="text-lg sm:text-xl font-semibold tracking-tight">Track Sales</h1>

        <!-- account dropdown -->
        <div class="relative">
          <button id="accountBtn" class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-slate-100 hover:bg-slate-200 transition focus-ring" aria-expanded="false">
            <span class="sr-only">Open account menu</span>
            <span>👤 {{ Auth::user()->name ?? 'Account' }}</span>
            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
          </button>

          <div id="accountMenu" class="hidden absolute right-0 mt-2 w-48 rounded-md border bg-white shadow-lg overflow-hidden anim-fade-in-up">
            <a href="{{ url('user/profile') }}" class="block px-4 py-2 hover:bg-slate-50">Profile</a>
            <div class="border-t"></div>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full text-left px-4 py-2 hover:bg-slate-50 text-rose-600">Logout</button>
            </form>
          </div>
        </div>
      </div>
    </header>

    <!-- PAGE CONTENT -->
    <main class="flex-1 p-4 sm:p-6 space-y-6">

      <!-- Filter + quick stats -->
      <section>
        <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-4 items-start">
          <div class="col-span-2 bg-white rounded-2xl shadow p-4 ring-1 ring-slate-100 anim-fade-in-up">
            <form id="filterForm" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
              <div class="sm:col-span-5">
                <label class="block text-sm text-slate-600 mb-1">Start Date</label>
                <input name="start_date" type="date" class="w-full h-10 text-sm px-3 rounded-md border border-slate-200 focus:ring focus:ring-blue-200 focus:border-blue-400" required>
              </div>

              <div class="sm:col-span-5">
                <label class="block text-sm text-slate-600 mb-1">End Date</label>
                <input name="end_date" type="date" class="w-full h-10 text-sm px-3 rounded-md border border-slate-200 focus:ring focus:ring-blue-200 focus:border-blue-400" required>
              </div>

              <div class="sm:col-span-2">
                <button type="submit" class="w-full h-10 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 transition">Filter Sales</button>
              </div>
            </form>
          </div>

          <!-- Quick stats / search / export -->
          <div class="bg-white rounded-2xl shadow p-4 ring-1 ring-slate-100 anim-fade-in-up space-y-3">
            <div class="flex items-center justify-between gap-2">
              <div>
                <div class="text-xs text-slate-400">Records</div>
                <div id="summaryRecords" class="text-lg font-semibold">—</div>
              </div>
              <div>
                <div class="text-xs text-slate-400">Total</div>
                <div id="summaryTotal" class="text-lg font-semibold">₦0.00</div>
              </div>
            </div>

            <!-- <div>
              <label class="block text-xs text-slate-500 mb-1">Quick search</label>
              <input id="salesSearch" placeholder="Search product, date, payment..." type="search" class="w-full h-10 text-sm px-3 rounded-md border border-slate-200 focus:ring focus:ring-blue-200 focus:border-blue-400">
            </div> -->

            <!-- <div class="flex gap-2">
              <button id="exportCsvBtn" class="flex-1 h-10 bg-emerald-600 text-white rounded-md text-sm hover:bg-emerald-700 transition">Export CSV</button>
              <button id="clearBtn" class="h-10 px-3 bg-white border rounded-md text-sm hover:bg-slate-50">Clear</button>
            </div> -->
          </div>
        </div>
      </section>

      <!-- Sales table -->
      <section>
        <div class="bg-white rounded-2xl shadow p-4 ring-1 ring-slate-100 anim-fade-in-up">
          <h3 class="text-slate-700 font-semibold mb-3">Sales Records</h3>

          <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse">
              <thead class="border-b bg-white">
                <tr>
                  <th class="px-3 py-2 text-left sticky">Date</th>
                  <th class="px-3 py-2 text-left sticky">Product</th>
                  <th class="px-3 py-2 text-right sticky">Qty</th>
                  <th class="px-3 py-2 text-right sticky">Price</th>
                  <th class="px-3 py-2 text-right sticky">Total</th>
                  <th class="px-3 py-2 text-left sticky">Payment</th>
                </tr>
              </thead>

              <tbody id="salesTableBody" class="divide-y divide-slate-100">
                <tr>
                  <td colspan="6" class="px-3 py-8 text-center text-slate-400">Use the filter to load sales records.</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div id="totalAmountDisplay" class="mt-4 text-right text-slate-600 font-semibold"></div>
        </div>
      </section>

    </main>

    <footer class="text-center text-slate-400 text-sm py-6">
      &copy; 2025 Lovehills Wholesale Assistant
    </footer>
  </div>

  <!-- UI: sidebar + account dropdown (keeps IDs same) -->
  <script>
    // Sidebar open/close (mobile)
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('backdrop');
    const openSidebarBtn = document.getElementById('openSidebar');
    const closeSidebarBtn = document.getElementById('closeSidebar');

    openSidebarBtn && openSidebarBtn.addEventListener('click', () => {
      sidebar.classList.remove('-translate-x-full');
      backdrop.classList.remove('opacity-0', 'pointer-events-none');
    });

    closeSidebarBtn && closeSidebarBtn.addEventListener('click', () => {
      sidebar.classList.add('-translate-x-full');
      backdrop.classList.add('opacity-0', 'pointer-events-none');
    });

    backdrop && backdrop.addEventListener('click', () => {
      sidebar.classList.add('-translate-x-full');
      backdrop.classList.add('opacity-0', 'pointer-events-none');
    });

    // account dropdown
    const accountBtn = document.getElementById('accountBtn');
    const accountMenu = document.getElementById('accountMenu');
    accountBtn && accountBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      accountMenu.classList.toggle('hidden');
    });
    document.addEventListener('click', (e) => {
      if (!accountMenu.classList.contains('hidden')) {
        if (!accountMenu.contains(e.target) && !accountBtn.contains(e.target)) accountMenu.classList.add('hidden');
      }
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') accountMenu.classList.add('hidden'); });
  </script>

  <!-- Table enhancements (non-invasive) -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const tbody = document.getElementById('salesTableBody');
      const searchInput = document.getElementById('salesSearch');
     
    
      const summaryRecords = document.getElementById('summaryRecords');
      const summaryTotal = document.getElementById('summaryTotal');
      const totalAmountDisplay = document.getElementById('totalAmountDisplay');

      // utility: parse currency like "₦1,234.00" or "1234.00" to number
      function parseCurrency(text) {
        if (!text) return 0;
        const cleaned = text.replace(/[^0-9.\-]/g, '');
        const n = Number(cleaned);
        return Number.isFinite(n) ? n : 0;
      }

      // compute summary from current visible rows
      function computeSummary() {
        const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => r.querySelectorAll('td').length > 0);
        // if placeholder (single row with colspan), treat as 0
        if (rows.length === 1 && rows[0].children.length === 1) {
          summaryRecords.textContent = '0';
          summaryTotal.textContent = '₦0.00';
          totalAmountDisplay.textContent = '';
          return;
        }

        let records = 0;
        let total = 0;
        rows.forEach(r => {
          const cells = r.querySelectorAll('td');
          if (cells.length < 5) return; // skip malformed
          const totalCell = cells[4]; // 5th column = Total
          const totalVal = parseCurrency(totalCell.textContent);
          if (!isNaN(totalVal)) { total += totalVal; records++; }
        });

        summaryRecords.textContent = records.toString();
        summaryTotal.textContent = '₦' + total.toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        totalAmountDisplay.textContent = `Total: ₦${total.toLocaleString('en-NG', { minimumFractionDigits: 2 })}`;
      }

      // simple search filter over current DOM rows (does not change server data)
      function applySearchFilter() {
        const q = (searchInput.value || '').trim().toLowerCase();
        const rows = Array.from(tbody.querySelectorAll('tr'));
        rows.forEach(r => {
          // placeholder row (single td) should remain visible when no query
          if (r.children.length === 1) {
            r.style.display = q ? 'none' : '';
            return;
          }
          const text = r.textContent.toLowerCase();
          r.style.display = text.includes(q) ? '' : 'none';
        });
        computeSummary();
      }

 

      // clear filters & search
      function clearUi() {
        searchInput.value = '';
        computeSummary();
      }

      // observe table changes (your track.js will insert rows) and recompute summary
      const observer = new MutationObserver(muts => {
        // add subtle class for new rows & compute summary
        muts.forEach(m => {
          if (m.addedNodes && m.addedNodes.length) {
            m.addedNodes.forEach(node => {
              if (node.nodeType === 1 && node.matches('tr')) {
                node.classList.add('table-row-appear');
                // ensure numeric cells appear right aligned
                node.querySelectorAll('td:nth-child(3), td:nth-child(4), td:nth-child(5)').forEach(td => td.classList.add('text-right'));
                node.querySelectorAll('td').forEach(td => td.classList.add('px-3', 'py-2'));
              }
            });
          }
        });
        // small timeout to allow your script to finish any formatting
        setTimeout(computeSummary, 60);
      });
      observer.observe(tbody, { childList: true, subtree: false });

      // initial compute in case track.js already rendered before DOMContentLoaded
      computeSummary();
    });
  </script>

  <!-- Your tracking logic (unchanged) -->
  <script src="/js/track.js"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

</body>
</html>
