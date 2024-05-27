<?php

class questionManage extends ControllerBase
{
    public function index($page = 1)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // khởi tạo model
        $question = $this->model("questionModel");
        // Gọi hàm addAllAdmin
        $questionList = ($question->getAllAdmin((isset($_GET['page']) ? $_GET['page'] : 1)))->fetch_all(MYSQLI_ASSOC);
        $countPaging  = $question->getCountPaging(8);
        $this->view("admin/question", [
            "headTitle"    => "Phản hồi đánh giá",
            "questionList" => $questionList,
            "countPaging"  => $countPaging
        ]);
    }

    public function reply($id)
    {
        // Kiểm tra nếu người dùng đã đăng nhập và không phải là Quản trị viên
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            // Chuyển hướng đến trang chủ nếu người dùng không phải là Quản trị viên
            $this->redirect("home");
        }
        // Tải model câu hỏi
        $question = $this->model("questionModel");

        // Kiểm tra nếu phương thức yêu cầu là POST (gửi biểu mẫu)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Cố gắng lưu phản hồi vào cơ sở dữ liệu
            $resultreply = $question->reply($_POST['reply'], $_POST['id']);
            // Lấy câu hỏi được cập nhật theo ID
            $result = $question->getById($_POST['id']);

            // Kiểm tra nếu phản hồi được lưu thành công
            if ($resultreply) {
                // Tải view với thông báo thành công và câu hỏi đã cập nhật
                $this->view("admin/replyQuestion", [
                    "headTitle" => "Phản hồi",
                    "cssClass"  => "success",
                    "message"   => "Thành công!",
                    "rating"    => $result
                ]);
            } else {
                // Tải view với thông báo lỗi
                $this->view("admin/replyQuestion", [
                    "headTitle" => "Phản hồi",
                    "cssClass"  => "error",
                    "message"   => "Lỗi, vui lòng thử lại sau!",
                    "rating"    => $result
                ]);
            }
        } else {
            $result = $question->getById($id);
            $this->view("admin/replyQuestion", [
                "headTitle" => "Phản hồi",
                "cssClass"  => "none",
                "rating"    => $result
            ]);
        }
    }

    public function delete($id)
    {
        $question = $this->model("questionModel");
        $result   = $question->delete($id);
        if ($result) {
            $this->redirect("questionManage");
        }
    }
}
