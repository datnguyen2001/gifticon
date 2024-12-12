function setActiveTab(tab) {
    document.querySelectorAll('.product-wrapper, .store-wrapper,.store-reviews').forEach((el) => {
        el.classList.remove('active');
    });

    if (tab === 'detail') {
        document.querySelector('.product-wrapper').classList.add('active');
        document.querySelector('.detail-tab').style.display = 'block';
        document.querySelector('.guide-tab').style.display = 'none';
        document.querySelector('.reviews-tab').style.display = 'none';
    } else if (tab === 'guide') {
        document.querySelector('.store-wrapper').classList.add('active');
        document.querySelector('.guide-tab').style.display = 'block';
        document.querySelector('.detail-tab').style.display = 'none';
        document.querySelector('.reviews-tab').style.display = 'none';
    }else if (tab === 'product_reviews') {
        document.querySelector('.store-reviews').classList.add('active');
        document.querySelector('.reviews-tab').style.display = 'block';
        document.querySelector('.guide-tab').style.display = 'none';
        document.querySelector('.detail-tab').style.display = 'none';
    }
}

$(document).ready(function() {
    $('.buy-now').on('click', function(e) {
        var quantity = $(this).data('quantity');
        var route = $(this).data('route');

        if (parseInt(quantity) === 0) {
            e.preventDefault();
            hideLoading();

            $(".failed-message").text("Sản phẩm đã hết hàng!");
            $(".failed-alert").fadeIn();
            $(".failed-overlay").fadeIn();
        } else if (parseInt(quantity) > 0) {
            window.location.href = route;
        }
    });

    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        hideLoading();
        var quantity = $(this).data('quantity');
        if(!userLogin){
            $(".warning-message").text("Vui lòng đăng nhập để thực hiện chức năng này!");
            $(".warning-alert").fadeIn();
            $(".warning-overlay").fadeIn();
            return;
        }
        if (parseInt(quantity) === 0) {
            $(".failed-message").text("Sản phẩm đã hết hàng!");
            $(".failed-alert").fadeIn();
            $(".failed-overlay").fadeIn();
        } else if (parseInt(quantity) > 0) {
            $("#addtoCartModel").modal('show');
        }
    });
});

$('.rating-item input[type="radio"]').on('change', function () {
    const selectedRating = $(this).val();
    $('.start').removeClass('ratingAll')
    if (selectedRating == 2) {
        $("#rating1").addClass('ratingAll');
    }
    if (selectedRating == 3) {
        $("#rating1").addClass('ratingAll');
        $("#rating2").addClass('ratingAll');
    }
    if (selectedRating == 4) {
        $("#rating1").addClass('ratingAll');
        $("#rating2").addClass('ratingAll');
        $("#rating3").addClass('ratingAll');
    }
    if (selectedRating == 5) {
        $("#rating1").addClass('ratingAll');
        $("#rating2").addClass('ratingAll');
        $("#rating3").addClass('ratingAll');
        $("#rating4").addClass('ratingAll');
    }
});

function formatDate(dateString) {
    const date = new Date(dateString); // Tạo đối tượng Date từ chuỗi
    const hours = date.getHours().toString().padStart(2, '0'); // Giờ
    const minutes = date.getMinutes().toString().padStart(2, '0'); // Phút
    const day = date.getDate().toString().padStart(2, '0'); // Ngày
    const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Tháng (tháng bắt đầu từ 0, nên phải cộng 1)
    const year = date.getFullYear(); // Năm

    return `${hours}:${minutes} ${day}/${month}/${year}`;
}

$(document).ready(function() {
    let page = 1;
    const productId = $('#product_sp_id').val();

    $('#load-more').click(function() {
        page++;

        $.ajax({
            url: window.location.origin + '/load-reviews',
            method: 'GET',
            data: {
                product_sp_id: productId,
                page: page,
            },
            success: function(response) {
                console.log(99,response.reviews)
                if (response.reviews.length > 0) {
                    response.reviews.forEach(function(review) {
                        const formattedDate = formatDate(review.created_at);
                        $('#reviews-container').append(`
                            <div class="item-review">
                                <div class="item-top-content-star">
                                    <div class="img-avatar">
                                        <img src="${review.user.avatar}" alt="">
                                        <span>${review.user.full_name}</span>
                                    </div>
                                    <div class="star-date">
                                        <div class="product-rate">
                                            <div class="star-rating star-rating-review"
                                                 style="--rating: ${review.star}; font-size: 16px"></div>
                                        </div>
                                        <span>${formattedDate}</span>
                                    </div>
                                </div>
                                <div class="item-content-reviews">
                                    ${review.content}
                                </div>
                            </div>
                        `);
                    });

                    if (!response.has_more) {
                        $('#load-more').hide();
                    }
                }
            },
            error: function() {
                alert('Có lỗi xảy ra khi tải thêm đánh giá.');
            }
        });
    });
});


