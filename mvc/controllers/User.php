<?php

class user extends ControllerBase
{
    // login controller
    public function login()
    {
        // Nếu người dùng bấm nút đăng nhập (nghĩa là có request trong form)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy thông tin từ 2 ô input là email và password
            $email    = $_POST['email'];
            $password = $_POST['password'];

            // Tạo đối tượng user từ userModel
            $user = $this->model("userModel");
            // Gọi hàm checkLogin từ userModel
            $result = $user->checkLogin($email, $password);


            if ($result) {
                // Get user
                $u = $result->fetch_assoc();
                // Set session
                $_SESSION['user_id']   = $u['id'];
                $_SESSION['user_name'] = $u['fullName'];
                $_SESSION['role']      = $u['RoleName'];
                // cart

                $cart     = $this->model("cartModel");
                $listCart = ($cart->getByUserId($_SESSION['user_id']));

                if (count($listCart) > 0) {
                    $_SESSION['cart'] = $listCart;
                }

                if ($u['RoleName'] == "Admin") {
                    $this->redirect("admin");
                } else {
                    $this->redirect("home");
                }
            } else {
                $this->view("client/login", [
                    "headTitle" => "Đăng nhập",
                    "message"   => "Tài khoản hoặc mật khẩu không đúng hoặc tk đã bị khóa!"
                ]);
            }
        } else {
            $this->view("client/login", [
                "headTitle" => "Đăng nhập"
            ]);
        }
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['cart']);
        $this->redirect("user", "login");
    }

    /* -------------------------------------------------------------------------- */
    /*                      CONTROLLER SỬ DỤNG ĐỂ ĐĂNG KÝ                         */
    /* -------------------------------------------------------------------------- */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            /**
             * Sử dụng method POST
             */

            // # Data lấy từ các ô input nhập trong form ở view register.php
            $fullName   = $_POST['fullName'];
            $email      = $_POST['email'];
            $dob        = $_POST['dob'];
            $address    = $_POST['address'];
            $password   = $_POST['password'];
            $phone      = $_POST['phone'];
            $provinceId = $_POST['ls_province'];
            $districtId = $_POST['ls_district'];
            $wardId     = $_POST['ls_ward'];
            // ##############################################################


            /**
             * $user = $this->model("userModel");: Đây là cách để tạo một đối tượng thuộc lớp userModel. 
             * Trong mô hình MVC, phần "Model" là nơi chứa logic liên quan đến dữ liệu. 
             * Bằng cách gọi phương thức model() từ đối tượng $this, bạn có thể truy cập đến các phương thức và 
             * thuộc tính của lớp userModel.
             * 
             * $checkEmail = $user->checkEmail($email);: Ở đây, bạn gọi phương thức checkEmail() của đối tượng $user, 
             * với tham số truyền vào là địa chỉ email ($email). Phương thức này có nhiệm vụ kiểm tra xem email đã tồn 
             * tại trong cơ sở dữ liệu chưa.
             */

            $user       = $this->model("userModel");
            $checkEmail = $user->checkEmail($email);

            if (!$checkEmail) {
                // Kiểm tra email đã tồn tại trong cơ sở dữ liệu chưa
                $checkPhone = $user->checkPhone($phone);

                if (!$checkPhone) {
                    // Nếu cả email và số điện thoại đều đã tồn tại, hiển thị thông báo lỗi cho người dùng
                    $this->view("client/register", [
                        "headTitle"    => "Đăng ký",
                        "messageEmail" => "Email đã tồn tại",
                        "messagePhone" => "Số điện thoại đã tồn tại",
                    ]);
                } else {
                    // Nếu chỉ email đã tồn tại, hiển thị thông báo lỗi cho người dùng
                    $this->view("client/register", [
                        "headTitle"    => "Đăng ký",
                        "messageEmail" => "Email đã tồn tại",
                    ]);
                }
                return;

            } else {
                // Nếu email chưa tồn tại, kiểm tra số điện thoại
                $checkPhone = $user->checkPhone($phone);

                // Nếu số điện thoại đã tồn tại, hiển thị thông báo lỗi cho người dùng
                if (!$checkPhone) {
                    $this->view("client/register", [
                        "headTitle"    => "Đăng ký",
                        "messagePhone" => "Số điện thoại đã tồn tại",
                    ]);
                    return;
                }
            }

            // Chèn thông tin người dùng mới vào cơ sở dữ liệu
            $result = $user->insert($fullName, $email, $dob, $address, $password, $phone, $provinceId, $districtId, $wardId);
            if ($result) {
                // Nếu chèn thành công, chuyển hướng người dùng đến trang xác nhận
                $this->redirect("user", "login");
            } else {
                $this->view("client/register", [
                    "headTitle" => "Đăng ký",
                    "cssClass"  => "error",
                    "message"   => "Đăng ký thất bại",
                ]);
            }
        } else {
            // Nếu không phải là phương thức POST, hiển thị trang đăng ký
            $this->view("client/register", [
                "headTitle" => "Đăng ký",
            ]);
        }
    }


    public function info($message = "")
    {
        // Khởi tạo model
        $user   = $this->model('userModel');
        $result = $user->getById($_SESSION['user_id']);
        $u      = $result->fetch_assoc();
        $this->view('client/info', [
            "headTitle" => "Thông tin tài khoản",
            "user"      => $u,
            "message"   => $message
        ]);
    }

    public function edit()
    {
        // Khởi tạo model
        $user = $this->model('userModel');
        // Lấy thông tin người dùng dựa trên ID người dùng trong session
        $result = $user->getById($_SESSION['user_id']);
        // Chuyển kết quả truy vấn thành mảng kết hợp
        $u = $result->fetch_assoc();

        // Kiểm tra xem yêu cầu HTTP là POST hay không
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Kiểm tra xem số điện thoại mới đã tồn tại trong cơ sở dữ liệu hay chưa
            $checkPhone = $user->checkPhoneUpdate($_POST['phone']);

            // Nếu số điện thoại đã tồn tại
            if (!$checkPhone) {
                // Hiển thị lại trang chỉnh sửa với thông báo lỗi
                $this->view("client/edit", [
                    "headTitle"    => "Chỉnh sửa thông tin tài khoản",
                    "messagePhone" => "Số điện thoại đã tồn tại",
                    "user"         => $u
                ]);
            } else {
                // Cập nhật thông tin người dùng trong cơ sở dữ liệu
                $r = $user->update($_POST);

                if ($r) {
                    $_SESSION['user_name'] = $_POST['fullName'];

                    $this->redirect("user", "info", [
                        "message" => "Cập nhật thành công!"
                    ]);
                } else {
                    $this->redirect("user", "info", [
                        "message" => "Lỗi!"
                    ]);
                }
            }
        } else {
            $this->view('client/edit', [
                "headTitle" => "Chỉnh sửa thông tin tài khoản",
                "user"      => $u
            ]);
        }
    }

    public function resetPassword()
    {
        // Kiểm tra nếu phương thức request là POS
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Khởi tạo model user để làm việc với database
            $user = $this->model('userModel');
            // Kiểm tra mật khẩu hiện tại của người dùng
            $result = $user->checkCurrentPassword($_SESSION['user_id'], $_POST['password']);
            if ($result) {
                // Nếu mật khẩu hiện tại đúng
                // Cập nhật mật khẩu mới cho người dùng
                $r = $user->updatePassword($_SESSION['user_id'], $_POST['newPassword']);
                if ($r) {
                    // Nếu cập nhật mật khẩu thành công, chuyển hướng đến trang thông tin người dùng
                    $this->redirect("user", "info", [
                        "message" => "Đổi mật khẩu thành công!"
                    ]);
                } else {
                    // Nếu cập nhật mật khẩu không thành công, chuyển hướng đến trang thông tin người dùng với thông báo lỗi 
                    $this->redirect("user", "info", [
                        "message" => "Lỗi!"
                    ]);
                }
            } else {
                $this->view('client/resetPassword', [
                    "headTitle"       => "Đổi mật khẩu",
                    "messagePassword" => "Mật khẩu hiện tại không đúng!"
                ]);
            }
        } else {
            // Nếu không phải là phương thức POST, hiển thị form đổi mật khẩu
            $this->view('client/resetPassword', [
                "headTitle" => "Đổi mật khẩu"
            ]);
        }
    }

    public function delete()
    {
        // Kiểm tra phương thức yêu cầu có phải là POST hay không
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Khởi tạo model
            $user   = $this->model('userModel');
            $result = $user->checkCurrentPassword($_SESSION['user_id'], $_POST['password']);
            if ($result) {
                $r = $user->delete($_SESSION['user_id']);
                if ($r) {
                    $this->redirect("user", "logout");
                } else {
                    $this->view('client/delete', [
                        "headTitle"       => "Xóa tài khoản",
                        "messagePassword" => "Lỗi!"
                    ]);
                }
            } else {
                $this->view('client/delete', [
                    "headTitle"       => "Xóa tài khoản",
                    "messagePassword" => "Mật khẩu hiện tại không đúng!"
                ]);
            }
        } else {
            $this->view('client/delete', [
                "headTitle" => "Đổi mật khẩu"
            ]);
        }
    }
}
