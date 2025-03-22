document.addEventListener('DOMContentLoaded', function () {
    const btnGiaoTanNoi = document.getElementById('giaotannoi');
    const btnTuDenLay = document.getElementById('tudenlay');
    const diaChiNhan = document.getElementById('diachinhan').closest('.form-group');

    const giaoTanNoiGroup = document.getElementById('giaotannoi-group');
    const tuDenLayGroup = document.getElementById('tudenlay-group');

    // Giao Tận Nơi button click
    btnGiaoTanNoi.addEventListener('click', function () {
        btnGiaoTanNoi.classList.add('active');
        btnTuDenLay.classList.remove('active');
        diaChiNhan.style.display = 'block';
        giaoTanNoiGroup.style.display = 'block';
        tuDenLayGroup.style.display = 'none';
    });

    // Tự Đến Lấy button click
    btnTuDenLay.addEventListener('click', function () {
        btnTuDenLay.classList.add('active');
        btnGiaoTanNoi.classList.remove('active');
        diaChiNhan.style.display = 'none';
        giaoTanNoiGroup.style.display = 'none';
        tuDenLayGroup.style.display = 'block';
    });

    // Handle thanh toán radio buttons
    const radioChuyenKhoan = document.getElementById('deliverytime');
    const radioTienMat = document.getElementById('giaongay');

    // Tạo div chứa thông tin tài khoản
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
    } else {
        diaChiNhan.style.display = 'none';
        giaoTanNoiGroup.style.display = 'none';
        tuDenLayGroup.style.display = 'block';
    }
});