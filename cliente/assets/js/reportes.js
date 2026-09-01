function showToast(msg, type) {
  const container = document.getElementById('toasts');
  if (!container) return;
  const el = document.createElement('div');
  el.className = 'toast ' + (type === 'error' ? 'bg-danger text-white' : 'bg-success text-white');
  el.innerHTML = '<div class="toast-body">' + msg + '</div>';
  container.appendChild(el);
  new bootstrap.Toast(el, {delay: 3000}).show();
  el.addEventListener('hidden.bs.toast', () => el.remove());
}
