document.addEventListener("DOMContentLoaded", () => {
    // Sự kiện cho nút thêm sản phẩm
    

    // Sự kiện cho nút chỉnh sửa sản phẩm
    

    // Sự kiện cho nút thêm khách hàng
    

    // Sự kiện cho nút sửa thông tin khách hàng
  
    const changeLogOut = document.getElementById("logoutacc");
    if (changeLogOut) {
        changeLogOut.addEventListener("click", () => {
            if (confirm("Bạn có muốn thoát tài khoản quản trị và trở về trang chủ?") == true) {
                alert("Đã thoát!");
                window.location.href = "index.php";
            }
        });
    }
});

// Xóa sản phẩm
// Bật mở Menu
// Lấy các phần tử DOM
const menuIconButton = document.querySelector(".menu-icon-btn");
const sidebar = document.querySelector(".sidebar");

// Khi tải trang, kiểm tra trạng thái từ LocalStorage
document.addEventListener("DOMContentLoaded", () => {
    let isOpen = localStorage.getItem("sidebarOpen");

    // Nếu LocalStorage chưa có giá trị, mặc định là "true"
    if (isOpen === null) {
        isOpen = "true"; // Đặt trạng thái mặc định
        localStorage.setItem("sidebarOpen", isOpen);
    }

    // Tạm thời tắt hiệu ứng
    sidebar.classList.add("no-animation");

    // Áp dụng trạng thái
    if (isOpen === "true") {
        sidebar.classList.add("open");
    } else {
        sidebar.classList.remove("open");
    }

    // Gỡ bỏ lớp "no-animation" để hiệu ứng hoạt động bình thường
    setTimeout(() => {
        sidebar.classList.remove("no-animation");
    }, 0);
});

// Thêm sự kiện click cho nút menu
menuIconButton.addEventListener("click", () => {
    const isCurrentlyOpen = sidebar.classList.toggle("open"); // Đổi trạng thái
    localStorage.setItem("sidebarOpen", isCurrentlyOpen); // Lưu trạng thái vào LocalStorage
});

// Đơn hàng
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("form-search-order");
    const statusFilter = document.getElementById("tinh-trang");
    const resetButton = document.querySelector(".btn-reset-order");
    const tableRows = document.querySelectorAll("#showOrder tr");
    
    // Hàm chuẩn hóa chuỗi để tìm kiếm không phân biệt chữ hoa/thường
    function normalizeString(str) {
        return str.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    }

    // Hàm kiểm tra ngày có nằm trong khoảng không
    function isDateInRange(dateStr, startDate, endDate) {
        if (!startDate && !endDate) return true;
        const date = new Date(dateStr);
        const start = startDate ? new Date(startDate) : null;
        const end = endDate ? new Date(endDate) : null;
        
        if (start && end) {
            return date >= start && date <= end;
        } else if (start) {
            return date >= start;
        } else if (end) {
            return date <= end;
        }
        return true;
    }

    // Hàm lọc bảng đơn hàng
    function filterTable() {
        const searchTerm = normalizeString(searchInput.value);
        const selectedStatus = statusFilter.value;
        const startDate = document.getElementById('time-start').value;
        const endDate = document.getElementById('time-end').value;
        
        tableRows.forEach(row => {
            const cells = row.querySelectorAll("td");
            const orderID = cells[0].innerText.toLowerCase().trim(); // Mã đơn hàng
            const customerID = cells[1].innerText.toLowerCase().trim(); // Mã khách hàng
            const customerName = cells[2].innerText.toLowerCase().trim(); // Tên khách hàng
            const statusSelect = cells[5].querySelector('select'); // Select box trạng thái
            const currentStatus = statusSelect ? statusSelect.value : '';

            // Kiểm tra điều kiện tìm kiếm
            const matchesSearch = orderID.includes(searchTerm) ||
                customerID.includes(searchTerm) ||
                customerName.includes(searchTerm);

            // Kiểm tra trạng thái
            let matchesStatus = true;
            if (selectedStatus !== "4") { // Nếu không phải "Tất cả"
                switch(selectedStatus) {
                    case "0": // Chưa xử lý
                        matchesStatus = currentStatus === "chuaxuly";
                        break;
                    case "1": // Đã xử lý
                        matchesStatus = currentStatus === "daxuly";
                        break;
                    case "2": // Chưa giao
                        matchesStatus = currentStatus === "chuagiao";
                        break;
                    case "3": // Đã giao
                        matchesStatus = currentStatus === "dagiao";
                        break;
                }
            }

            // Kiểm tra ngày tháng
            const matchesDate = isDateInRange(cells[3].innerText, startDate, endDate);

            // Hiển thị/Ẩn dòng dựa trên điều kiện phù hợp
            row.style.display = (matchesSearch && matchesStatus && matchesDate) ? '' : 'none';
        });
    }

    // Hàm reset bộ lọc
    function resetFilters() {
        searchInput.value = '';
        statusFilter.value = '4';
        document.getElementById('time-start').value = '';
        document.getElementById('time-end').value = '';
        
        tableRows.forEach(row => row.style.display = '');
    }

    // Gắn sự kiện
    searchInput.addEventListener("input", filterTable);
    statusFilter.addEventListener("change", filterTable);
    resetButton.addEventListener("click", (e) => {
        e.preventDefault(); // Ngăn hành vi mặc định của nút
        resetFilters();
    });
});

