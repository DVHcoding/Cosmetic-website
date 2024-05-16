<?php
class userManage extends ControllerBase
{
    public function index($page = 1)
    {
        // # Kiểm tra tài khoản nếu không phải admin thì đẩy về trang chủ
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }
        // Khởi tạo đối tượng user từ userModel
        $user = $this->model("userModel");


        // Trường hợp nếu admin nhập thông tin vào ô tìm kiếm 
        if (isset($_GET['keyword'])) {
            // # gọi hàm searchUser từ userModel và chuyền cho nó 1 argument là từ cần tìm
            $result    = $user->searchuser($_GET["keyword"]);
            $usersList = [];

            // # Chỉ hiển thị 8 cái cho mỗi page
            $countPaging = $user->getCountPaging(8);
            // # Nếu tìm thành công thì cho đống data vào mảng $userList
            if ($result) {
                $usersList = $result->fetch_all(MYSQLI_ASSOC);
            }

            // # Dùng data đó đẩy ra view
            $this->view("admin/users", [
                "headTitle"   => "Quản lý người dùng",
                "usersList"   => $usersList,
                'countPaging' => $countPaging
            ]);
        } else {
            // Trường hợp không tìm gì thì hiện ra tất cả. Tuy nhiên vẫn chỉ hiện 8 cái mỗi page
            // Nếu trên thanh url có ?page=... thì lấy cái trang đó
            if (isset($page['page'])) {
                $usersList = ($user->getAllUsersForPage($page['page']))->fetch_all(MYSQLI_ASSOC);
            } else {
                // Nếu k có thì mặc định là page đầu tiên
                $usersList = ($user->getAllUsersForPage('1'))->fetch_all(MYSQLI_ASSOC);
            }

            $countPaging = $user->getCountPaging(8);

            $this->view("admin/users", [
                "headTitle"   => "Quản lý người dùng",
                "usersList"   => $usersList,
                'countPaging' => $countPaging
            ]);
        }
    }

    // Controller thay đổi trạng thái (Ví dụ: khóa hoặc mở)
    public function changeStatus($id)
    {
        $user   = $this->model("userModel");
        $result = $user->changeStatus($id);
        if ($result) {
            $this->redirect("userManage");
        }
    }
}


?>