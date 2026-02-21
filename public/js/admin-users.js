// /js/admin-users.js
document.addEventListener('DOMContentLoaded', function () {
  const CSRF = document.querySelector('meta[name="csrf-token"]').content;
  const toastContainer = document.getElementById('toastContainer');
  const usersTableBody = document.getElementById('usersTableBody');
  const createUserModal = document.getElementById('createUserModal');
  const createUserForm = document.getElementById('createUserForm');
  const createUserBtn = document.getElementById('createUserBtn');
  const closeModalBtn = document.getElementById('closeModalBtn');
  const cancelModalBtn = document.getElementById('cancelModalBtn');
  const editUserModal = document.getElementById('editUserModal');
  const editUserForm = document.getElementById('editUserForm');
  const closeEditModalBtn = document.getElementById('closeEditModalBtn');
  const cancelEditModalBtn = document.getElementById('cancelEditModalBtn');
  const searchInput = document.getElementById('searchInput');
  const roleFilter = document.getElementById('roleFilter');
  const statusFilter = document.getElementById('statusFilter');

  // Toast notification
  function showToast(message, type = 'success') {
    if (!toastContainer) return;
    const bg = type === 'success' ? 'bg-emerald-600' : 'bg-red-600';
    const el = document.createElement('div');
    el.className = `px-3 py-2 rounded-md text-white ${bg} shadow-lg flex items-center justify-between`;
    el.innerHTML = `
      <span>${message}</span>
      <button class="ml-2 text-white font-bold" onclick="this.parentElement.remove()">×</button>
    `;
    toastContainer.appendChild(el);
    setTimeout(() => el.remove(), 3000);
  }

  // Load stats
  async function loadStats() {
    try {
      const res = await fetch('/admin/users/stats');
      const data = await res.json();
      if (data.success) {
        document.getElementById('totalUsers').textContent = data.stats.total;
        document.getElementById('activeSales').textContent = data.stats.activeSales;
        document.getElementById('inactiveUsers').textContent = data.stats.inactive;
        document.getElementById('thisMonth').textContent = data.stats.thisMonth;
      }
    } catch (err) {
      console.error('Error loading stats:', err);
    }
  }

  // Load users
  async function loadUsers() {
    const search = searchInput.value;
    const role = roleFilter.value;
    const status = statusFilter.value;

    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (role) params.append('role', role);
    if (status) params.append('status', status);

    try {
      const res = await fetch(`/admin/users/list?${params}`);
      const data = await res.json();

      if (data.success) {
        renderUsers(data.users);
      }
    } catch (err) {
      console.error('Error loading users:', err);
      usersTableBody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-rose-500">Error loading users</td></tr>';
    }
  }

  // Render users table
  function renderUsers(users) {
    if (users.length === 0) {
      usersTableBody.innerHTML = `
        <tr>
          <td colspan="6" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
              <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
              </svg>
              <p class="text-slate-400 text-sm font-medium">No users found</p>
              <p class="text-slate-400 text-xs mt-1">Try adjusting your search or filters</p>
            </div>
          </td>
        </tr>
      `;
      return;
    }

    usersTableBody.innerHTML = users.map(user => `
      <tr class="hover:bg-slate-50 transition-colors">
        <td class="px-6 py-4">
          <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10">
              <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                ${user.name.charAt(0).toUpperCase()}
              </div>
            </div>
            <div class="ml-4">
              <div class="text-sm font-semibold text-slate-900">${user.name}</div>
            </div>
          </div>
        </td>
        <td class="px-6 py-4">
          <div class="text-sm text-slate-600">${user.email}</div>
        </td>
        <td class="px-6 py-4">
          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ${
            user.usertype === 1 
              ? 'bg-purple-100 text-purple-800 ring-1 ring-purple-600/20' 
              : 'bg-blue-100 text-blue-800 ring-1 ring-blue-600/20'
          }">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
              ${user.usertype === 1 
                ? '<path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>' 
                : '<path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>'
              }
            </svg>
            ${user.usertype === 1 ? 'Admin' : 'Sales'}
          </span>
        </td>
        <td class="px-6 py-4">
          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ${
            user.status 
              ? 'bg-emerald-100 text-emerald-800 ring-1 ring-emerald-600/20' 
              : 'bg-rose-100 text-rose-800 ring-1 ring-rose-600/20'
          }">
            <span class="w-1.5 h-1.5 mr-1.5 rounded-full ${user.status ? 'bg-emerald-600' : 'bg-rose-600'}"></span>
            ${user.status ? 'Active' : 'Inactive'}
          </span>
        </td>
        <td class="px-6 py-4">
          <div class="text-sm text-slate-600">${user.created_at}</div>
        </td>
        <td class="px-6 py-4 text-right">
          <div class="flex items-center justify-end gap-2">
            <button 
              onclick="editUser(${user.id})" 
              class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all" title="Edit user">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
            </button>
            ${user.id !== 1 ? `
            <button 
              onclick="toggleStatus(${user.id})" 
              class="p-2 rounded-lg transition-all ${
                user.status 
                  ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' 
                  : 'bg-green-50 text-green-600 hover:bg-green-100'
              }" title="${user.status ? 'Deactivate' : 'Activate'}">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${user.status 
                  ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>' 
                  : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                }
              </svg>
            </button>
            ` : ''}
            <button 
              onclick="resetPassword(${user.id})" 
              class="p-2 rounded-lg bg-purple-50 text-purple-600 hover:bg-purple-100 transition-all" title="Reset password">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
              </svg>
            </button>
            ${user.id !== 1 ? `
            <button 
              onclick="deleteUser(${user.id})" 
              class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all" title="Delete user">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
            </button>
            ` : ''}
          </div>
        </td>
      </tr>
    `).join('');
  }

  // Toggle user status
  window.toggleStatus = async function(userId) {
    if (!confirm('Are you sure you want to change this user\'s status?')) return;

    try {
      const res = await fetch(`/admin/users/${userId}/toggle-status`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF
        }
      });
      const data = await res.json();

      if (data.success) {
        showToast(data.message);
        loadUsers();
        loadStats();
      } else {
        showToast(data.message, 'error');
      }
    } catch (err) {
      console.error('Error toggling status:', err);
      showToast('Error updating status', 'error');
    }
  };

  // Reset password
  window.resetPassword = async function(userId) {
    if (!confirm('Reset this user\'s password? A new password will be generated.')) return;

    try {
      const res = await fetch(`/admin/users/${userId}/reset-password`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF
        }
      });
      const data = await res.json();

      if (data.success) {
        showToast(`Password reset! New password: ${data.password}`);
        prompt('New password (copy this):', data.password);
      } else {
        showToast(data.message, 'error');
      }
    } catch (err) {
      console.error('Error resetting password:', err);
      showToast('Error resetting password', 'error');
    }
  };

  // Delete user
  window.deleteUser = async function(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) return;

    try {
      const res = await fetch(`/admin/users/${userId}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF
        }
      });
      const data = await res.json();

      if (data.success) {
        showToast(data.message);
        loadUsers();
        loadStats();
      } else {
        showToast(data.message, 'error');
      }
    } catch (err) {
      console.error('Error deleting user:', err);
      showToast('Error deleting user', 'error');
    }
  };

  // Create user modal
  createUserBtn?.addEventListener('click', () => {
    createUserModal.classList.remove('hidden');
  });

  closeModalBtn?.addEventListener('click', () => {
    createUserModal.classList.add('hidden');
    createUserForm.reset();
  });

  cancelModalBtn?.addEventListener('click', () => {
    createUserModal.classList.add('hidden');
    createUserForm.reset();
  });

  // Edit user modal
  window.editUser = async function(userId) {
    try {
      const res = await fetch('/admin/users/list');
      const data = await res.json();
      const user = data.users.find(u => u.id === userId);

      if (user) {
        document.getElementById('editUserId').value = user.id;
        document.getElementById('editName').value = user.name;
        document.getElementById('editEmail').value = user.email;
        document.getElementById('editUsertype').value = user.usertype;
        document.getElementById('editStatus').value = user.status ? 1 : 0;
        document.getElementById('editPassword').value = '';
        editUserModal.classList.remove('hidden');
      }
    } catch (err) {
      console.error('Error loading user:', err);
      showToast('Error loading user details', 'error');
    }
  };

  closeEditModalBtn?.addEventListener('click', () => {
    editUserModal.classList.add('hidden');
    editUserForm.reset();
  });

  cancelEditModalBtn?.addEventListener('click', () => {
    editUserModal.classList.add('hidden');
    editUserForm.reset();
  });

  // Edit user form submit
  editUserForm?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const userId = document.getElementById('editUserId').value;
    const formData = new FormData(editUserForm);
    const data = Object.fromEntries(formData);
    delete data.userId;

    // Remove password if empty
    if (!data.password) {
      delete data.password;
    }

    try {
      const res = await fetch(`/admin/users/${userId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF
        },
        body: JSON.stringify(data)
      });

      const result = await res.json();

      if (result.success) {
        showToast(result.message);
        editUserModal.classList.add('hidden');
        editUserForm.reset();
        loadUsers();
        loadStats();
      } else {
        if (result.errors) {
          const errorMessages = Object.values(result.errors).flat().join(', ');
          showToast(errorMessages, 'error');
        } else {
          showToast(result.message || 'Error updating user', 'error');
        }
      }
    } catch (err) {
      console.error('Error updating user:', err);
      showToast('Error updating user. Check console for details.', 'error');
    }
  });

  // Create user form submit
  createUserForm?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(createUserForm);
    const data = Object.fromEntries(formData);

    try {
      const res = await fetch('/admin/users/create', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF
        },
        body: JSON.stringify(data)
      });

      const result = await res.json();

      if (result.success) {
        showToast(result.message);
        createUserModal.classList.add('hidden');
        createUserForm.reset();
        loadUsers();
        loadStats();
      } else {
        // Handle validation errors
        if (result.errors) {
          const errorMessages = Object.values(result.errors).flat().join(', ');
          showToast(errorMessages, 'error');
        } else {
          showToast(result.message || 'Error creating user', 'error');
        }
      }
    } catch (err) {
      console.error('Error creating user:', err);
      showToast('Error creating user. Check console for details.', 'error');
    }
  });

  // Search and filters
  searchInput?.addEventListener('input', () => {
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(loadUsers, 300);
  });

  roleFilter?.addEventListener('change', loadUsers);
  statusFilter?.addEventListener('change', loadUsers);

  // Initial load
  loadStats();
  loadUsers();
});
