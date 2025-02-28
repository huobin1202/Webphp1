document.addEventListener("DOMContentLoaded", loadCart);

const currentUser = localStorage.getItem("currentUser") || "guest";

const btn = document.querySelectorAll(".mua");
btn.forEach(function (mua) {
    mua.addEventListener("click", function (event) {
        event.preventDefault();
        event.stopPropagation();

        var product = event.target.closest(".card");
        var productImg = product.querySelector("img").src;
        var productName = product.querySelector("h3").innerText;
        var productPrice = product.querySelector(".price").innerText.replace(/[^0-9]/g, '');

        addCart(productImg, productName, parseInt(productPrice));
    });
});

function addCart(productImg, productName, productPrice) {
    var cartTable = document.querySelector("tbody");
    var cartArray = JSON.parse(localStorage.getItem(`cart_${currentUser}`)) || [];
    var isProductInCart = false;

    cartArray.forEach(cartItem => {
        if (cartItem.name === productName) {
            cartItem.quantity++;
            isProductInCart = true;
        }
    });

    if (!isProductInCart) {
        cartArray.push({ img: productImg, name: productName, price: productPrice, quantity: 1 });
    }

    localStorage.setItem(`cart_${currentUser}`, JSON.stringify(cartArray));
    loadCart();
}

function loadCart() {
    var cartTable = document.querySelector("tbody");
    cartTable.innerHTML = "";
    var cartArray = JSON.parse(localStorage.getItem(`cart_${currentUser}`)) || [];

    cartArray.forEach(item => {
        var addtr = document.createElement("tr");
        addtr.innerHTML = `
            <td style="display: flex; align-items: center;">
                <img style="width: 90px;" src="${item.img}" alt="">
                <span>${item.name}</span>
            </td>
            <td>
                <p><span>${item.price}</span><sup>đ</sup></p>
            </td> 
            <td>
                <input style="width: 40px; outline: none;" type="number" value="${item.quantity}" min="1" >
            </td>
            <td style="cursor: pointer;" class="delete-item">Xóa</td>
        `;
        cartTable.append(addtr);

        addtr.querySelector(".delete-item").addEventListener("click", function () {
            removeFromCart(item.name);
        });
        addtr.querySelector("input").addEventListener("input", function () {
            updateCartQuantity(item.name, this.value);
        });
    });
    tongdonhang();
}

function updateCartQuantity(productName, newQuantity) {
    var cartArray = JSON.parse(localStorage.getItem(`cart_${currentUser}`)) || [];
    cartArray.forEach(item => {
        if (item.name === productName) {
            item.quantity = parseInt(newQuantity);
        }
    });
    localStorage.setItem(`cart_${currentUser}`, JSON.stringify(cartArray));
    tongdonhang();
}

function removeFromCart(productName) {
    var cartArray = JSON.parse(localStorage.getItem(`cart_${currentUser}`)) || [];
    cartArray = cartArray.filter(item => item.name !== productName);
    localStorage.setItem(`cart_${currentUser}`, JSON.stringify(cartArray));
    loadCart();
}

function tongdonhang() {
    var totalC = 0;
    var cartArray = JSON.parse(localStorage.getItem(`cart_${currentUser}`)) || [];

    cartArray.forEach(item => {
        totalC += parseFloat(item.price) * parseFloat(item.quantity);
    });

    var totalD = totalC.toLocaleString('de-DE');
    var cartTotal = document.querySelector(".price-total span");
    if (cartTotal) {
        cartTotal.innerHTML = totalD + ' đ';
    }
}

const cartbtn = document.querySelector(".dong");
const cartshow = document.querySelector(".ravao");
cartshow.addEventListener("click", function () {
    document.querySelector(".cart").style.right = "0";
});
cartbtn.addEventListener("click", function () {
    document.querySelector(".cart").style.right = "-100%";
});
