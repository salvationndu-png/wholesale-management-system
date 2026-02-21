<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard - Lovehills</title>
  
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="/css/design-system.css">
  <link rel="stylesheet" href="/css/nav.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); }
    
    /* Aesthetic Dark Mode */
    .dark { background: #0a0f1e; color: #e2e8f0; }
    .dark .bg-gray-50 { background: #0a0f1e; }
    .dark .bg-white { background: #111827; border-color: transparent; }
    .dark .text-gray-900 { color: #f1f5f9; }
    .dark .text-gray-700 { color: #cbd5e1; }
    .dark .text-gray-600 { color: #94a3b8; }
    .dark .text-gray-500 { color: #64748b; }
    .dark .text-gray-400 { color: #475569; }
    .dark .border-gray-200 { border-color: #1e293b; }
    .dark .border-gray-100 { border-color: #1e293b; }
    .dark .bg-gray-100 { background: #1e293b; }
    .dark .hover\:bg-gray-100:hover { background: #1e293b; }
    .dark .hover\:bg-gray-200:hover { background: #334155; }
    .dark .card { background: #111827; border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3); }
    .dark .stat-card { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4); }
    
    @keyframes shimmer { 0% { background-position: -1000px 0; } 100% { background-position: 1000px 0; } }
    .skeleton { background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%); background-size: 1000px 100%; animation: shimmer 2s infinite; }
    .dark .skeleton { background: linear-gradient(90deg, #0a0f1e 25%, #111827 50%, #0a0f1e 75%); }
  </style>
</head>
<body class="bg-gray-50 antialiased transition-colors duration-300">

<!-- MODERN WHITE SIDEBAR -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 -translate-x-full md:translate-x-0 transition-transform duration-300 shadow-xl">
  
  <!-- Logo & Brand -->
  <div class="h-16 flex items-center px-6 border-b border-gray-200">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl grid place-items-center text-white font-bold text-lg shadow-lg" style="background: var(--gradient-primary);">
        LH
      </div>
      <div class="text-gray-900 font-bold text-lg">Lovehills</div>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="flex-1 overflow-y-auto py-6 px-3">
    <!-- Main Section -->
    <div class="mb-6">
      <div class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Main</div>
      
      <a href="{{ url('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white mb-1 transition-all shadow-md" style="background: var(--gradient-primary);">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
        <span class="font-medium">Dashboard</span>
      </a>

      @if(Auth::check() && Auth::user()->usertype == 1)
      <a href="{{ url('product') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gray-100 hover:text-gray-900 mb-1 transition-all">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
        <span class="font-medium">Products</span>
      </a>

      <a href="{{ url('stock') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gray-100 hover:text-gray-900 mb-1 transition-all">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/><path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
        <span class="font-medium">Stock</span>
      </a>
      @endif

      <a href="{{ url('sales') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gray-100 hover:text-gray-900 mb-1 transition-all">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm2.5 3a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm6.207.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4a1 1 0 00-1.414-1.414L13 7.586l-1.293-1.293z" clip-rule="evenodd"/></svg>
        <span class="font-medium">Sales</span>
      </a>

      <a href="{{ url('track') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gray-100 hover:text-gray-900 mb-1 transition-all">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
        <span class="font-medium">Reports</span>
      </a>
    </div>

    @if(Auth::check() && Auth::user()->usertype == 1)
    <!-- Admin Section -->
    <div class="mb-6">
      <div class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin</div>
      
      <a href="{{ url('admin/users') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gray-100 hover:text-gray-900 mb-1 transition-all">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
        <span class="font-medium">Users</span>
      </a>
    </div>
    @endif
  </nav>

  <!-- User Profile -->
  <div class="p-4 border-t border-gray-200">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-10 h-10 rounded-full grid place-items-center text-white font-semibold shadow-md" style="background: var(--gradient-primary);">
        {{ strtoupper(substr(Auth::user()->name ?? 'U',0,1)) }}
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name ?? 'User' }}</div>
        <div class="text-xs text-gray-500">{{ Auth::user()->usertype == 1 ? 'Admin' : 'Sales' }}</div>
      </div>
    </div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        Logout
      </button>
    </form>
  </div>
</aside>

<!-- Mobile Backdrop -->
<div id="backdrop" class="fixed inset-0 bg-black/50 z-30 opacity-0 pointer-events-none transition-opacity md:hidden"></div>

<!-- MAIN CONTENT -->
<div class="min-h-screen md:pl-64 flex flex-col">
  
  <!-- ENHANCED HEADER -->
  <header class="sticky top-0 z-20 h-16 flex items-center justify-between px-6 bg-white/80 backdrop-blur-lg border-b border-gray-200">
    <div class="flex items-center gap-4">
      <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <div>
        <h1 class="text-lg font-semibold text-gray-900">Welcome back, {{ Auth::user()->name }}! 👋</h1>
        <p class="text-sm text-gray-500">Here's what's happening today</p>
      </div>
    </div>
    
    <div class="flex items-center gap-3">
      <button onclick="toggleDarkMode()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Toggle Dark Mode (Ctrl+D)">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
      </button>
      <div class="relative">
        <button id="notifBtn" class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Notifications">
          <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
          @if(count($notifications) > 0)
          <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
          @endif
        </button>
        <div id="notifMenu" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-50">
          <div class="p-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-900">Notifications</h3>
          </div>
          <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notif)
            <div class="p-4 hover:bg-gray-50 border-b border-gray-100 cursor-pointer">
              <div class="flex gap-3">
                <div class="w-2 h-2 mt-2 rounded-full flex-shrink-0 
                  @if($notif['type'] == 'warning') bg-red-500
                  @elseif($notif['type'] == 'success') bg-green-500
                  @else bg-blue-500 @endif"></div>
                <div>
                  <p class="text-sm font-medium text-gray-900">{{ $notif['title'] }}</p>
                  <p class="text-xs text-gray-500 mt-1">{{ $notif['message'] }}</p>
                  <p class="text-xs text-gray-400 mt-1">{{ $notif['time'] }}</p>
                </div>
              </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-500">
              <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
              <p class="text-sm">No notifications</p>
            </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- PAGE CONTENT -->
  <main class="flex-1 p-6 space-y-6">
    
    <!-- STAT CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="stat-card success animate-slide-up transition-all duration-300">
        <div class="flex items-start justify-between mb-4">
          <div>
            <div class="text-sm font-medium opacity-90 mb-1">Total Revenue</div>
            <div class="text-3xl font-bold">₦<span data-count="{{ $todaysSales }}">0</span></div>
          </div>
          <div class="p-3 bg-white/20 rounded-lg">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
          </div>
        </div>
        <div class="flex items-center gap-1 text-sm opacity-90">
          @if($salesChange >= 0)
          <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/></svg>
          <span>{{ number_format(abs($salesChange), 1) }}% from yesterday</span>
          @else
          <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd"/></svg>
          <span>{{ number_format(abs($salesChange), 1) }}% from yesterday</span>
          @endif
        </div>
      </div>

      <div class="stat-card info animate-slide-up stagger-1 transition-all duration-300">
        <div class="flex items-start justify-between mb-4">
          <div>
            <div class="text-sm font-medium opacity-90 mb-1">Total Products</div>
            <div class="text-3xl font-bold"><span data-count="{{ $totalProducts }}">0</span></div>
          </div>
          <div class="p-3 bg-white/20 rounded-lg">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
          </div>
        </div>
        <div class="flex items-center gap-1 text-sm opacity-90">
          @if($productsChange >= 0)
          <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/></svg>
          <span>{{ number_format(abs($productsChange), 1) }}% from last month</span>
          @else
          <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd"/></svg>
          <span>{{ number_format(abs($productsChange), 1) }}% from last month</span>
          @endif
        </div>
      </div>

      <div class="stat-card purple animate-slide-up stagger-2 transition-all duration-300">
        <div class="flex items-start justify-between mb-4">
          <div>
            <div class="text-sm font-medium opacity-90 mb-1">Total Stock</div>
            <div class="text-3xl font-bold"><span data-count="{{ $totalStock }}">0</span></div>
          </div>
          <div class="p-3 bg-white/20 rounded-lg">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/><path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
          </div>
        </div>
        <div class="flex items-center gap-1 text-sm opacity-90">
          <span>Bundles in stock</span>
        </div>
      </div>

      <div class="stat-card warning animate-slide-up stagger-3 transition-all duration-300">
        <div class="flex items-start justify-between mb-4">
          <div>
            <div class="text-sm font-medium opacity-90 mb-1">Low Stock Alert</div>
            <div class="text-3xl font-bold"><span data-count="{{ $lowStockCount }}">0</span></div>
          </div>
          <div class="p-3 bg-white/20 rounded-lg">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
          </div>
        </div>
        <div class="flex items-center gap-1 text-sm opacity-90">
          <span>Products need restocking</span>
        </div>
      </div>
    </div>

    <!-- CHARTS ROW -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="card p-6 animate-fade-in">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Sales Trend</h3>
          <button onclick="exportReport('sales')" class="text-xs px-3 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">Export</button>
        </div>
        <div id="chartLoader1" class="skeleton h-72 rounded-lg"></div>
        <div id="chartContainer1" style="height: 300px; display: none;"><canvas id="salesTrendChart"></canvas></div>
      </div>

      <div class="card p-6 animate-fade-in">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Top Selling Products</h3>
          <button onclick="exportReport('products')" class="text-xs px-3 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">Export</button>
        </div>
        <div id="chartLoader2" class="skeleton h-72 rounded-lg"></div>
        <div id="chartContainer2" style="height: 300px; display: none;"><canvas id="topProductsChart"></canvas></div>
      </div>
    </div>

    <!-- QUICK ACTIONS & ACTIVITY -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">⚡ Quick Actions</h3>
        <div class="grid grid-cols-2 gap-3">
          <a href="{{ url('sales') }}" class="flex flex-col items-center justify-center p-4 rounded-lg bg-gradient-to-br from-green-50 to-emerald-50 hover:from-green-100 hover:to-emerald-100 border border-green-200 transition-all">
            <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <span class="text-sm font-medium text-green-900">New Sale</span>
          </a>
          
          @if(Auth::check() && Auth::user()->usertype == 1)
          <a href="{{ url('stock') }}" class="flex flex-col items-center justify-center p-4 rounded-lg bg-gradient-to-br from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 border border-blue-200 transition-all">
            <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <span class="text-sm font-medium text-blue-900">Add Stock</span>
          </a>
          
          <a href="{{ url('product') }}" class="flex flex-col items-center justify-center p-4 rounded-lg bg-gradient-to-br from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 border border-purple-200 transition-all">
            <svg class="w-8 h-8 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span class="text-sm font-medium text-purple-900">New Product</span>
          </a>
          
          <a href="{{ url('admin/users') }}" class="flex flex-col items-center justify-center p-4 rounded-lg bg-gradient-to-br from-amber-50 to-orange-50 hover:from-amber-100 hover:to-orange-100 border border-amber-200 transition-all">
            <svg class="w-8 h-8 text-amber-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            <span class="text-sm font-medium text-amber-900">Add User</span>
          </a>
          @endif
        </div>
      </div>

      <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">🕐 Recent Activity</h3>
        <div class="space-y-3">
          <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50">
            <div class="w-2 h-2 mt-2 rounded-full bg-green-500"></div>
            <div class="flex-1">
              <p class="text-sm text-gray-900">New sale recorded</p>
              <p class="text-xs text-gray-500">2 minutes ago</p>
            </div>
          </div>
          <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50">
            <div class="w-2 h-2 mt-2 rounded-full bg-blue-500"></div>
            <div class="flex-1">
              <p class="text-sm text-gray-900">Stock updated</p>
              <p class="text-xs text-gray-500">15 minutes ago</p>
            </div>
          </div>
          <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50">
            <div class="w-2 h-2 mt-2 rounded-full bg-amber-500"></div>
            <div class="flex-1">
              <p class="text-sm text-gray-900">Low stock alert</p>
              <p class="text-xs text-gray-500">1 hour ago</p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>

  <footer class="text-center text-gray-400 text-sm py-6">
    &copy; 2025 Lovehills Wholesale Assistant
  </footer>
</div>

<script>
  // Sidebar toggle
  const sidebar = document.getElementById('sidebar');
  const backdrop = document.getElementById('backdrop');
  const openBtn = document.getElementById('openSidebar');
  const closeBtn = document.getElementById('closeSidebar');

  openBtn?.addEventListener('click', () => {
    sidebar.classList.remove('-translate-x-full');
    backdrop.classList.remove('opacity-0', 'pointer-events-none');
  });

  backdrop?.addEventListener('click', () => {
    sidebar.classList.add('-translate-x-full');
    backdrop.classList.add('opacity-0', 'pointer-events-none');
  });

  // Chart data
  const topProducts = @json($topProducts);
  const salesTrend = @json($salesTrend);

  // Initialize Charts
  new Chart(document.getElementById('topProductsChart'), {
    type: 'doughnut',
    data: { 
      labels: topProducts.labels, 
      datasets: [{ 
        data: topProducts.data, 
        backgroundColor: ['#0b5e57','#16a34a','#f59e0b','#ef4444','#8b5cf6'] 
      }] 
    },
    options: { 
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' } } 
    }
  });

  new Chart(document.getElementById('salesTrendChart'), {
    type: 'bar',
    data: { 
      labels: salesTrend.labels, 
      datasets: [{ 
        label: '₦ Sales', 
        data: salesTrend.data, 
        backgroundColor: '#0b5e57', 
        borderRadius: 8 
      }] 
    },
    options: { 
      responsive: true,
      maintainAspectRatio: false,
      scales: { 
        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, 
        x: { grid: { display: false } } 
      }, 
      plugins: { legend: { display: false } } 
    }
  });

  // Notification dropdown
  const notifBtn = document.getElementById('notifBtn');
  const notifMenu = document.getElementById('notifMenu');
  notifBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    notifMenu.classList.toggle('hidden');
  });
  document.addEventListener('click', () => notifMenu.classList.add('hidden'));
  
  // Show charts after load
  setTimeout(() => {
    document.getElementById('chartLoader1').style.display = 'none';
    document.getElementById('chartContainer1').style.display = 'block';
    document.getElementById('chartLoader2').style.display = 'none';
    document.getElementById('chartContainer2').style.display = 'block';
  }, 500);
</script>
<script src="/js/dashboard-enhanced.js"></script>
</body>
</html>
