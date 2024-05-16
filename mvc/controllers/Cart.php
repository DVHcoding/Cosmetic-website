<?php

class cart extends ControllerBase
{
    public function addItemcart($productId)
    {
        $product = $this->model("productModel");

        // Save in DB
        if (isset($_SESSION['user_id'])) {
            $cart = $this->model("cartModel");
            $cartUser = ($cart->getByUserId($_SESSION['user_id']));
            if ($cart->check($_SESSION['user_id'], $productId)) {
                $check = $product->checkQuantity($productId, $cartUser[$productId]['quantity']);
                if ($check) {
                    if (!$cart->updateQuanity($_SESSION['user_id'], $cartUser, $productId)) {
                        echo 'lỗi';
                        die();
                    }
                } else {
                    echo '<script>alert("Số lượng sản phẩm đã hết!");window.history.back();</script>';
                }
            } else {
                $result = $product->getById($productId);
                // Fetch
                $p = $result->fetch_assoc();
                if (!$cart->add($_SESSION['user_id'], $p)) {
                    echo 'lỗi';
                    die();
                }
            }
        }

        // Save in SESSION
        if (isset($_SESSION['cart'][$productId])) {
            $check = $product->checkQuantity($productId, $_SESSION['cart'][$productId]['quantity']);
            if ($check) {
                $_SESSION['cart'][$productId]['quantity']++;
            } else {
                echo '<script>alert("Số lượng sản phẩm đã hết!");window.history.back();</script>';
            }
        } else {
            $result = $product->getById($productId);
            // Fetch
            $p = $result->fetch_assoc();

            $_SESSION['cart'][$p['id']] = array(
                "productId" => $p['id'],
                "productName" => $p['name'],
                "image" => $p['image'],
                "quantity" => 1,
                "productPrice" => $p['promotionPrice']
            );
        }
        echo '<script>window.history.back();</script>';
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($_SESSION['cart'] as $key => $value) {
            $total += $value['quantity'];
        }
        return $total;
    }

    public function updateItemcart($productId, $qty)
    {
        $product = $this->model("productModel");
        $check = $product->checkQuantity($productId, $qty);
        if (isset($_SESSION['user_id'])) {
            $cart = $this->model("cartModel");
            if ($check) {
                if (!$cart->editQuanity($_SESSION['user_id'], $productId, $qty)) {
                    http_response_code(501);
                }
                $_SESSION['cart'][$productId]['quantity'] = $qty;
                http_response_code(200);
            } else {
                http_response_code(501);
            }
        } else {
            if ($check) {
                $_SESSION['cart'][$productId]['quantity'] = $qty;
                http_response_code(200);
            } else {
                http_response_code(501);
            }
        }
    }

    public function removeItemcart($productId)
    {
        unset($_SESSION['cart'][$productId]);
        if (isset($_SESSION['user_id'])) {
            $cart = $this->model("cartModel");
            if ($cart->remove($_SESSION['user_id'], $productId)) {
                if ($_SESSION['cart'] == null) {
                    $this->cancelVoucher();
                }
            } else {
                echo 'lỗi';
                die();
            }
        }
        echo '<script>window.history.back();</script>';
    }

    public function getTotalPricecart()
    {
        $cart = $this->model("cartModel");
        return ($cart->getTotalPrice($_SESSION['user_id']))->fetch_assoc();
    }

    public function getTotalQuantitycart()
    {
        if (isset($_SESSION['user_id'])) {
            $cart = $this->model("cartModel");
            return ($cart->getTotalQuantitycart($_SESSION['user_id']))->fetch_assoc();
        }
        return 0;
    }

    public function checkout()
    {
        if (isset($_SESSION['user_id'])) {
            $cart = $this->model("cartModel");
            $result = ($cart->getByUserId($_SESSION['user_id']));
            if (count($result) > 0) {
                $_SESSION['cart'] = $result;
                $this->view("client/checkout", [
                    "headTitle" => "Đơn hàng của tôi",
                    'cart' => $result
                ]);
            } else {
                $this->view("client/checkout", [
                    "headTitle" => "Đơn hàng của tôi",
                    'cart' => isset($_SESSION['cart']) ? $_SESSION['cart'] : []
                ]);
            }
        } else {
            $this->view("client/checkout", [
                "headTitle" => "Đơn hàng của tôi",
                'cart' => isset($_SESSION['cart']) ? $_SESSION['cart'] : []
            ]);
        }
    }

    public function check()
    {
        $voucher = $this->model("voucherModel");
        $result = $voucher->check($_POST['code']);
        if ($result) {
            $_SESSION['voucher']['percentDiscount'] = $result['percentDiscount'];
            $_SESSION['voucher']['code'] = $result['code'];
        } else {
            echo '<script>alert("Mã giảm giá không đúng, đã sử dụng hoặc số lượng đã hết!");window.history.back();</script>';
            die();
        }
        $this->redirect("cart", "checkout");
    }

    public function voucher()
    {
        $voucher = $this->model("voucherModel");
        $result = $voucher->used($_POST['code']);
        if ($result) {
            $_SESSION['voucher']['percentDiscount'] = $result['percentDiscount'];
            $_SESSION['voucher']['code'] = $result['code'];
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
