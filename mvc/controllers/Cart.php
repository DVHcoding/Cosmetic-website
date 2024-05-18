<?php

class cart extends ControllerBase
{

    // Phương thức thêm sản phẩm vào giỏ hàng
    public function addItemcart($productId)
    {
        $product = $this->model("productModel");

        // Lưu vào cơ sở dữ liệu nếu người dùng đã đăng nhập
        if (isset($_SESSION['user_id'])) {
            $cart     = $this->model("cartModel");
            $cartUser = ($cart->getByUserId($_SESSION['user_id']));
            if ($cart->check($_SESSION['user_id'], $productId)) {
                $check = $product->checkQuantity($productId, $cartUser[$productId]['quantity']);

                if ($check) {
                    // Cập nhật số lượng sản phẩm trong giỏ hàng
                    if (!$cart->updateQuanity($_SESSION['user_id'], $cartUser, $productId)) {
                        echo 'lỗi';
                        die();
                    }
                } else {
                    // Thông báo nếu số lượng sản phẩm đã hết
                    echo '<script>alert("Số lượng sản phẩm đã hết!");window.history.back();</script>';
                }
            } else {
                // Lấy thông tin sản phẩm từ cơ sở dữ liệu
                $result = $product->getById($productId);
                // Fetch
                $p = $result->fetch_assoc();
                // Thêm sản phẩm vào giỏ hàng của người dùng
                if (!$cart->add($_SESSION['user_id'], $p)) {
                    echo 'lỗi';
                    die();
                }
            }
        }

        // Lưu vào SESSION nếu người dùng chưa đăng nhập
        if (isset($_SESSION['cart'][$productId])) {
            $check = $product->checkQuantity($productId, $_SESSION['cart'][$productId]['quantity']);
            if ($check) {
                $_SESSION['cart'][$productId]['quantity']++;
            } else {
                // Thông báo nếu số lượng sản phẩm đã hết
                echo '<script>alert("Số lượng sản phẩm đã hết!");window.history.back();</script>';
            }
        } else {
            // Lấy thông tin sản phẩm từ cơ sở dữ liệu
            $result = $product->getById($productId);
            // Fetch
            $p = $result->fetch_assoc();

            // Thêm sản phẩm vào SESSION
            $_SESSION['cart'][$p['id']] = array(
                "productId"    => $p['id'],
                "productName"  => $p['name'],
                "image"        => $p['image'],
                "quantity"     => 1,
                "productPrice" => $p['promotionPrice']
            );
        }
        // Quay lại trang trước 
        echo '<script>window.history.back();</script>';
    }

    // Phương thức tính tổng số lượng sản phẩm trong giỏ hàng
    public function getTotal()
    {
        $total = 0;
        foreach ($_SESSION['cart'] as $key => $value) {
            $total += $value['quantity'];
        }
        return $total;
    }

    // Phương thức cập nhật số lượng sản phẩm trong giỏ hàng
    public function updateItemcart($productId, $qty)
    {
        // Tạo đối tượng product từ productModel
        $product = $this->model("productModel");
        // Kiểm tra số lượng sản phẩm có đủ để cập nhật không
        $check = $product->checkQuantity($productId, $qty);

        if (isset($_SESSION['user_id'])) { // Kiểm tra người dùng đã đăng nhập chưa
            $cart = $this->model("cartModel");

            if ($check) { // Nếu số lượng sản phẩm đủ
                // Cập nhật số lượng sản phẩm trong giỏ hàng của người dùng
                if (!$cart->editQuanity($_SESSION['user_id'], $productId, $qty)) {
                    http_response_code(501); // Trả về mã lỗi 501 nếu cập nhật thất bại
                }

                // Cập nhật số lượng sản phẩm trong session giỏ hàng
                $_SESSION['cart'][$productId]['quantity'] = $qty;
                http_response_code(200); // Trả về mã thành công 200
            } else {
                http_response_code(501); // Trả về mã lỗi 501 nếu số lượng không đủ
            }
        } else {  // Nếu người dùng chưa đăng nhập
            if ($check) { // Nếu số lượng sản phẩm đủ
                // Cập nhật số lượng sản phẩm trong session giỏ hàng
                $_SESSION['cart'][$productId]['quantity'] = $qty;
                http_response_code(200); // Trả về mã thành công 200
            } else {
                http_response_code(501); // Trả về mã lỗi 501 nếu số lượng không đủ
            }
        }
    }

