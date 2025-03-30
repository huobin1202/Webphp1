document.addEventListener("DOMContentLoaded", () => {
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

function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

window.onclick = function(event) {
    const modal = document.querySelector('.modal.detail-modal');
    if (event.target == modal) {
        window.location.href = '?' + new URLSearchParams(
            Object.fromEntries(
                Object.entries(Object.fromEntries(new URLSearchParams(window.location.search)))
                .filter(([key]) => key !== 'detail')
            )
        ).toString();
    }
}

