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
        $countPaging = $question->getCountPaging(8);
        $this->view("admin/question", [
            "headTitle" => "Phản hồi đánh giá",
            "questionList" => $questionList,
            "countPaging" => $countPaging
        ]);
    }

    public function reply($id)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }
        $question = $this->model("questionModel");

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $resultreply = $question->reply($_POST['reply'], $_POST['id']);
            $result = $question->getById($_POST['id']);
            if ($resultreply) {
                $this->view("admin/replyQuestion", [
                    "headTitle" => "Phản hồi",
                    "cssClass" => "success",
                    "message" => "Thành công!",
                    "rating" => $result
                ]);
            } else {
                $this->view("admin/replyQuestion", [
                    "headTitle" => "Phản hồi",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "rating" => $result
                ]);
            }
        } else {
            $result = $question->getById($id);
            $this->view("admin/replyQuestion", [
                "headTitle" => "Phản hồi",
                "cssClass" => "none",
                "rating" => $result
            ]);
        }
    }

    public function delete($id)
    {
        $question = $this->model("questionModel");
        $result = $question->delete($id);
        if ($result) {
            $this->redirect("questionManage");
        }
    }
}
