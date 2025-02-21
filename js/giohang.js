    const btn = document.querySelectorAll(".mua");
    btn.forEach(function(mua, index) {
        mua.addEventListener("click", function(event) {
            var btnItem = event.target;
            var product = btnItem.parentElement;
            var productImg = product.querySelector("img").src;
            var productName = product.querySelector("h3").innerText;
            var productPrice = product.querySelector(".price").innerText;

            // Truyền tham số vào hàm addCart
            addCart(productImg, productName, productPrice);
        });
    });

    function addCart(productImg, productName, productPrice) {
        var cartTable = document.querySelector("tbody");
        var cartItems = cartTable.querySelectorAll("tr");

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        var isProductInCart = false;
        cartItems.forEach(function(item) {
            var itemName = item.querySelector("td span").innerText;
            if (itemName === productName) {
                // Nếu sản phẩm đã có, tăng số lượng
                var inputQuantity = item.querySelector("input");
                inputQuantity.value = parseInt(inputQuantity.value) + 1;
                isProductInCart = true;
            }
        });

        // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
        if (!isProductInCart) {
            var addtr = document.createElement("tr");
            var trcontent = `
                <td style="display: flex; align-items: center;">
                    <img style="width: 90px;" src="${productImg}" alt="">
                    <span>${productName}</span>
                </td>
                <td>
                    <p><span>${productPrice}</span><sup>đ</sup></p>
                </td> 
                <td>
                    <input style="width: 40px; outline: none;" type="number" value="1" min="1" >
                </td>
                <td style="cursor: pointer;" class="delete-item">Xóa</td>
            `;
            addtr.innerHTML = trcontent;
            cartTable.append(addtr);

            // Thêm sự kiện xóa sản phẩm
            addtr.querySelector(".delete-item").addEventListener("click", function() {
                addtr.remove();
                tongdonhang();
            });

            // Thêm sự kiện khi thay đổi số lượng
            addtr.querySelector("input").addEventListener("input", function() {
                tongdonhang();
            });
        }

        tongdonhang();
    }

    function tongdonhang() {
        var cartItems = document.querySelectorAll("tbody tr");
        var totalC = 0;

        cartItems.forEach(function(item) {
            var productPrice = item.querySelector("td p span").innerText;
            var inputValue = item.querySelector("input").value;

            productPrice = productPrice.replace(/[^0-9]/g, '');

            var totalA = parseFloat(productPrice) * parseFloat(inputValue);
            totalC += totalA;
        });

        var totalD = totalC.toLocaleString('de-DE');

        var cartTotal = document.querySelector(".price-total span");
        if (cartTotal) {
            cartTotal.innerHTML = totalD + ' đ';
        }
    }
    const cartbtn = document.querySelector(".dong")
    const cartshow= document.querySelector(".ravao")
    cartshow.addEventListener("click",function(){
        console.log(cartshow)
        document.querySelector(".cart").style.right="0"
    })
    cartbtn.addEventListener("click",function(){
        console.log(cartshow)
        document.querySelector(".cart").style.right="-100%"
    })
    function searchProducts() {
        var searchValue = document.getElementById("search").value.toLowerCase();
        console.log("Tìm kiếm: ", searchValue); // Kiểm tra giá trị tìm kiếm
        var productItems = document.querySelectorAll("tbody tr");
    
        productItems.forEach(function(item) {
            var productName = item.querySelector("td span").innerText.toLowerCase();
            console.log("Tên sản phẩm: ", productName); // Kiểm tra tên sản phẩm
    
            if (productName.includes(searchValue)) {
                item.style.display = ""; // Hiển thị nếu khớp
            } else {
                item.style.display = "none"; // Ẩn nếu không khớp
            }
        });
    }
    
    
