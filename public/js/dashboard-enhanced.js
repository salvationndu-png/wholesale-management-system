// Number counting animation
function animateValue(element, start, end, duration) {
  const range = end - start;
  const increment = range / (duration / 16);
  let current = start;
  
  const timer = setInterval(() => {
    current += increment;
    if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
      current = end;
      clearInterval(timer);
    }
    element.textContent = Math.floor(current).toLocaleString();
  }, 16);
}

// Dark mode toggle
function initDarkMode() {
  const darkMode = localStorage.getItem('darkMode') === 'true';
  if (darkMode) document.documentElement.classList.add('dark');
  
  window.toggleDarkMode = () => {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
  };
}

// Toast notifications
function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-xl shadow-xl text-white z-50 animate-slide-up ${
    type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
  }`;
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}

// Keyboard shortcuts
document.addEventListener('keydown', (e) => {
  if (e.ctrlKey || e.metaKey) {
    switch(e.key) {
      case 'k': e.preventDefault(); document.getElementById('searchInput')?.focus(); break;
      case 'd': e.preventDefault(); toggleDarkMode(); break;
    }
  }
});

// Initialize on load
document.addEventListener('DOMContentLoaded', () => {
  initDarkMode();
  
  // Animate stat numbers
  document.querySelectorAll('[data-count]').forEach(el => {
    const target = parseInt(el.dataset.count);
    animateValue(el, 0, target, 1000);
  });
});

// Export functionality
window.exportReport = (type) => {
  showToast(`Exporting ${type} report...`, 'info');
  // Add actual export logic here
};
