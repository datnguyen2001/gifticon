var mySwiperPromotion = new Swiper(".mySwiperPromotion", {
    slidesPerView: 3,
    spaceBetween: 16,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false
    },
});

document.addEventListener('DOMContentLoaded', function() {
    var content = document.querySelector('.brand-content');
    var button = document.querySelector('.brand-toggle-button');

    function isContentOverflowing(element) {
        return element.scrollHeight > element.clientHeight;
    }

    if (!isContentOverflowing(content)) {
        button.style.display = 'none';
    }

    button.addEventListener('click', function() {
        if (content.classList.contains('brand-truncated')) {
            content.classList.remove('brand-truncated');
            button.innerText = 'Thu gọn';
        } else {
            content.classList.add('brand-truncated');
            button.innerText = 'Xem thêm';
        }
    });
});

