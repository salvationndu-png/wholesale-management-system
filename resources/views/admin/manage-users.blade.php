@extends('layouts.modern')

@section('title', 'Manage Users - Lovehills')
@section('page-title', 'Manage Users')
@section('page-subtitle', 'User accounts and permissions')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
  <div class="p-6 rounded-2xl shadow-lg text-white" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb);">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-blue-100 text-sm mb-1">Total Users</div>
        <div id="totalUsers" class="text-3xl font-bold">0</div>
      </div>
      <div class="bg-white/20 rounded-full p-3">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
      </div>
    </div>
  </div>

  <div class="p-6 rounded-2xl shadow-lg text-white" style="background: linear-gradient(to bottom right, #10b981, #059669);">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-green-100 text-sm mb-1">Active Sales</div>
        <div id="activeSales" class="text-3xl font-bold">0</div>
      </div>
      <div class="bg-white/20 rounded-full p-3">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
      </div>
    </div>
  </div>

  <div class="p-6 rounded-2xl shadow-lg text-white" style="background: linear-gradient(to bottom right, #ef4444, #dc2626);">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-red-100 text-sm mb-1">Inactive</div>
        <div id="inactiveUsers" class="text-3xl font-bold">0</div>
      </div>
      <div class="bg-white/20 rounded-full p-3">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/></svg>
      </div>
    </div>
  </div>

  <div class="p-6 rounded-2xl shadow-lg text-white" style="background: linear-gradient(to bottom right, #f59e0b, #d97706);">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-amber-100 text-sm mb-1">This Month</div>
        <div id="thisMonth" class="text-3xl font-bold">0</div>
      </div>
      <div class="bg-white/20 rounded-full p-3">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
      </div>
    </div>
  </div>
</div>

<div class="card p-6 mb-6">
  <div class="flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
    <div class="flex flex-col sm:flex-row gap-3 flex-1">
      <input id="searchInput" type="text" placeholder="Search by name or email..." class="flex-1 max-w-md px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
      <select id="roleFilter" class="px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
        <option value="">All Roles</option>
        <option value="1">Admin</option>
        <option value="0">Sales</option>
      </select>
      <select id="statusFilter" class="px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
        <option value="">All Status</option>
        <option value="1">Active</option>
        <option value="0">Inactive</option>
      </select>
    </div>
    <button id="createUserBtn" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl font-medium text-white transition-all shadow-md hover:shadow-lg" style="background: var(--gradient-primary);">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
      Create User
    </button>
  </div>
</div>

<div class="card p-6">
  <h3 class="text-lg font-semibold text-gray-900 mb-4">User Accounts</h3>
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="border-b border-gray-200">
          <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">User</th>
          <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
          <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Role</th>
          <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
          <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Joined</th>
          <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Actions</th>
        </tr>
      </thead>
      <tbody id="usersTableBody" class="divide-y divide-gray-100">
        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">Loading users...</td></tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Create User Modal -->
<div id="createUserModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-xl font-semibold text-gray-900">Create New User</h2>
      <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form id="createUserForm" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
        <input name="name" type="text" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <input name="email" type="email" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
        <input name="password" type="password" required minlength="8" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
        <select name="usertype" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
          <option value="0">Sales User</option>
          <option value="1">Admin</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
        <select name="status" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>
      </div>
      <div class="flex gap-2 pt-2">
        <button type="submit" class="flex-1 px-4 py-3 rounded-xl font-medium text-white transition-all shadow-md" style="background: var(--gradient-primary);">Create User</button>
        <button type="button" id="cancelModalBtn" class="px-4 py-3 rounded-xl border border-gray-300 hover:bg-gray-50 transition-all">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-xl font-semibold text-gray-900">Edit User</h2>
      <button id="closeEditModalBtn" class="text-gray-400 hover:text-gray-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form id="editUserForm" class="space-y-4">
      <input type="hidden" id="editUserId" name="userId">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
        <input id="editName" name="name" type="text" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <input id="editEmail" name="email" type="email" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-xs text-gray-500">(leave blank to keep current)</span></label>
        <input id="editPassword" name="password" type="password" minlength="8" placeholder="Enter new password or leave blank" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
        <select id="editUsertype" name="usertype" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
          <option value="0">Sales User</option>
          <option value="1">Admin</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
        <select id="editStatus" name="status" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>
      </div>
      <div class="flex gap-2 pt-2">
        <button type="submit" class="flex-1 px-4 py-3 rounded-xl font-medium text-white transition-all shadow-md" style="background: var(--gradient-primary);">Update User</button>
        <button type="button" id="cancelEditModalBtn" class="px-4 py-3 rounded-xl border border-gray-300 hover:bg-gray-50 transition-all">Cancel</button>
      </div>
    </form>
  </div>
</div>

<div id="toastContainer" class="fixed bottom-6 right-6 z-50 space-y-3"></div>
@endsection

@push('scripts')
<script src="/js/admin-users.js"></script>
@endpush
