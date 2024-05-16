<?php

class ratingManage extends ControllerBase
{
    public function index($page = 1)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }

        // khởi tạo model
        $rating = $this->model("ratingModel");
        // Gọi hàm addAllAdmin
        $ratingList = ($rating->getAllAdmin(isset($_GET['page']) ? $_GET['page'] : 1))->fetch_all(MYSQLI_ASSOC);
        $countPaging = $rating->getCountPaging(8);
        $this->view("admin/rating", [
            "headTitle" => "Phản hồi đánh giá",
            "ratingList" => $ratingList,
            "countPaging" => $countPaging
        ]);
    }

    public function reply($id)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') {
            $this->redirect("home");
        }
        $rating = $this->model("ratingModel");

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $resultreply = $rating->reply($_POST['reply'], $_POST['id']);
            $result = $rating->getById($_POST['id']);
            if ($resultreply) {
                $this->view("admin/reply", [
                    "headTitle" => "Phản hồi",
                    "cssClass" => "success",
                    "message" => "Thành công!",
                    "rating" => $result
                ]);
            } else {
                $this->view("admin/reply", [
                    "headTitle" => "Phản hồi",
                    "cssClass" => "error",
                    "message" => "Lỗi, vui lòng thử lại sau!",
                    "rating" => $result
                ]);
            }
        } else {
            $result = $rating->getById($id);
            $this->view("admin/reply", [
                "headTitle" => "Phản hồi",
                "cssClass" => "none",
                "rating" => $result
            ]);
        }
    }

    public function delete($id)
    {
        $rating = $this->model("ratingModel");
        $result = $rating->delete($id);
        if ($result) {
            $this->redirect("ratingManage");
        }
    }
}
