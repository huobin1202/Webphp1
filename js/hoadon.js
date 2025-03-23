document.addEventListener('DOMContentLoaded', function () {
    const modal = document.querySelector('.modal.detail-order');
    const detailButtons = document.querySelectorAll('.order-history-detail-btn');

    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Gán dữ liệu vào modal
            document.getElementById('modal-created-at').innerText = this.dataset.createdAt;
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
