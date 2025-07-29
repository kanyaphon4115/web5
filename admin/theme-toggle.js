document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('toggle-theme-btn');

  // โหลดสถานะตอนหน้าโหลด
  if(localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-mode');
  }

  // ถ้าปุ่มมีอยู่
  if(btn) {
    btn.addEventListener('click', () => {
      document.body.classList.toggle('dark-mode');
      if(document.body.classList.contains('dark-mode')) {
        localStorage.setItem('theme', 'dark');
      } else {
        localStorage.setItem('theme', 'light');
      }
    });
  }
});
