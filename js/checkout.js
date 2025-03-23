document.addEventListener('DOMContentLoaded', function () {
    const btnGiaoTanNoi = document.getElementById('giaotannoi');
    const btnTuDenLay = document.getElementById('tudenlay');
    const diaChiNhan = document.getElementById('diachinhan').closest('.form-group');

    const giaoTanNoiGroup = document.getElementById('giaotannoi-group');
    const tuDenLayGroup = document.getElementById('tudenlay-group');

    const deliveryModeInput = document.getElementById('delivery_mode'); // Hidden input

    // Giao Tận Nơi button click
    btnGiaoTanNoi.addEventListener('click', function () {
        btnGiaoTanNoi.classList.add('active');
        btnTuDenLay.classList.remove('active');
        diaChiNhan.style.display = 'block';
        giaoTanNoiGroup.style.display = 'block';
        tuDenLayGroup.style.display = 'none';

        deliveryModeInput.value = 'Giao tận nơi'; // Set value
    });

    // Tự Đến Lấy button click
    btnTuDenLay.addEventListener('click', function () {
        btnTuDenLay.classList.add('active');
        btnGiaoTanNoi.classList.remove('active');
        diaChiNhan.style.display = 'none';
        giaoTanNoiGroup.style.display = 'none';
        tuDenLayGroup.style.display = 'block';

        deliveryModeInput.value = 'Mua trực tiếp'; // Set value
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
        diaChiNhan.style.display = 'block';
        giaoTanNoiGroup.style.display = 'block';
        tuDenLayGroup.style.display = 'none';
        deliveryModeInput.value = 'Giao tận nơi';
    } else {
        diaChiNhan.style.display = 'none';
        giaoTanNoiGroup.style.display = 'none';
        tuDenLayGroup.style.display = 'block';
        deliveryModeInput.value = 'Mua trực tiếp';
    }
});
