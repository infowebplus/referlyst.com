import '../css/main.css';
import { applyAppLinks } from './app-links.js';
import { renderAddons, renderBuyPackage, renderCompareTable, renderPricingCards } from './pricing.js';

document.addEventListener('DOMContentLoaded', () => {
    renderPricingCards();
    renderAddons();
    renderCompareTable();
    renderBuyPackage();
    applyAppLinks();

    const params = new URLSearchParams(window.location.search);
    if (params.get('success') === 'true') {
        const success = document.querySelector('[data-contact-success]');
        if (success) {
            success.classList.remove('hidden');
        }
    }
});
