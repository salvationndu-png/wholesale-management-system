<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Lovehills')</title>
  
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="/css/design-system.css">
  @stack('styles')
  <style>
    .dark { background: #0a0f1e; color: #e2e8f0; }
    .dark .bg-gray-50 { background: #0a0f1e; }
    .dark .bg-white { background: #111827; border-color: transparent; }
    .dark .text-gray-900 { color: #f1f5f9; }
    .dark .text-gray-700 { color: #cbd5e1; }
    .dark .text-gray-600 { color: #94a3b8; }
    .dark .text-gray-500 { color: #64748b; }
    .dark .border-gray-200 { border-color: #1e293b; }
    .dark .border-gray-100 { border-color: #1e293b; }
    .dark .bg-gray-100 { background: #1e293b; }
    .dark .hover\:bg-gray-100:hover { background: #1e293b; }
    .dark .hover\:bg-gray-200:hover { background: #334155; }
    .dark .card { background: #111827; border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3); }
  </style>
</head>
<body class="bg-gray-50 antialiased transition-colors duration-300">

<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 -translate-x-full md:translate-x-0 transition-transform duration-300 shadow-xl">
  <div class="h-16 flex items-center px-6 border-b border-gray-200">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl grid place-items-center text-white font-bold text-lg shadow-lg" style="background: var(--gradient-primary);">LH</div>
      <div class="text-gray-900 font-bold text-lg">Lovehills</div>
    </div>
  </div>

  <nav class="flex-1 overflow-y-auto py-6 px-3">
    <div class="mb-6">
      <div class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Main</div>
      
      <a href="{{ url('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->is('home') ? 'text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} mb-1 transition-all" @if(request()->is('home')) style="background: var(--gradient-primary);" @endif>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
        <span class="font-medium">Dashboard</span>
      </a>

      @if(Auth::check() && Auth::user()->usertype == 1)
      <a href="{{ url('product') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->is('product') ? 'text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} mb-1 transition-all" @if(request()->is('product')) style="background: var(--gradient-primary);" @endif>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
        <span class="font-medium">Products</span>
      </a>

      <a href="{{ url('stock') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->is('stock') ? 'text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} mb-1 transition-all" @if(request()->is('stock')) style="background: var(--gradient-primary);" @endif>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/><path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
        <span class="font-medium">Stock</span>
      </a>
      @endif

      <a href="{{ url('sales') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->is('sales') ? 'text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} mb-1 transition-all" @if(request()->is('sales')) style="background: var(--gradient-primary);" @endif>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm2.5 3a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm6.207.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4a1 1 0 00-1.414-1.414L13 7.586l-1.293-1.293z" clip-rule="evenodd"/></svg>
        <span class="font-medium">Sales</span>
      </a>

      <a href="{{ url('track') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->is('track') ? 'text-white shadow-md' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} mb-1 transition-all" @if(request()->is('track')) style="background: var(--gradient-primary);" @endif>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
        <span class="font-medium">Reports</span>
      </a>
    </div>

    @if(Auth::check() && Auth::user()->usertype == 1)
    <div class="mb-6">
      <div class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin</div>
      <a href="{{ url('admin/users') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-700 hover:bg-gray-100 hover:text-gray-900 mb-1 transition-all">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
        <span class="font-medium">Users</span>
      </a>
    </div>
    @endif
  </nav>

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

<div id="backdrop" class="fixed inset-0 bg-black/50 z-30 opacity-0 pointer-events-none transition-opacity md:hidden"></div>

<div class="min-h-screen md:pl-64 flex flex-col">
  <header class="sticky top-0 z-20 h-16 flex items-center justify-between px-6 bg-white/80 backdrop-blur-lg border-b border-gray-200">
    <div class="flex items-center gap-4">
      <button id="openSidebar" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <div>
        <h1 class="text-lg font-semibold text-gray-900">@yield('page-title')</h1>
        <p class="text-sm text-gray-500">@yield('page-subtitle')</p>
      </div>
    </div>
    <button onclick="toggleDarkMode()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Toggle Dark Mode">
      <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
    </button>
  </header>

  <main class="flex-1 p-6 space-y-6">
    @yield('content')
  </main>

  <footer class="text-center text-gray-400 text-sm py-6">
    &copy; 2025 Lovehills Wholesale Assistant
  </footer>
</div>

<script>
  const sidebar = document.getElementById('sidebar');
  const backdrop = document.getElementById('backdrop');
  document.getElementById('openSidebar')?.addEventListener('click', () => {
    sidebar.classList.remove('-translate-x-full');
    backdrop.classList.remove('opacity-0', 'pointer-events-none');
  });
  backdrop?.addEventListener('click', () => {
    sidebar.classList.add('-translate-x-full');
    backdrop.classList.add('opacity-0', 'pointer-events-none');
  });
</script>
<script src="/js/dashboard-enhanced.js"></script>
@stack('scripts')
</body>
</html>