document.querySelectorAll('.view-order-btn').forEach(button => {
    button.addEventListener('click', () => {
        const modal = document.querySelector('.modal.detail-order');
        modal.classList.add('open');

        modal.querySelector('.modal-container-title').innerText = `CHI TIẾT ĐƠN HÀNG #${button.dataset.orderId}`;

        const info = modal.querySelectorAll('.detail-order-item-right');
        info[0].innerText = button.dataset.createdDate;
        info[1].innerText = button.dataset.deliveryType;
        info[2].innerText = button.dataset.recipientName;
        info[3].innerText = button.dataset.recipientPhone;

        modal.querySelectorAll('.detail-order-item-b')[0].innerText = button.dataset.address;
        modal.querySelectorAll('.detail-order-item-b')[1].innerText = button.dataset.note;
        modal.querySelector('.price').innerText = button.dataset.total;

        const productGroup = modal.querySelector('.order-item-group');
        const products = JSON.parse(button.dataset.products);
        productGroup.innerHTML = '';
        products.forEach(item => {
            productGroup.innerHTML += `
    <div class="order-product">
        <div class="order-product-left">
            <img src="/Webphp1/sanpham/${item.hinhanh}" alt="">
            <div class="order-product-info">
                <h4>${item.tensp}</h4>
                <p class="order-product-quantity">SL: ${item.soluong}</p>
            </div>
        </div>
        <div class="order-product-right">
            <div class="order-product-price">
                <span class="order-product-current-price">${Number(item.price).toLocaleString('vi-VN')}đ</span>
            </div>
        </div>
    </div>`;
        });

        // Button trạng thái
        const form = modal.querySelector('.modal-detail-bottom-right form');
        if (button.dataset.status == 0) {
            form.method = "post";
            form.innerHTML = `
    <input type="hidden" name="order_id" value="${button.dataset.orderId}">
    <button type="submit" name="process_order" class="modal-detail-btn btn-chuaxuly">Chưa xử lý</button>
`;
        } else {
            form.innerHTML = `<button disabled class="modal-detail-btn btn-daxuly">Đã xử lý</button>`;
        }
    });
});

document.querySelector('.modal-close').addEventListener('click', () => {
    document.querySelector('.modal.detail-order').classList.remove('open');
});

// Khách hàng
document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('form-search-user');
        const statusSelect = document.getElementById('tinh-trang-user');
        const tableRows = document.querySelectorAll('#show-user tr');

        function filterTable() {
            const searchValue = searchInput.value.toLowerCase();
            const statusValue = statusSelect.value;

            tableRows.forEach(row => {
                const id = row.cells[0]?.textContent.toLowerCase() || '';
                const name = row.cells[1]?.textContent.toLowerCase() || '';
                const contact = row.cells[2]?.textContent.toLowerCase() || '';
                const status = row.querySelector('.status-complete') ? '1' : '0';

                // Kiểm tra điều kiện tìm kiếm
                const matchesSearch = !searchValue || 
                    id.includes(searchValue) || 
                    name.includes(searchValue) || 
                    contact.includes(searchValue);

                // Kiểm tra điều kiện trạng thái
                const matchesStatus = statusValue === '2' || status === statusValue;

                // Hiển thị hoặc ẩn dòng dựa trên kết quả
                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });

            // Kiểm tra và hiển thị thông báo không tìm thấy
            const visibleRows = document.querySelectorAll('#show-user tr[style=""]').length;
            const noResultsRow = document.querySelector('.no-results');
            
            if (visibleRows === 0) {
                if (!noResultsRow) {
                    const tbody = document.getElementById('show-user');
                    const newRow = document.createElement('tr');
                    newRow.className = 'no-results';
                    newRow.innerHTML = '<td colspan="6" class="no-products">Không tìm thấy khách hàng nào!</td>';
                    tbody.appendChild(newRow);
                }
            } else if (noResultsRow) {
                noResultsRow.remove();
            }
        }

        // Thêm sự kiện lắng nghe cho input tìm kiếm
        if (searchInput) {
            searchInput.addEventListener('input', filterTable);
        }

        // Thêm sự kiện lắng nghe cho select trạng thái
        if (statusSelect) {
            statusSelect.addEventListener('change', filterTable);
        }

        // Hàm reset tìm kiếm
        window.resetSearch = function() {
            searchInput.value = '';
            statusSelect.value = '2';
            filterTable();
        }
    });

