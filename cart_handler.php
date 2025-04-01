<?php
session_start();
include('database.php');

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_SESSION['customer_id'];
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO giohang (customer_id, product_id, soluong, price, img) VALUES (?, ?, 1, ?, ?)");
        $stmt->bind_param("iiss", $customer_id, $product_id, $_POST['price'], $_POST['img']);
        $success = $stmt->execute();
        echo json_encode(['success' => $success]);
    } 
    else if ($action === 'remove') {
        $stmt = $conn->prepare("DELETE FROM giohang WHERE customer_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $customer_id, $product_id);
        $success = $stmt->execute();
        echo json_encode(['success' => $success]);
    }
    exit;
}
?> 

<script>
function updateQuantity(btn, change) {
    const input = btn.parentElement.querySelector('.quantity-input');
    let value = parseInt(input.value) + change;
    value = Math.max(1, Math.min(10, value));
    input.value = value;
}

function prepareUpdate(btn) {
    const form = btn.closest('form');
    const quantityInput = form.previousElementSibling.querySelector('.quantity-input');
    const hiddenInput = form.querySelector('.quantity-hidden');
    hiddenInput.value = quantityInput.value;
}

// Thêm thông báo khi có message
<?php if(isset($_SESSION['success'])): ?>
    alert("<?php echo $_SESSION['success']; ?>");
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    alert("<?php echo $_SESSION['error']; ?>");
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
</script> 

<style>
    .quantity-controls {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
    }

    .quantity-btn {
        padding: 8px 12px;
        border: none;
        background: #f8f8f8;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .quantity-btn:hover {
        background: #e9ecef;
    }

    .quantity-input {
        width: 50px;
        text-align: center;
        border: none;
        padding: 8px 0;
        -moz-appearance: textfield;
        background: white;
    }

    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .update-btn, .delete-btn {
        padding: 8px 15px;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .update-btn {
        background-color: #139b3a;
    }

    .update-btn:hover {
        background-color: #0f7c2e;
    }

    .delete-btn {
        background-color: #dc3545;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }
</style>