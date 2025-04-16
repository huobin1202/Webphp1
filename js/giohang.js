document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cart-button').forEach(button => {
        button.addEventListener('click', function() {
            if (!isLoggedIn) {
                showToast("Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng", "error");
                return;
            }

            const productId = this.dataset.productId;
            const productPrice = this.dataset.productPrice;
            const productImg = this.dataset.productImg;
            const isInCart = this.classList.contains('in-cart');

            fetch('cart_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=${isInCart ? 'remove' : 'add'}&product_id=${productId}&price=${productPrice}&img=${productImg}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (isInCart) {
                        this.classList.remove('in-cart');
                        this.textContent = '+ Thêm vào giỏ hàng';
                    } else {
                        this.classList.add('in-cart');
                        this.textContent = '- Xóa khỏi giỏ hàng';
                    }
                }
            });
        });
    });
}); 