// Thống kê sản phẩm, khách hàng
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('form-search-tk');
    const sortAscBtn = document.querySelector('.btn-reset-order:nth-child(2)');
    const sortDescBtn = document.querySelector('.btn-reset-order:nth-child(3)');
    const resetBtn = document.querySelector('.btn-reset-order:nth-child(4)');
    let tableBody = document.querySelectorAll('#showTk'); // Khởi tạo các dòng trong bảng
    const table = document.querySelector('.table table'); // Phần tử bảng chính

    const originalData = Array.from(tableBody).map(tbody => tbody.cloneNode(true)); // Lưu các dòng gốc

    // Hàm trợ giúp để định dạng tiền tệ thành số
    const parseCurrency = (str) => parseInt(str.replace(/[^0-9]/g, ''));

    // Hàm lọc bảng
    const filterTable = () => {
        const searchValue = searchInput.value.toLowerCase();

        tableBody.forEach((tbody) => {
            const customerCode = tbody.querySelector('td:nth-child(1)').textContent.toLowerCase();
            const customerName = tbody.querySelector('td:nth-child(2) p').textContent.toLowerCase();
            const matchesCode = customerCode.includes(searchValue);
            const matchesName = customerName.includes(searchValue);

            tbody.style.display = searchValue === '' || matchesCode || matchesName ? '' : 'none';
        });
    };

    // Hàm sắp xếp bảng
    const sortTable = (ascending = true) => {
        const rows = Array.from(tableBody);
        rows.sort((a, b) => {
            const revenueA = parseCurrency(a.querySelector('td:nth-child(4)').textContent);
            const revenueB = parseCurrency(b.querySelector('td:nth-child(4)').textContent);
            return ascending ? revenueA - revenueB : revenueB - revenueA;
        });

        // Xóa và thêm lại các dòng đã sắp xếp
        rows.forEach(row => table.appendChild(row));
    };

    // Đặt lại bảng
    const resetTable = () => {
        searchInput.value = '';
        // Xóa các dòng hiện tại và khôi phục các dòng gốc
        tableBody.forEach(row => row.remove());
        originalData.forEach(row => table.appendChild(row.cloneNode(true)));

        // Khởi tạo lại `tableBody` sau khi đặt lại
        tableBody = document.querySelectorAll('#showTk');
    };

    searchInput.addEventListener('input', filterTable);

    sortAscBtn.addEventListener('click', () => sortTable(true));
    sortDescBtn.addEventListener('click', () => sortTable(false));
    resetBtn.addEventListener('click', resetTable);
});

// Hàm cập nhật trạng thái đơn hàng
function updateOrderStatus(orderId, newStatus) {
        fetch('update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}&new_status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra khi cập nhật trạng thái!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi cập nhật trạng thái!');
        });
    
}

document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("form-search-user");
    const statusFilter = document.getElementById("tinh-trang-user");

    function updateSearch() {
        const searchValue = searchInput.value;
        const statusValue = statusFilter.value;
        
        // Tạo URL với tham số tìm kiếm
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchValue);
        url.searchParams.set('status', statusValue);
        
        // Fetch kết quả mới
        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTbody = doc.getElementById('show-user');
                document.getElementById('show-user').innerHTML = newTbody.innerHTML;
            });
    }

    // Thêm sự kiện cho input tìm kiếm
    if (searchInput) {
        searchInput.addEventListener("input", updateSearch);
    }

    // Thêm sự kiện cho select trạng thái
    if (statusFilter) {
        statusFilter.addEventListener("change", updateSearch);
    }
});

// Chỉ giữ lại code cho modal
document.addEventListener("DOMContentLoaded", () => {
    const modalClose = document.querySelector('.modal-close');
    const modal = document.querySelector('.modal');
    
    if (modalClose) {
        modalClose.addEventListener('click', () => {
            modal.classList.remove('open');
        });
    }
});

