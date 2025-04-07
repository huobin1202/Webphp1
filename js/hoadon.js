document.addEventListener('DOMContentLoaded', function () {
    const modal = document.querySelector('.modal.detail-order');
    const detailButtons = document.querySelectorAll('.order-history-detail-btn');

    // Function to format date to dd/mm/yyyy
    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Gán dữ liệu vào modal
            const orderDate = this.dataset.createdAt;
            document.getElementById('modal-created-at').innerText = formatDate(orderDate);
            document.getElementById('modal-delivery-date').innerText = formatDate(orderDate);
            document.getElementById('modal-delivery-type').innerText = this.dataset.deliveryType;
            document.getElementById('modal-address').innerText = this.dataset.address;
            document.getElementById('modal-recipient-name').innerText = this.dataset.recipientName;
            document.getElementById('modal-recipient-phone').innerText = this.dataset.recipientPhone;

            // Hiển thị modal
            modal.classList.add('open');
        });
    });

    // Hàm đóng modal
    window.closeModal = function () {
        modal.classList.remove('open');
    }
});

function confirmCancelOrder() {
    return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');
}