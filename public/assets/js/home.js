var mySwiperPromotion = new Swiper(".mySwiperPromotion", {
    slidesPerView: 3,
    spaceBetween: 16,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false
    },
    breakpoints: {
        1024: {
            slidesPerView: 3
        },
        768: {
            slidesPerView: 4
        },
        576: {
            slidesPerView: 3
        },
        0: {
            slidesPerView: 2.5
        }
    }
});

var mySwiperBanner = new Swiper(".mySwiperBanner", {
    slidesPerView: 1,
    loop: true,
    autoplay: {
        delay: 3500,
        disableOnInteraction: false
    },
});

var mySwiperTrademark = new Swiper(".mySwiperTrademark", {
    slidesPerView: 5,
    loop: true,
    autoplay: {
        delay: 3500,
        disableOnInteraction: false
    },
    navigation: {
        nextEl: ".trademark-button-next",
        prevEl: ".trademark-button-prev",
    },
    breakpoints: {
        768: {
            slidesPerView: 5
        },
        576: {
            slidesPerView: 4
        },
        0: {
            slidesPerView: 2.5
        }
    }
});

$('ul.tabs li').click(function(){
    var tab_id = $(this).attr('data-tab');

    $('ul.tabs li').removeClass('current');
    $('.tab-content').removeClass('current');

    $(this).addClass('current');
    $("#"+tab_id).addClass('current');
})
