function checkProductStatus(productId) {
    // Gửi AJAX request để kiểm tra trạng thái sản phẩm
    fetch('check_product_status.php?id=' + productId)
        .then(response => response.json())
        .then(data => {
            if (data.sold) {
                // Nếu sản phẩm đã được bán (có trong order_details), hiển thị thông báo ẩn/hiện
                if (confirm(data.status == 1 ? "Sản phẩm này đã được bán, ẩn sản phẩm?" : "Bạn có muốn hiển thị sản phẩm này?")) {
                    window.location.href = 'toggle_product.php?id=' + productId;
                }
            } else {
                // Nếu sản phẩm chưa được bán (không có trong order_details), hiển thị thông báo xóa
                if (confirm("Bạn có muốn xóa sản phẩm này?")) {
                    window.location.href = 'delete_product.php?id=' + productId;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi kiểm tra trạng thái sản phẩm');
        });
    return false;
} 