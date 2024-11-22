document.querySelectorAll('.fa-heart').forEach(item => {
    item.addEventListener('click', function (event) {

        let productId = this.getAttribute('data-product-id');
        let heartIcon = this;

        fetch('/toggle-favorite/' + productId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'added') {
                    heartIcon.style.color = '#F1641E';
                    toastr.success('Sản phẩm đã được thêm vào yêu thích!');
                } else if(data.status === 'removed') {
                    heartIcon.style.color = '#c3c3c3cc';
                    toastr.success('Sản phẩm đã được xóa khỏi yêu thích.');
                }else {
                    window.location.href = '/dang-nhap';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});
