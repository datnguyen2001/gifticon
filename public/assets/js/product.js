function setActiveTab(tab) {
    document.querySelectorAll('.product-wrapper, .store-wrapper').forEach((el) => {
        el.classList.remove('active');
    });

    if (tab === 'detail') {
        document.querySelector('.product-wrapper').classList.add('active');
        document.querySelector('.detail-tab').style.display = 'block';
        document.querySelector('.guide-tab').style.display = 'none';
    } else if (tab === 'guide') {
        document.querySelector('.store-wrapper').classList.add('active');
        document.querySelector('.guide-tab').style.display = 'block';
        document.querySelector('.detail-tab').style.display = 'none';
    }
}
