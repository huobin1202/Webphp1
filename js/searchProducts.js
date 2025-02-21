let debounceTimeout;
        const products = [
        { id: 1, name: 'Kawasaki NINJA® 650', price: '210,000,000 vnđ', url: 'ninja-650.php' },            
        { id: 2, name: 'Kawasaki NINJA® 1000SX', price: '330,000,000 vnđ' },
            { id: 3, name: 'Kawasaki NINJA® ZX™-4R', price: '150,000,000 vnđ' },
            { id: 4, name: 'Kawasaki NINJA H2®R', price: '1,400,000,000 vnđ' },
            { id: 5, name: 'Kawasaki NINJA® e-1', price: '490,000,000 vnđ' },
            { id: 6, name: 'Kawasaki ZH2', price: '210,000,000 vnđ' },
            { id: 7, name: 'Kawasaki Z125', price: '90,000,000 vnđ' },
            { id: 8, name: 'Kawasaki Z500', price: '120,000,000 vnđ' },
            { id: 9, name: 'Kawasaki Z650RS', price: '150,000,000 vnđ' },
            { id: 10, name: 'Kawasaki Z7 HYBRID', price: '670,000,000 vnđ' },
            { id: 11, name: 'Kawasaki KLX110R', price: '50,000,000 vnđ' },
            { id: 12, name: 'Kawasaki KLX230', price: '30,000,000 vnđ' },
            { id: 13, name: 'Kawasaki KLX230R', price: '65,000,000 vnđ' },
            { id: 14, name: 'Kawasaki KLX230SM', price: '80,000,000 vnđ' },
            { id: 15, name: 'Kawasaki KLX300', price: '73,000,000 vnđ' },
            { id: 16, name: 'Kawasaki KLX300SM', price: '85,000,000 vnđ' }
        ];

        function debounceSearchProducts() {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(function() {
                searchProducts();
            }, 0);
        }
        // Hàm tìm kiếm sản phẩm
        function searchProducts() {
            let searchQuery = document.getElementById('searchBox').value.toLowerCase();
            const searchResultsDiv = document.getElementById('searchResults');

            // Nếu ô tìm kiếm trống, ẩn kết quả tìm kiếm
            if (searchQuery === '') {
                searchResultsDiv.style.display = 'none';
                return;
            }

            // Lọc các sản phẩm phù hợp
            let filteredProducts = products.filter(product => 
                product.name.toLowerCase().includes(searchQuery)
            );

            displaySearchResults(filteredProducts);
        }

        // Hàm hiển thị kết quả tìm kiếm
        function displaySearchResults(filteredProducts) {
            const searchResultsDiv = document.getElementById('searchResults');
            searchResultsDiv.innerHTML = ''; // Xóa kết quả cũ trước khi hiển thị kết quả mới

            if (filteredProducts.length > 0) {
                filteredProducts.forEach(product => {
                    const productElement = document.createElement('div');
                    productElement.classList.add('product-item');
                    productElement.innerHTML = `
                       <a href="${product.url}">
                    <h4>${product.name}</h4>
                    <span>${product.price}</span>
                </a>
                    `;
                    searchResultsDiv.appendChild(productElement);
                });

                // Hiển thị kết quả khi có sản phẩm tìm thấy
                searchResultsDiv.style.display = 'block';
            } else {
                // Hiển thị thông báo nếu không có sản phẩm nào
                searchResultsDiv.innerHTML = '<p>Không tìm thấy sản phẩm nào.</p>';
                searchResultsDiv.style.display = 'block';
            }
        }