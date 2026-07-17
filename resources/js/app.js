import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Dark-mode toggle (pre-paint preference set inline in the layout <head>).
window.__toggleTheme = function () {
    const root = document.documentElement;
    const dark = root.classList.toggle('dark');
    try {
        localStorage.setItem('theme', dark ? 'dark' : 'light');
    } catch (e) {}
};