    public function removeItemcart($productId)
    {
        // Xóa sản phẩm khỏi session giỏ hàng
        unset($_SESSION['cart'][$productId]);

        if (isset($_SESSION['user_id'])) { // Kiểm tra người dùng đã đăng nhập chưa
            $cart = $this->model("cartModel"); // Tạo đối tượng cart từ cartModel

            if ($cart->remove($_SESSION['user_id'], $productId)) {
                // Nếu giỏ hàng trống sau khi xóa sản phẩm, hủy bỏ voucher nếu có
                if ($_SESSION['cart'] == null) {
                    $this->cancelVoucher();
                }
            } else {
                echo 'lỗi'; // Thông báo lỗi nếu xóa sản phẩm thất bại
                die(); // Dừng chương trình
            }
        }

        // Trở lại trang trước đó
        echo '<script>window.history.back();</script>';
    }


    public function getTotalPricecart()
    {
        $cart = $this->model("cartModel");  // Tạo đối tượng cart từ cartModel
        // Trả về tổng giá trị giỏ hàng của người dùng
        return ($cart->getTotalPrice($_SESSION['user_id']))->fetch_assoc();
    }

    public function getTotalQuantitycart()
    {
        // Kiểm tra người dùng đã đăng nhập chưa
        if (isset($_SESSION['user_id'])) {
            $cart = $this->model("cartModel"); // Tạo đối tượng cart từ cartModel
            // Trả về tổng số lượng sản phẩm trong giỏ hàng của người dùng
            return ($cart->getTotalQuantitycart($_SESSION['user_id']))->fetch_assoc();
        }
        return 0;
    }

    public function checkout()
    {
        if (isset($_SESSION['user_id'])) {
            $cart   = $this->model("cartModel");
            $result = ($cart->getByUserId($_SESSION['user_id']));
            if (count($result) > 0) {
                $_SESSION['cart'] = $result;
                $this->view("client/checkout", [
                    "headTitle" => "Đơn hàng của tôi",
                    'cart'      => $result
                ]);
            } else {
                $this->view("client/checkout", [
                    "headTitle" => "Đơn hàng của tôi",
                    'cart'      => isset($_SESSION['cart']) ? $_SESSION['cart'] : []
                ]);
            }
        } else {
            $this->view("client/checkout", [
                "headTitle" => "Đơn hàng của tôi",
                'cart'      => isset($_SESSION['cart']) ? $_SESSION['cart'] : []
            ]);
        }
    }

    public function check()
    {
        $voucher = $this->model("voucherModel");
        $result  = $voucher->check($_POST['code']);
        if ($result) {
            $_SESSION['voucher']['percentDiscount'] = $result['percentDiscount'];
            $_SESSION['voucher']['code']            = $result['code'];
        } else {
            echo '<script>alert("Mã giảm giá không đúng, đã sử dụng hoặc số lượng đã hết!");window.history.back();</script>';
            die();
        }
        $this->redirect("cart", "checkout");
    }

    public function voucher()
    {
        $voucher = $this->model("voucherModel");
        $result  = $voucher->used($_POST['code']);
        if ($result) {
            $_SESSION['voucher']['percentDiscount'] = $result['percentDiscount'];
            $_SESSION['voucher']['code']            = $result['code'];
        } else {
            echo '<script>alert("Mã giảm giá không đúng, đã sử dụng hoặc số lượng đã hết!");window.history.back();</script>';
            die();
        }
        $this->redirect("cart", "checkout");
    }

    public function cancelVoucher()
    {
        $voucher = $this->model("voucherModel");
        $voucher->cancel($_SESSION['voucher']['code']);
        unset($_SESSION['voucher']);
        $this->redirect("cart", "checkout");
    }

    public function deleteCart()
    {
        $cart = $this->model("cartModel");
        $cart->deleteCart();
    }
}
