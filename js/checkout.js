document.addEventListener('DOMContentLoaded', function() {
    // Xử lý chuyển đổi mode giao hàng
    const btnGiaoTanNoi = document.getElementById('giaotannoi');
    const btnTuDenLay = document.getElementById('tudenlay');
    const addressSelection = document.getElementById('address-selection');
    const giaoTanNoiGroup = document.getElementById('giaotannoi-group');
    const tuDenLayGroup = document.getElementById('tudenlay-group');
    const deliveryModeInput = document.getElementById('delivery_mode');

    // Xử lý hiển thị thông tin chuyển khoản cho cả hai mode
    function setupPaymentHandlers(radioChuyenKhoan, radioTienMat, bankInfo) {
        if (radioChuyenKhoan) {
            radioChuyenKhoan.addEventListener('change', function() {
                if (bankInfo) {
                    bankInfo.style.display = this.checked ? 'block' : 'none';
                }
            });
        }

        if (radioTienMat) {
            radioTienMat.addEventListener('change', function() {
                if (bankInfo) {
                    bankInfo.style.display = 'none';
                }
            });
        }
    }

    // Thiết lập cho mode giao tận nơi
    setupPaymentHandlers(
        document.getElementById('deliverytime'),
        document.getElementById('giaongay'),
        document.getElementById('bank-info')
    );

    // Thiết lập cho mode tự đến lấy
    setupPaymentHandlers(
        document.getElementById('chuyenkhoan_store'),
        document.getElementById('tienmat_store'),
        document.getElementById('bank-info-store')
    );

    function switchDeliveryMode(mode) {
        if (mode === 'giaotannoi') {
            btnGiaoTanNoi.classList.add('active');
            btnTuDenLay.classList.remove('active');
            addressSelection.style.display = 'block';
            giaoTanNoiGroup.style.display = 'block';
            tuDenLayGroup.style.display = 'none';
            deliveryModeInput.value = 'Giao tận nơi';
        } else {
            btnTuDenLay.classList.add('active');
            btnGiaoTanNoi.classList.remove('active');
            addressSelection.style.display = 'none';
            giaoTanNoiGroup.style.display = 'none';
            tuDenLayGroup.style.display = 'block';
            deliveryModeInput.value = 'Mua trực tiếp';
        }
    }

    // Gán sự kiện click cho các nút
    btnGiaoTanNoi.addEventListener('click', () => switchDeliveryMode('giaotannoi'));
    btnTuDenLay.addEventListener('click', () => switchDeliveryMode('tudenlay'));

    // Thiết lập trạng thái mặc định
    switchDeliveryMode('tudenlay');

    // Kiểm tra và hiển thị thông tin chuyển khoản nếu đã được chọn
    const chuyenKhoanStore = document.getElementById('chuyenkhoan_store');
    const bankInfoStore = document.getElementById('bank-info-store');
    if (chuyenKhoanStore && chuyenKhoanStore.checked && bankInfoStore) {
        bankInfoStore.style.display = 'block';
    }
});

// Thêm hàm global để xử lý từ onclick
function switchDeliveryMode(mode) {
    const btnGiaoTanNoi = document.getElementById('giaotannoi');
    const btnTuDenLay = document.getElementById('tudenlay');
    const addressSelection = document.getElementById('address-selection');
    const giaoTanNoiGroup = document.getElementById('giaotannoi-group');
    const tuDenLayGroup = document.getElementById('tudenlay-group');
    const deliveryModeInput = document.getElementById('delivery_mode');

    if (mode === 'giaotannoi') {
        btnGiaoTanNoi.classList.add('active');
        btnTuDenLay.classList.remove('active');
        addressSelection.style.display = 'block';
        giaoTanNoiGroup.style.display = 'block';
        tuDenLayGroup.style.display = 'none';
        deliveryModeInput.value = 'Giao tận nơi';
    } else {
        btnTuDenLay.classList.add('active');
        btnGiaoTanNoi.classList.remove('active');
        addressSelection.style.display = 'none';
        giaoTanNoiGroup.style.display = 'none';
        tuDenLayGroup.style.display = 'block';
        deliveryModeInput.value = 'Mua trực tiếp';
    }
}