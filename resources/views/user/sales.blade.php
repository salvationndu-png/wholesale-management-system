<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Record Sale - Lovehills</title>


  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(6px) } to { opacity: 1; transform: none } }
    .anim-fade-in-up { animation: fadeInUp .28s cubic-bezier(.2,.9,.2,1) both; }
    .focus-ring:focus { outline: 3px solid rgba(99,241,182,.58); outline-offset: 2px; }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">


  <aside id="sidebar"
         class="fixed inset-y-0 left-0 z-40 w-72 md:w-64 -translate-x-full md:translate-x-0
                bg-blue-600 text-white shadow-xl md:shadow-none transition-transform duration-300 ease-out flex flex-col">

    <div class="h-16 flex items-center justify-between px-6 border-b border-white/20">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-md bg-blue-500 grid place-items-center text-white font-bold">LH</div>
        <div class="text-lg font-semibold tracking-wider">Lovehills</div>
      </div>
      <button id="closeSidebar" class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-md hover:bg-blue-500 focus-ring"
              aria-label="Close menu">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

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

      <a href="{{ url('sales') }}" aria-current="page" class="group flex items-center gap-3 rounded-lg px-4 py-3 transition hover:bg-blue-500 hover:translate-x-0.5 focus-ring nav-accent">
        <span class="w-8 h-8 flex items-center justify-center rounded-md bg-blue-500">
          <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 3h18v2H3zM5 7h14v14H5z"/></svg>
        </span>
        <span class="font-medium">Record Sale</span>
      </a>

      <a href="{{ url('track') }}" class="group flex items-center gap-3 rounded-lg px-4 py-3 transition hover:bg-blue-500 hover:translate-x-0.5 focus-ring">
        <span class="w-8 h-8 flex items-center justify-center rounded-md bg-blue-500">
          <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 3v18h18"/></svg>
        </span>
        <span class="font-medium">View Sales</span>
      </a>
    </nav>

    <div class="px-4 py-4 border-t border-white/20">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-md bg-rose-600 hover:bg-rose-700 transition focus-ring">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path d="M17 16l4-4m0 0l-4-4m4 4H7"/>
          </svg>
          Logout
        </button>
      </form>
    </div>
  </aside>


  <div id="backdrop" class="fixed inset-0 bg-black/40 z-30 opacity-0 pointer-events-none transition-opacity"></div>

  <!-- MAIN -->
  <div class="min-h-screen md:pl-64 flex flex-col">
    <!-- TOPBAR -->
    <header class="sticky top-0 z-20 border-b">
      <div class="h-16 flex items-center justify-between px-4 sm:px-6 bg-white/60 backdrop-blur supports-[backdrop-filter]:bg-white/50">
        <button id="openSidebar" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-200 hover:bg-slate-100 transition" aria-label="Open menu" aria-expanded="false">
          <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <h1 class="text-lg sm:text-xl font-semibold tracking-tight">Record Sale</h1>

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


    <main class="flex-1 p-4 sm:p-6 space-y-6">
      <div class="max-w-5xl mx-auto space-y-4">

        <form id="salesForm" class="space-y-4" autocomplete="off">
          @csrf

          <div class="bg-white rounded-2xl shadow p-3 ring-1 ring-slate-100">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
              <div>
                <label class="block text-xs text-slate-600 mb-1">Customer (optional)</label>
                <input name="customer_name" class="w-full h-9 px-2 rounded-md border border-slate-200 focus:ring focus:ring-blue-200 text-sm" placeholder="Customer name" />
              </div>

              <div>
                <label class="block text-xs text-slate-600 mb-1">Payment</label>
                <select name="payment_type" class="w-full h-9 px-2 rounded-md border border-slate-200 text-sm">
                  <option value="Cash">Cash</option>
                  <option value="Transfer">Transfer</option>
                  <option value="Credit">Credit</option>
                </select>
              </div>

              <div>
                <label class="block text-xs text-slate-600 mb-1">Date</label>
                <input type="date" name="sale_date" value="{{ now()->toDateString() }}" class="w-full h-9 px-2 rounded-md border border-slate-200 text-sm" />
              </div>
            </div>
          </div>

     
          <template id="productOptions">
            <option value="" selected disabled>Select product</option>
            @foreach($products as $product)
              <option value="{{ $product->id }}" data-price="{{ $product->price ?? '' }}">{{ $product->name }}</option>
            @endforeach
          </template>

          <!-- Items container -->
          <div id="itemsContainer" class="space-y-2"></div>

          <!-- actions -->
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <button type="button" id="addItem" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition text-sm">➕ Add Item</button>

            <div class="flex items-center gap-4">
              <div class="text-sm text-slate-500">Total Sale</div>
              <div id="grandTotal" class="text-lg font-semibold">₦0.00</div>
              <button id="submitBtn" type="submit" class="ml-2 inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-amber-500 text-white hover:bg-amber-600 transition text-sm">✅ Record Sale</button>
            </div>
          </div>
        </form>
      </div>
    </main>

    <footer class="text-center text-slate-400 text-sm py-6">
      &copy; 2025 Lovehills Wholesale Assistant
    </footer>
  </div>

  <!-- toast container -->
  <div id="toastContainer" class="fixed bottom-4 right-4 z-50 space-y-2"></div>


  <script>
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

    const accountBtn = document.getElementById('accountBtn');
    const accountMenu = document.getElementById('accountMenu');
    accountBtn && accountBtn.addEventListener('click', (e) => { e.stopPropagation(); accountMenu.classList.toggle('hidden'); });
    document.addEventListener('click', (e) => {
      if (!accountMenu.classList.contains('hidden')) {
        if (!accountMenu.contains(e.target) && !accountBtn.contains(e.target)) accountMenu.classList.add('hidden');
      }
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') accountMenu.classList.add('hidden'); });
  </script>


  <script src="/js/sales.js"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

</body>
</html>
