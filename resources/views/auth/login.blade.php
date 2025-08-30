<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LoveHills | Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen w-screen font-sans flex items-center justify-center bg-black/50">
  <!-- Background -->
  <div class="absolute inset-0 -z-10">
    <img src="main.jpg" alt="Background" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-black/50"></div>
  </div>

  <!-- Split overlay -->
  <div class="w-full h-full flex flex-col md:flex-row">
    <!-- Left Half -->
    <div class="hidden md:flex flex-1 flex-col justify-center items-center text-center px-8 text-white bg-black/40">
      <h1 class="text-4xl lg:text-5xl font-bold tracking-wider drop-shadow-lg">LOVEHILLS STOCK MANAGER</h1>
      <p class="mt-4 text-lg text-gray-200 max-w-md">Welcome back. Manage your inventory securely and efficiently.</p>
    </div>

    <!-- Right Half -->
    <div class="flex-1 flex justify-center items-center relative px-6">
      <span class="absolute top-6 left-6 text-white/70 tracking-widest text-sm font-semibold">LOVEHILLS</span>

      <!-- Login Box -->
      <div class="w-full max-w-sm bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl p-8 animate-[slideUp_1s_ease-out_forwards] opacity-0 translate-y-10">
        <h2 class="text-2xl font-semibold text-white text-center mb-6">Secure Access</h2>

        <!-- Validation Errors -->
        <x-validation-errors class="mb-4 text-red-400 text-sm" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
          @csrf
          <div>
            <input type="password" name="password" required autocomplete="current-password"
              placeholder="Enter access key"
              class="w-full px-4 py-3 rounded-full bg-black/60 border border-white/20 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-500 shadow-md text-center">
          </div>

          <button type="submit"
            class="w-full py-3 rounded-full bg-gradient-to-r from-violet-500 to-indigo-400 text-white font-semibold hover:scale-[1.02] transition-transform shadow-lg">
            Login
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Tailwind Custom Animation -->
  <style>
    @keyframes slideUp {
      to { transform: translateY(0); opacity: 1; }
    }
  </style>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

</body>
</html>
