document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('toggle-theme-btn');
  const icon = document.getElementById('theme-icon');
  const iconPath = document.getElementById('theme-icon-path');
  const label = document.getElementById('theme-label');
  let dark = false;

  // โหลดสถานะตอนหน้าโหลด
  if(localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-mode');
    dark = true;
    iconPath.setAttribute('d', 'M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z');
    label.textContent = 'Dark';
  }

  // ถ้าปุ่มมีอยู่
  if(btn) {
    btn.addEventListener('click', () => {
      dark = !dark;
      document.body.classList.toggle('dark-mode');
      document.body.classList.toggle('bg-gray-900', dark);
      document.body.classList.toggle('text-white', dark);
      // เปลี่ยนไอคอน sun/moon
      if (dark) {
        iconPath.setAttribute('d', 'M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z');
        label.textContent = 'Dark';
        localStorage.setItem('theme', 'dark');
      } else {
        iconPath.setAttribute('d', 'M12 3v1m0 16v1m8.66-8.66l-.71.71M4.05 19.07l-.71.71M21 12h-1M4 12H3m16.24 7.07l-.71-.71M6.34 6.34l-.71-.71M12 5a7 7 0 100 14 7 7 0 000-14z');
        label.textContent = 'Light';
        localStorage.setItem('theme', 'light');
      }
    });
  }
});
