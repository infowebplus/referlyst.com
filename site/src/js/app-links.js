const APP_URL = (import.meta.env.VITE_APP_URL || '').replace(/\/$/, '');

export function appPath(path) {
    if (!APP_URL) {
        return '#';
    }

    const normalized = path.startsWith('/') ? path : `/${path}`;

    return `${APP_URL}${normalized}`;
}

export function applyAppLinks(root = document) {
    root.querySelectorAll('[data-app-path]').forEach((el) => {
        const path = el.getAttribute('data-app-path');
        if (!path) {
            return;
        }

        el.setAttribute('href', appPath(path));
    });
}
