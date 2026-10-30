'use strict';
document.querySelectorAll('.navbar a.nav-link.active').forEach(link => link.setAttribute('aria-current', 'page'));
const inquiryForm = document.getElementById('inquiry-form');
if (inquiryForm) {
    const inquiryButton = inquiryForm.querySelector('button[type="submit"]');
    const originalButtonText = inquiryButton.textContent;
    inquiryForm.addEventListener('submit', () => {
        const button = inquiryForm.querySelector('button[type="submit"]');
        button.disabled = true;
        button.textContent = inquiryForm.dataset.sendingLabel;
    });
    window.addEventListener('pageshow', () => {
        const button = inquiryForm.querySelector('button[type="submit"]');
        button.disabled = false;
        button.textContent = originalButtonText;
    });
}

const catalogue = document.querySelector('[data-catalogue]');
if (catalogue) {
    const cards = [...catalogue.querySelectorAll('[data-category]')];
    const chips = [...catalogue.querySelectorAll('[data-filter]')];
    const search = catalogue.querySelector('[data-product-search]');
    let selected = catalogue.querySelector('[data-filter].active')?.dataset.filter || '';
    function filterProducts(updateUrl = false) {
        const term = search.value.trim().toLowerCase();
        let visible = 0;
        cards.forEach(card => {
            const show = (!selected || card.dataset.category === selected) && card.dataset.search.includes(term);
            card.hidden = !show;
            if (show) visible++;
        });
        chips.forEach(chip => {
            const active = chip.dataset.filter === selected;
            chip.classList.toggle('active', active);
            chip.setAttribute('aria-pressed', String(active));
        });
        const countTemplate = selected ? (visible === 1 ? catalogue.dataset.countCategoryOne : catalogue.dataset.countCategory) : (visible === 1 ? catalogue.dataset.countAllOne : catalogue.dataset.countAll);
        catalogue.querySelector('[data-result-count]').textContent = countTemplate.replaceAll('{count}', String(visible)).replaceAll('{category}', selected);
        catalogue.querySelector('[data-empty-results]').hidden = visible !== 0;
        if (updateUrl) {
            const url = new URL(window.location.href);
            if (selected) url.searchParams.set('category', selected); else url.searchParams.delete('category');
            history.replaceState(null, '', url);
        }
    }
    chips.forEach(chip => chip.addEventListener('click', () => { selected = chip.dataset.filter; filterProducts(true); }));
    search.addEventListener('input', () => filterProducts());
    catalogue.querySelector('[data-reset-filters]').addEventListener('click', () => { selected = ''; search.value = ''; filterProducts(true); search.focus(); });
    filterProducts();
}
