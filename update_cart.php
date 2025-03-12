<?php
session_start();
include("database.php");

if (!isset($_SESSION["customer_id"])) {
    die("Bạn cần đăng nhập để chỉnh sửa giỏ hàng!");
}

$customer_id = $_SESSION["customer_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["cart_id"])) {
        $cart_id = (int)$_POST["cart_id"];

        // Xóa sản phẩm khỏi giỏ hàng
        if (isset($_POST["delete"])) {
            $stmt = $conn->prepare("DELETE FROM giohang WHERE id = ? AND customer_id = ?");
            $stmt->bind_param("ii", $cart_id, $customer_id);
            $stmt->execute();
            $stmt->close();
        }

        // Cập nhật số lượng sản phẩm
        if (isset($_POST["quantity"])) {
            $quantity = (int)$_POST["quantity"];
            if ($quantity < 1) $quantity = 1;

            $stmt = $conn->prepare("UPDATE giohang SET soluong = ? WHERE id = ? AND customer_id = ?");
            $stmt->bind_param("iii", $quantity, $cart_id, $customer_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Tính lại tổng tiền sau khi cập nhật giỏ hàng
$sql = "SELECT SUM(soluong * price) AS total FROM giohang WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$stmt->bind_result($total);
$stmt->fetch();
$stmt->close();

// Lấy danh sách sản phẩm sau khi cập nhật giỏ hàng
$sql = "SELECT g.id, g.product_id, g.soluong, g.price, g.img, p.tensp 
        FROM giohang g
        JOIN products p ON g.product_id = p.id
        WHERE g.customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_html = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subtotal = $row["soluong"] * $row["price"];
        $cart_html .= '
<tr>
    <td style="display: flex; align-items: center;">
        <img style="width: 90px;" src="sanpham/' . $row["img"] . '" alt="' . htmlspecialchars($row["tensp"]) . '">
    </td>
    <td><span>' . htmlspecialchars($row["tensp"]) . '</span></td>
    <td>
        <p><span>' . number_format($row["price"], 0, ',', '.') . '</span><sup>đ</sup></p>
    </td>
    <td>
        <input style="width: 40px; outline: none;" type="number" value="' . $row["soluong"] . '" min="1" class="cart-quantity" data-cart-id="' . $row["id"] . '">
    </td>
    <td style="cursor: pointer;" class="delete-item" data-cart-id="' . $row["id"] . '">Xóa</td>
</tr>';
    }
} else {
    $cart_html = "<tr><td colspan='5'>Giỏ hàng của bạn đang trống!</td></tr>";
}

$stmt->close();
$conn->close();

// Trả về JSON chứa cả giỏ hàng và tổng tiền mới
echo json_encode([
    "cart_html" => $cart_html,
    "total_price" => number_format($total, 0, ',', '.')
]);
