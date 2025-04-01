document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cart-button').forEach(button => {
        button.addEventListener('click', function() {
            if (!isLoggedIn) {
                window.location.href = 'dn.php';
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
function updateQuantity(btn, change) {
    const input = btn.parentElement.querySelector('.quantity-input');
    const currentValue = parseInt(input.value);
    let newValue = currentValue + change;

    // Giới hạn giá trị từ 1 đến 10
    newValue = Math.max(1, Math.min(10, newValue));

    // Cập nhật giá trị cho input
    input.value = newValue;

    // Cập nhật giá trị cho hidden input trong form
    const form = btn.closest('.button-container').querySelector('form');
    const hiddenInput = form.querySelector('.quantity-hidden');
    hiddenInput.value = newValue;

    // Cập nhật hiển thị giá tiền
    const unitPrice = parseInt(input.getAttribute('data-price'));
    const newTotal = unitPrice * newValue;
    const priceElement = btn.closest('.button-container').querySelector('.product-price');
    priceElement.textContent = new Intl.NumberFormat('vi-VN').format(newTotal) + 'đ';
}

function prepareUpdate(btn) {
    const form = btn.closest('form');
    const quantityInput = form.previousElementSibling.querySelector('.quantity-input');
    const hiddenInput = form.querySelector('.quantity-hidden');
    hiddenInput.value = quantityInput.value;
    return true;
}

// Vô hiệu hóa việc nhập trực tiếp vào input số lượng
document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('keydown', (e) => {
        e.preventDefault();
        return false;
    });
});

// Hiển thị thông báo nếu có
<?php if (isset($_SESSION['success'])): ?>
    alert("<?php echo $_SESSION['success']; ?>");
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    alert("<?php echo $_SESSION['error']; ?>");
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>