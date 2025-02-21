function displayCart() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const cartItemsContainer = document.getElementById('cartItems');
            const totalAmount = document.getElementById('totalAmount');
            let total = 0;

            cartItemsContainer.innerHTML = '';

            cart.forEach((item, index) => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><img src="${item.image}" style="width: 50px;"> ${item.name}</td>
                    <td>${item.price.toLocaleString()} đ</td>
                    <td>${item.quantity}</td>
                    <td>${itemTotal.toLocaleString()} đ</td>
                    <td><button class="btn-delete" onclick="deleteItem(${index})">Xóa</button></td>
                `;
                cartItemsContainer.appendChild(row);
            });

            totalAmount.innerText = total.toLocaleString() + ' đ';
        }

        // Hàm xóa sản phẩm khỏi giỏ hàng
        function deleteItem(index) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));

            displayCart();
        }

        // Hàm hiển thị/ẩn tóm tắt hóa đơn
        function toggleInvoice() {
            const invoiceDetails = document.getElementById('invoiceDetails');
            if (invoiceDetails.style.display === "none") {
                invoiceDetails.style.display = "block";
                displayCart();
            } else {
                invoiceDetails.style.display = "none";
            }
        }

        window.onload = displayCart;
        cartItemsContainer.innerHTML = '';

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <img src="${item.image}" class="product-image" alt="${item.name}"> ${item.name}
                    </td>
                    <td>${item.price} đ</td>
                    <td>${item.quantity}</td>
                    <td>${itemTotal} đ</td>
                `;
                cartItemsContainer.appendChild(row);
            });

            totalAmount.innerText = total.toLocaleString() + ' đ';
        