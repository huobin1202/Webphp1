// Hàm để thêm sản phẩm vào giỏ hàng
function addToCart(name, price, image) {
    // Lấy giỏ hàng từ localStorage (nếu chưa có thì khởi tạo mảng trống)
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
    let existingProduct = cart.find(product => product.name === name);

    if (existingProduct) {
        // Nếu sản phẩm đã có, tăng số lượng lên 1
        existingProduct.quantity += 1;
    } else {
        // Nếu sản phẩm chưa có, thêm sản phẩm mới
        let newProduct = {
            name: name,
            price: price,
            image: image,
            quantity: 1
        };
        cart.push(newProduct);
    }

    // Lưu lại giỏ hàng vào localStorage
    localStorage.setItem('cart', JSON.stringify(cart));

    // Thông báo cho người dùng rằng sản phẩm đã được thêm
}

// Lấy danh sách sản phẩm ban đầu
const products = Array.from(document.querySelectorAll('.card')); // Tất cả sản phẩm
const productList = document.querySelector('#product-list'); // Danh sách hiển thị sản phẩm

// Hàm tìm kiếm và lọc sản phẩm
function searchProducts(sortOrder = 0) {
    // Lấy giá trị tìm kiếm từ các trường
    const category = document.querySelector('#advanced-search-category-select').value.toLowerCase(); // Loại
    const minPrice = parseFloat(document.querySelector('#min-price').value) || 0; // Giá tối thiểu
    const maxPrice = parseFloat(document.querySelector('#max-price').value) || Infinity; // Giá tối đa

    // Lọc sản phẩm theo tên và giá
    const filteredProducts = products.filter(product => {
        const productName = product.querySelector('h3').textContent.toLowerCase(); // Lấy tên sản phẩm
        const productPriceText = product.querySelector('.price').textContent.replace(/\D+/g, ''); // Lấy giá (chuỗi)
        const productPrice = parseFloat(productPriceText); // Chuyển giá sang số

        // Điều kiện lọc
        const matchesCategory = category === 'tất cả' || productName.includes(category);
        const matchesPrice = productPrice >= minPrice && productPrice <= maxPrice;

        return matchesCategory && matchesPrice;
    });

    // Sắp xếp sản phẩm nếu cần
    if (sortOrder === 1) {
        filteredProducts.sort((a, b) => {
            const priceA = parseFloat(a.querySelector('.price').textContent.replace(/\D+/g, ''));
            const priceB = parseFloat(b.querySelector('.price').textContent.replace(/\D+/g, ''));
            return priceA - priceB; // Giá tăng dần
        });
    } else if (sortOrder === 2) {
        filteredProducts.sort((a, b) => {
            const priceA = parseFloat(a.querySelector('.price').textContent.replace(/\D+/g, ''));
            const priceB = parseFloat(b.querySelector('.price').textContent.replace(/\D+/g, ''));
            return priceB - priceA; // Giá giảm dần
        });
    }

    // Hiển thị các sản phẩm đã lọc
    renderProducts(filteredProducts);
}

