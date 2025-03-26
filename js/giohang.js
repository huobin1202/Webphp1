const cartbtn = document.querySelector(".dong");
const cartshow = document.querySelector(".ravao");
cartshow.addEventListener("click", function () {
    document.querySelector(".cart").style.right = "0";
});
cartbtn.addEventListener("click", function () {
    document.querySelector(".cart").style.right = "-100%";
});

document.addEventListener("DOMContentLoaded", function () {
    // Thêm vào giỏ hàng bằng AJAX
    document.querySelectorAll("form.add-to-cart").forEach(form => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            fetch("add_to_cart.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    updateCart();
                    document.querySelector(".cart").style.right = "0";
                } else {
                    window.location.href = 'dn.php';
                }
            })
            .catch(error => {
                console.error("Lỗi khi thêm sản phẩm vào giỏ hàng:", error);
                alert("Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng!");
            });
        });
    });

    // Cập nhật số lượng sản phẩm bằng AJAX
    document.querySelectorAll(".cart-quantity").forEach(input => {
        input.addEventListener("change", function () {
            let cartId = this.dataset.cartId;
            let newQuantity = this.value;
            if (newQuantity < 1) {
                newQuantity = 1;
                this.value = 1;
            }
            fetch("update_cart.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `cart_id=${cartId}&quantity=${newQuantity}`
            })
            .then(response => response.json())
            .then(data => {
                updateCart();
            })
            .catch(error => console.error("Lỗi khi cập nhật số lượng:", error));
        });
    });

    // Xóa sản phẩm khỏi giỏ hàng bằng AJAX
    document.querySelectorAll(".delete-item").forEach(button => {
        button.addEventListener("click", function () {
            let cartId = this.dataset.cartId;
            if (confirm("Bạn có chắc muốn xóa sản phẩm này?")) {
                fetch("update_cart.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `cart_id=${cartId}&delete=true`
                })
                .then(response => response.json())
                .then(data => {
                    updateCart();
                })
                .catch(error => console.error("Lỗi khi xóa sản phẩm:", error));
            }
        });
    });

    // Hàm cập nhật giỏ hàng mà không tải lại trang
    function updateCart() {
        fetch("update_cart.php", { method: "POST" })
            .then(response => response.json())
            .then(data => {
                document.querySelector("tbody").innerHTML = data.cart_html;
                document.querySelector(".price-total span").innerText = data.total_price;
                setTimeout(attachEventListeners, 0);
            })
            .catch(error => console.error("Lỗi khi tải giỏ hàng:", error));
    }

    // Hàm gán lại sự kiện sau mỗi lần cập nhật giỏ hàng
    function attachEventListeners() {
        document.querySelectorAll(".cart-quantity").forEach(input => {
            input.addEventListener("change", function () {
                let cartId = this.dataset.cartId;
                let newQuantity = this.value;
                if (newQuantity < 1) {
                    newQuantity = 1;
                    this.value = 1;
                }
                fetch("update_cart.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `cart_id=${cartId}&quantity=${newQuantity}`
                })
                .then(response => response.json())
                .then(data => {
                    updateCart();
                })
                .catch(error => console.error("Lỗi khi cập nhật số lượng:", error));
            });
        });

        document.querySelectorAll(".delete-item").forEach(button => {
            button.addEventListener("click", function () {
                let cartId = this.dataset.cartId;
                if (confirm("Bạn có chắc muốn xóa sản phẩm này?")) {
                    fetch("update_cart.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: `cart_id=${cartId}&delete=true`
                    })
                    .then(response => response.json())
                    .then(data => {
                        updateCart();
                    })
                    .catch(error => console.error("Lỗi khi xóa sản phẩm:", error));
                }
            });
        });
    }
});
