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

// Sản phẩm
function filterProducts() {
    // Lấy giá trị từ ô tìm kiếm và danh mục lọc
    const searchInput = document.getElementById('form-search-product').value.toLowerCase().trim();
    const selectedCategory = document.getElementById('the-loai').value;

    // Lấy danh sách sản phẩm
    const productItems = document.querySelectorAll('.list');

    productItems.forEach(product => {
        // Lấy thông tin sản phẩm: tên xe và danh mục
        const productName = product.querySelector('.list-info h4')?.innerText.toLowerCase().trim();
        const productCategory = product.querySelector('.list-category')?.innerText.trim();

        // Kiểm tra điều kiện tìm kiếm và lọc
        const matchesSearch = !searchInput || productName.includes(searchInput);
        const matchesCategory = selectedCategory === 'Tất cả' || productCategory === selectedCategory;

        // Hiển thị sản phẩm nếu cả hai điều kiện đều đúng
        product.style.display = (matchesSearch && matchesCategory) ? 'flex' : 'none';
    });
}

// Đặt lại tìm kiếm và lọc
function resetProductFilter() {
    document.getElementById('form-search-product').value = ''; // Xóa nội dung tìm kiếm
    document.getElementById('the-loai').value = 'Tất cả'; // Đặt danh mục về 'Tất cả'
    filterProducts(); // Hiển thị lại tất cả sản phẩm
}

// Gắn sự kiện cho các phần tử liên quan
document.addEventListener('DOMContentLoaded', () => {
    // Tìm kiếm khi nhập vào ô tìm kiếm
    const searchInput = document.getElementById('form-search-product');
    if (searchInput) {
        searchInput.addEventListener('input', filterProducts);
    }

    // Lọc khi thay đổi danh mục
    const categoryFilter = document.getElementById('the-loai');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterProducts);
    }

    // Đặt lại khi nhấn nút reset
    const resetButton = document.querySelector('.btn-control-large');
    if (resetButton) {
        resetButton.addEventListener('click', resetProductFilter);
    }
});

// Đơn hàng
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("form-search-order");
    const statusFilter = document.getElementById("tinh-trang");
    const resetButton = document.querySelector(".btn-reset-order");
    const tableRows = document.querySelectorAll("#showOrder tr");
    
    // Hàm lọc bảng
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedStatus = statusFilter.value;

        tableRows.forEach(row => {
            const cells = row.querySelectorAll("td");
            const orderID = cells[0].innerText.toLowerCase().trim(); // Mã đơn hàng
            const customerID = cells[1].innerText.toLowerCase().trim(); // Mã khách hàng
            const customerName = cells[2].innerText.toLowerCase().trim(); // Tên khách hàng
            const statusText = cells[5].innerText.toLowerCase().trim(); // Trạng thái

            // Kiểm tra trạng thái
            const matchesStatus = selectedStatus === "2" || // Tất cả
                (selectedStatus === "1" && statusText.includes("đã xử lý")) ||
                (selectedStatus === "0" && statusText.includes("chưa xử lý"));

            // Kiểm tra thuật ngữ tìm kiếm
            const matchesSearch = orderID.includes(searchTerm) ||
                customerID.includes(searchTerm) ||
                customerName.includes(searchTerm);

            // Hiển thị/Ẩn dòng dựa trên điều kiện phù hợp
            if (matchesStatus && matchesSearch) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    // Hàm đặt lại bảng
    function resetTable() {
        searchInput.value = "";
        statusFilter.value = "2";
        tableRows.forEach(row => {
            row.style.display = "";
        });
    }

    // Gắn sự kiện
    searchInput.addEventListener("input", filterTable);
    statusFilter.addEventListener("change", filterTable);
    resetButton.addEventListener("click", (e) => {
        e.preventDefault(); // Ngăn hành vi mặc định của nút
        resetTable();
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
document.addEventListener("DOMContentLoaded", function () {
    // Lấy các phần tử DOM
    const searchInput = document.getElementById("form-search-user");
    const statusFilter = document.getElementById("tinh-trang-user");
    const tableBody = document.getElementById("show-user");
    const resetButton = document.querySelector(".btn-reset-order");

    // Dữ liệu gốc
    const rows = Array.from(tableBody.querySelectorAll("tr"));

    // Hàm chuẩn hóa chuỗi (loại bỏ khoảng trắng thừa và chuyển thành chữ thường)
    function normalizeString(str) {
        return str.trim().toLowerCase().replace(/\s+/g, ""); // Loại bỏ khoảng trắng và chuẩn hóa chữ
    }

    // Hàm lọc các hàng dựa trên đầu vào tìm kiếm và trạng thái
    function filterTable() {
        const searchValue = normalizeString(searchInput.value); // Chuẩn hóa đầu vào người dùng
        const selectedStatus = statusFilter.value;

        rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            if (cells.length === 0) return; // Bỏ qua các dòng không có dữ liệu

            const maKh = normalizeString(cells[0].textContent || ""); // Chuẩn hóa mã khách hàng
            const hoVaTen = normalizeString(cells[1].textContent || ""); // Chuẩn hóa tên khách hàng
            const status = cells[4]?.querySelector("span").classList.contains("status-no-complete") ? "0" : "1";

            // Kiểm tra nếu dòng phù hợp với tìm kiếm và trạng thái
            const matchesSearch = maKh.includes(searchValue) || hoVaTen.includes(searchValue);
            const matchesStatus = selectedStatus === "2" || selectedStatus === status;

            // Hiển thị hoặc ẩn dòng dựa trên điều kiện
            if (matchesSearch && matchesStatus) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    // Đặt lại tìm kiếm và lọc
    function resetFilters() {
        searchInput.value = ""; // Xóa ô tìm kiếm
        statusFilter.value = "2"; // Đặt trạng thái lọc về "Tất cả"
        rows.forEach(row => (row.style.display = "")); // Hiển thị tất cả các dòng
    }

    // Gắn sự kiện
    searchInput.addEventListener("input", filterTable); // Khi nhập vào ô tìm kiếm
    statusFilter.addEventListener("change", filterTable); // Khi thay đổi trạng thái lọc
    resetButton.addEventListener("click", resetFilters); // Khi nhấn nút đặt lại
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

