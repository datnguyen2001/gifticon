$(document).ready(function () {
    let ribbon = $(".ribbon");
    let gift = $(".gift");
    let quick = $(".quick");

    let ribbonTop = 30;   // Vị trí cố định ban đầu của ribbon
    let giftTop = 280;    // Vị trí cố định của .gift
    let quickTop = 280;   // Vị trí cố định của .quick
    let lastScrollTop = 0;
    let scrollTimeout;

    $(window).on("scroll", function () {
        clearTimeout(scrollTimeout); // Xóa timeout trước đó nếu có

        let scrollTop = $(this).scrollTop();

        // Khi cuộn xuống, di chuyển các phần tử theo
        ribbon.stop().css("top", scrollTop + 50 + "px");
        gift.stop().css("top", scrollTop + 280 + "px");
        quick.stop().css("top", scrollTop + 280 + "px");

        lastScrollTop = scrollTop;
    });
});

document.querySelectorAll(".depth1Li").forEach(function (item) {
    item.addEventListener("mouseenter", function () {
        this.classList.add("on");
    });
    item.addEventListener("mouseleave", function () {
        this.classList.remove("on");
    });
});

$(document).ready(function () {
    $("#toggle-info").click(function (e) {
        e.preventDefault();
        $(".text-copybox").stop().slideToggle(200);
        $(".arrow").toggleClass("up");
    });
});

$(document).ready(function () {
    function updateScrollbar($viewport) {
        var $slider = $viewport.siblings(".pane").find(".slider");
        var viewportHeight = $viewport.height();
        var contentHeight = $viewport[0].scrollHeight;
        var maxSliderTop = $viewport.siblings(".pane").height() - $slider.height();

        $viewport.off("scroll").on("scroll", function () {
            var scrollTop = $viewport.scrollTop();
            var scrollRatio = scrollTop / (contentHeight - viewportHeight);
            var newSliderTop = scrollRatio * maxSliderTop;
            $slider.css("top", newSliderTop + "px");
        });
    }

    $(".item-menu-hot").on("click", function () {
        $(".item-menu-hot").removeClass("on");
        $(".rankingViewport").hide();

        $(this).addClass("on");

        var index = $(".item-menu-hot").index(this);
        var $selectedViewport = $(".rankingViewport").eq(index);

        $selectedViewport.show();
        updateScrollbar($selectedViewport.find(".overViewpage")); // Cập nhật thanh cuộn khi đổi tab
    });

    // Khởi tạo thanh cuộn cho tab đầu tiên
    updateScrollbar($(".rankingViewport:visible .overViewpage"));

});

var mySwiperBanner = new Swiper(".mySwiperBanner", {
    slidesPerView: 1,
    spaceBetween: 0,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false
    },
    pagination: {
        el: ".swiper-pagination-banner",
    },
});

var mySwiperBannerMob = new Swiper(".mySwiperBannerMob", {
    slidesPerView: 1,
    spaceBetween: 0,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false
    },
});

var mySwiperProductTH = new Swiper(".mySwiperProductTH", {
    slidesPerView: 1,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false
    },
    pagination: {
        el: ".swiper-pagination-product-th",
    },
});

var mySwiperProductPropose = new Swiper(".mySwiperProductPropose", {
    slidesPerView: 4,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false
    },
    pagination: {
        el: ".swiper-pagination-product-propose",
    },
});

var mySwiperProductNew = new Swiper(".mySwiperProductNew", {
    slidesPerView: 4,
    spaceBetween: 10,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false
    },
    navigation: {
        nextEl: ".swiper-button-next-news",
        prevEl: ".swiper-button-prev-news",
    },
    breakpoints: {
        767: {
            slidesPerView: 4
        },
        500: {
            slidesPerView: 3
        },
        0: {
            slidesPerView: 2
        }
    }
});
var mySwiperProductRank1 = new Swiper(".mySwiperProductRank1", {
    slidesPerView: 4,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false
    },
    pagination: {
        el: ".swiper-pagination-product-rank1",
    },
});
var mySwiperProductRank2 = new Swiper(".mySwiperProductRank2", {
    slidesPerView: 4,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false
    },
    pagination: {
        el: ".swiper-pagination-product-rank2",
    },
});

$(".item-menu-rank").click(function () {
    $(".item-menu-rank").removeClass("active");

    $(this).addClass("active");

    if ($(this).text().includes("Xếp hạng hấp dẫn")) {
        $(".box-bestseller").hide();
        $(".box-rank").fadeIn();
    } else {
        $(".box-rank").hide();
        $(".box-bestseller").fadeIn();
    }
});
