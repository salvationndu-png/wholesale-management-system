<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Record Stock - Lovehills</title>


  <script src="https://cdn.tailwindcss.com"></script>


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(6px) } to { opacity: 1; transform: none } }
    .anim-fade-in-up { animation: fadeInUp .28s cubic-bezier(.2,.9,.2,1) both; }
    @keyframes slideIn { from { transform: translateX(-100%) } to { transform: translateX(0) } }
    .anim-slide-in { animation: slideIn .28s cubic-bezier(.2,.9,.2,1) both; }
    .nav-accent { background: linear-gradient(180deg, rgba(144,241,99,.18), rgba(144,241,99,.12)); }
    .no-scroll::-webkit-scrollbar { display: none; }
    .no-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    .focus-ring:focus { outline: 3px solid rgba(99,241,182,.58); outline-offset: 2px; }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">


<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-40 w-72 md:w-64 -translate-x-full md:translate-x-0
              bg-blue-600 text-white shadow-xl md:shadow-none
              transition-transform duration-300 ease-out flex flex-col">
  <div class="h-16 flex items-center justify-between px-6 border-b border-white/20">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-md bg-blue-500 grid place-items-center text-white font-bold">LH</div>
      <div class="text-lg font-semibold tracking-wider">Lovehills</div>
    </div>
    <button id="closeSidebar" class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-md hover:bg-blue-500 focus-ring" aria-label="Close menu">
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

    <a href="{{ url('stock') }}" aria-current="page" class="group flex items-center gap-3 rounded-lg px-4 py-3 transition hover:bg-blue-500 hover:translate-x-0.5 focus-ring nav-accent">
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
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
        Logout
      </button>
    </form>
  </div>
</aside>

<!-- mobile overlay -->
<div id="backdrop" class="fixed inset-0 bg-black/40 z-30 opacity-0 pointer-events-none transition-opacity"></div>

<!-- MAIN -->
<div class="min-h-screen md:pl-64 flex flex-col">

  <!-- TOPBAR -->
  <header class="sticky top-0 z-20 border-b">
    <div class="h-16 flex items-center justify-between px-4 sm:px-6 bg-white/60 backdrop-blur supports-[backdrop-filter]:bg-white/50">
      <button id="openSidebar" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-200 hover:bg-slate-100 transition" aria-label="Open menu" aria-expanded="false">
        <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>

      <h1 class="text-lg sm:text-xl font-semibold tracking-tight">Record Stock</h1>

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

<section>
  <div class="max-w-4xl bg-white rounded-2xl shadow p-6 ring-1 ring-slate-100 anim-fade-in-up">
    <h3 class="text-slate-700 font-semibold mb-5">Add Stock</h3>

    <form id="stockForm" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
      @csrf


      <div class="md:col-span-4">
        <label class="block text-sm text-slate-600 mb-1.5">Product</label>
        <select name="product_id" id="productSelect" required
                class="w-full h-11 text-sm px-3 rounded-lg border border-slate-200 bg-white focus:border-blue-400 focus:ring focus:ring-blue-200/60">
          <option value="">Select Product</option>
          @foreach($products as $product)
            <option value="{{ $product->id }}">{{ $product->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="md:col-span-2">
        <label class="block text-sm text-slate-600 mb-1.5">Quantity</label>
        <input type="number" name="quantity" id="quantityInput" placeholder="e.g., 10" required
               class="w-full h-11 text-sm px-3 rounded-lg border border-slate-200 focus:border-blue-400 focus:ring focus:ring-blue-200/60" />
      </div>


      <div class="md:col-span-2">
        <label class="block text-sm text-slate-600 mb-1.5">Price</label>
        <input type="number" step="0.01" name="price" id="priceInput" placeholder="Enter price" required
               class="w-full h-11 text-sm px-3 rounded-lg border border-slate-200 focus:border-blue-400 focus:ring focus:ring-blue-200/60" />
      </div>

      <!-- Date -->
      <div class="md:col-span-2">
        <label class="block text-sm text-slate-600 mb-1.5">Date</label>
        <input type="date" name="date" id="dateInput" value="{{ now()->toDateString() }}" required
               class="w-full h-11 text-sm px-3 rounded-lg border border-slate-200 focus:border-blue-400 focus:ring focus:ring-blue-200/60" />
      </div>

      <div class="md:col-span-2">
        <button type="submit" id="submitBtn"
                class="w-full h-11 px-4 text-sm inline-flex items-center justify-center rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">
          Add Stock
        </button>
      </div>
    </form>
  </div>
</section>



 
    <section>
      <div class="bg-white rounded-2xl shadow p-6 ring-1 ring-slate-100 anim-fade-in-up">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-slate-700 font-semibold">Current Stock</h3>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="text-left text-slate-500">
                <th class="px-3 py-2 font-medium">#</th>
                <th class="px-3 py-2 font-medium">Product Name</th>
                <th class="px-3 py-2 font-medium">Quantity</th>
                <th class="px-3 py-2 font-medium">Price</th>
                <th class="px-3 py-2 font-medium">Delete</th>
                <th class="px-3 py-2 font-medium">Actions</th>
              </tr>
            </thead>
            <tbody id="stockTable" class="divide-y divide-slate-100">
              <!-- rows injected by JS -->
            </tbody>
          </table>
        </div>
      </div>
    </section>

  </main>

  <footer class="text-center text-slate-400 text-sm py-6">
    &copy; 2025 Lovehills Wholesale Assistant
  </footer>
</div>

<!-- Toasts -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:1056">
  <div id="toastContainer"></div>
</div>

<!-- Increment Stock Modal -->
<div class="modal fade" id="incrementModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Increase Stock Quantity</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Product:</strong> <span id="modalProductName"></span></p>
        <p><strong>Current Quantity:</strong> <span id="modalCurrentQuantity"></span></p>
        <div class="mb-3">
          <label for="additionalQuantity" class="form-label">Additional Quantity</label>
          <input type="number" class="form-control" id="additionalQuantity" min="1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="saveIncrementBtn" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Price Modal -->
<div class="modal fade" id="priceModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Edit Price</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control" id="editPriceInput" inputmode="decimal">
      </div>
      <div class="modal-footer">
        <button type="button" id="savePriceBtn" class="btn btn-warning">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
  const sidebar = document.getElementById('sidebar');
  const backdrop = document.getElementById('backdrop');
  document.getElementById('openSidebar').addEventListener('click', () => {
    sidebar.classList.remove('-translate-x-full');
    backdrop.classList.remove('opacity-0', 'pointer-events-none');
  });
  document.getElementById('closeSidebar').addEventListener('click', () => {
    sidebar.classList.add('-translate-x-full');
    backdrop.classList.add('opacity-0', 'pointer-events-none');
  });
  backdrop.addEventListener('click', () => {
    sidebar.classList.add('-translate-x-full');
    backdrop.classList.add('opacity-0', 'pointer-events-none');
  });

  const accountBtn = document.getElementById('accountBtn');
  const accountMenu = document.getElementById('accountMenu');
  accountBtn.addEventListener('click', () => accountMenu.classList.toggle('hidden'));
  document.addEventListener('click', (e) => {
    if (!accountBtn.contains(e.target) && !accountMenu.contains(e.target)) accountMenu.classList.add('hidden');
  });
</script>

<script src="/js/stock.js"></script>
@vite(['resources/css/app.css', 'resources/js/app.js'])

</body>
</html>
