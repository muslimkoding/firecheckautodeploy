import './bootstrap';

// Import all of Bootstrap’s JS
import * as bootstrap from 'bootstrap'

document.addEventListener('DOMContentLoaded', () => {
  const sidebarToggle = document.getElementById('sidebarToggle');
  if (!sidebarToggle) return;

  // Restore previous toggle state
  // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
  //   document.body.classList.add('sb-sidenav-toggled');
  // }

  sidebarToggle.addEventListener('click', (e) => {
    e.preventDefault();
    document.body.classList.toggle('sb-sidenav-toggled');
    localStorage.setItem(
      'sb|sidebar-toggle',
      document.body.classList.contains('sb-sidenav-toggled')
    );
  });
});
