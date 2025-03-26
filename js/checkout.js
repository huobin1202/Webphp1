document.addEventListener('DOMContentLoaded', function () {
    const btnGiaoTanNoi = document.getElementById('giaotannoi');
    const btnTuDenLay = document.getElementById('tudenlay');
    const addressSelection = document.getElementById('address-selection');
    const giaoTanNoiGroup = document.getElementById('giaotannoi-group');
    const tuDenLayGroup = document.getElementById('tudenlay-group');
    const deliveryModeInput = document.getElementById('delivery_mode');

    // Xử lý chọn địa chỉ
    const addressTypeInputs = document.querySelectorAll('input[name="address_type"]');
    const savedAddressDiv = document.getElementById('saved-address');
    const newAddressDiv = document.getElementById('new-address');
    const diaChiNhanInput = document.getElementById('diachinhan');

    addressTypeInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.value === 'saved') {
                savedAddressDiv.style.display = 'block';
                newAddressDiv.style.display = 'none';
                diaChiNhanInput.value = savedAddressDiv.querySelector('input').value;
            } else {
                savedAddressDiv.style.display = 'none';
                newAddressDiv.style.display = 'block';
                diaChiNhanInput.value = '';
            }
        });
    });

    // Giao Tận Nơi button click
    btnGiaoTanNoi.addEventListener('click', function () {
        btnGiaoTanNoi.classList.add('active');
        btnTuDenLay.classList.remove('active');
        addressSelection.style.display = 'block';
        giaoTanNoiGroup.style.display = 'block';
        tuDenLayGroup.style.display = 'none';
        deliveryModeInput.value = 'Giao tận nơi';
    });

    // Tự Đến Lấy button click
    btnTuDenLay.addEventListener('click', function () {
        btnTuDenLay.classList.add('active');
        btnGiaoTanNoi.classList.remove('active');
        addressSelection.style.display = 'none';
        giaoTanNoiGroup.style.display = 'none';
        tuDenLayGroup.style.display = 'block';
        deliveryModeInput.value = 'Mua trực tiếp';
    });

    // Handle thanh toán radio buttons
    const radioChuyenKhoan = document.getElementById('deliverytime');
    const radioTienMat = document.getElementById('giaongay');

    const accountInfo = document.createElement('div');
    accountInfo.classList.add('account-info');
    accountInfo.innerHTML = `
        <p>Ngân hàng: Vietcombank</p>
        <p>Số tài khoản: 123456789</p>
        <p>Chủ tài khoản: Nguyễn Văn A</p>
    `;
    accountInfo.style.display = 'none';
    giaoTanNoiGroup.appendChild(accountInfo);

    radioChuyenKhoan.addEventListener('change', function () {
        if (radioChuyenKhoan.checked) {
            accountInfo.style.display = 'block';
        }
    });

    radioTienMat.addEventListener('change', function () {
        if (radioTienMat.checked) {
            accountInfo.style.display = 'none';
        }
    });

    // Default state
    if (btnGiaoTanNoi.classList.contains('active')) {
        addressSelection.style.display = 'block';
        giaoTanNoiGroup.style.display = 'block';
        tuDenLayGroup.style.display = 'none';
        deliveryModeInput.value = 'Giao tận nơi';
    } else {
        addressSelection.style.display = 'none';
        giaoTanNoiGroup.style.display = 'none';
        tuDenLayGroup.style.display = 'block';
        deliveryModeInput.value = 'Mua trực tiếp';
    }
});
