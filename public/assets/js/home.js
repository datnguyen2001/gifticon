var mySwiperPromotion = new Swiper(".mySwiperPromotion", {
    slidesPerView: 3,
    spaceBetween: 16,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false
    },
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
});

$('ul.tabs li').click(function(){
    var tab_id = $(this).attr('data-tab');

    $('ul.tabs li').removeClass('current');
    $('.tab-content').removeClass('current');

    $(this).addClass('current');
    $("#"+tab_id).addClass('current');
})
