document.addEventListener('DOMContentLoaded', function() {
    // Xử lý chuyển đổi mode giao hàng
    const btnGiaoTanNoi = document.getElementById('giaotannoi');
    const btnTuDenLay = document.getElementById('tudenlay');
    const addressSelection = document.getElementById('address-selection');
    const giaoTanNoiGroup = document.getElementById('giaotannoi-group');
    const tuDenLayGroup = document.getElementById('tudenlay-group');
    const deliveryModeInput = document.getElementById('delivery_mode');
    const checkoutForm = document.querySelector('.info-nhan-hang');

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

    // Function to switch delivery mode
    function switchDeliveryMode(mode) {
        if (mode === 'giaotannoi') {
            btnGiaoTanNoi.classList.add('active');
            btnTuDenLay.classList.remove('active');
            addressSelection.style.display = 'block';
            giaoTanNoiGroup.style.display = 'block';
            tuDenLayGroup.style.display = 'none';
            deliveryModeInput.value = 'Giao tận nơi';
            
            // Automatically select "Sử dụng địa chỉ đã lưu" option
            const savedAddressRadio = document.querySelector('input[name="address_type"][value="saved"]');
            if (savedAddressRadio) {
                savedAddressRadio.checked = true;
                updateAddressFields();
            }
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

    // Handle address selection
    const addressTypeRadios = document.querySelectorAll('input[name="address_type"]');
    const savedAddressDiv = document.getElementById('saved-address');
    const newAddressDiv = document.getElementById('new-address');
    const savedAddressInput = savedAddressDiv.querySelector('input[name="diachinhan"]');
    const newAddressInput = newAddressDiv.querySelector('input[name="diachinhan_new"]');

    // Function to update address fields based on selection
    function updateAddressFields() {
        const selectedValue = document.querySelector('input[name="address_type"]:checked').value;
        
        if (selectedValue === 'saved') {
            savedAddressDiv.style.display = 'block';
            newAddressDiv.style.display = 'none';
            savedAddressInput.setAttribute('name', 'diachinhan');
            newAddressInput.setAttribute('name', 'diachinhan_new');
        } else {
            savedAddressDiv.style.display = 'none';
            newAddressDiv.style.display = 'block';
            savedAddressInput.setAttribute('name', 'diachinhan_new');
            newAddressInput.setAttribute('name', 'diachinhan');
        }
        
        // Log the current state for debugging
        console.log('Address type changed to:', selectedValue);
        console.log('Saved address input name:', savedAddressInput.getAttribute('name'));
        console.log('New address input name:', newAddressInput.getAttribute('name'));
    }

    // Add event listeners to radio buttons
    addressTypeRadios.forEach(radio => {
        radio.addEventListener('change', updateAddressFields);
    });

    // Initialize on page load
    updateAddressFields();

    // Add form submission handler
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            // Make sure the correct address field is used based on the selected address type
            const selectedValue = document.querySelector('input[name="address_type"]:checked').value;
            const deliveryMode = document.getElementById('delivery_mode').value;
            
            // Get the address values
            const savedAddress = savedAddressInput ? savedAddressInput.value : '';
            const newAddress = newAddressInput ? newAddressInput.value : '';
            
            // Set the final address based on the selected type and delivery mode
            let finalAddress = '';
            if (deliveryMode === 'Giao tận nơi') {
                if (selectedValue === 'saved') {
                    finalAddress = savedAddress;
                } else {
                    finalAddress = newAddress;
                }
            } else {
                // Khi chọn "Tự đến lấy", lấy địa chỉ chi nhánh được chọn
                const selectedBranch = document.querySelector('input[name="delivery_type"]:checked');
                if (selectedBranch) {
                    finalAddress = selectedBranch.value;
                } else {
                    finalAddress = "273 An Dương Vương, Phường 3, Quận 5"; // Địa chỉ mặc định
                }
            }
            
            // Set the final address in the hidden input
            const finalAddressInput = document.getElementById('final_address');
            if (finalAddressInput) {
                finalAddressInput.value = finalAddress;
            }
            
            // Log debugging information
            console.log('Form submission details:');
            console.log('Delivery mode:', deliveryMode);
            console.log('Address type:', selectedValue);
            console.log('Saved address:', savedAddress);
            console.log('New address:', newAddress);
            console.log('Final address:', finalAddress);
            
            // Log all form data
            const formData = new FormData(checkoutForm);
            console.log('Form data:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
        });
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
    const savedAddressDiv = document.getElementById('saved-address');
    const newAddressDiv = document.getElementById('new-address');
    const savedAddressInput = savedAddressDiv ? savedAddressDiv.querySelector('input[name="diachinhan"]') : null;
    const newAddressInput = newAddressDiv ? newAddressDiv.querySelector('input[name="diachinhan_new"]') : null;

    if (mode === 'giaotannoi') {
        btnGiaoTanNoi.classList.add('active');
        btnTuDenLay.classList.remove('active');
        addressSelection.style.display = 'block';
        giaoTanNoiGroup.style.display = 'block';
        tuDenLayGroup.style.display = 'none';
        deliveryModeInput.value = 'Giao tận nơi';
        
        // Automatically select "Sử dụng địa chỉ đã lưu" option
        const savedAddressRadio = document.querySelector('input[name="address_type"][value="saved"]');
        if (savedAddressRadio) {
            savedAddressRadio.checked = true;
            // Update address fields to show saved address
            if (savedAddressDiv && newAddressDiv) {
                savedAddressDiv.style.display = 'block';
                newAddressDiv.style.display = 'none';
                
                // Make sure the saved address input has the correct name
                if (savedAddressInput) {
                    savedAddressInput.setAttribute('name', 'diachinhan');
                }
                if (newAddressInput) {
                    newAddressInput.setAttribute('name', 'diachinhan_new');
                }
            }
        }
    } else {
        btnTuDenLay.classList.add('active');
        btnGiaoTanNoi.classList.remove('active');
        addressSelection.style.display = 'none';
        giaoTanNoiGroup.style.display = 'none';
        tuDenLayGroup.style.display = 'block';
        deliveryModeInput.value = 'Mua trực tiếp';
    }
} 