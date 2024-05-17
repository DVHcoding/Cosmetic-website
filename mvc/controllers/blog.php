<?php
class blog extends ControllerBase
{
    // Hàm Index: Lấy danh sách các bài viết và bài viết phổ biến
    public function Index()
    {
        // Khởi tạo model blog
        $blog = $this->model("blogModel");
        // Lấy danh sách tất cả các bài viết
        $blogList = $blog->getAll()->fetch_all(MYSQLI_ASSOC);
        // Lấy danh sách các bài viết phổ biến
        $blogListPopular = $blog->getPopular()->fetch_all(MYSQLI_ASSOC);
        // Chuyển hướng đến view và truyền dữ liệu vào
        $this->view("client/blogList", [
            "headTitle"       => "Blog",
            "blogList"        => $blogList,
            "blogListPopular" => $blogListPopular
        ]);
    }

    // Hàm detail: Hiển thị chi tiết một bài viết dựa trên id
    public function detail($id)
    {
        // Khởi tạo model blog
        $blog = $this->model("blogModel");
        // Ghi nhận lượt xem cho bài viết
        $blog->view($id);
        // Lấy thông tin chi tiết của bài viết
        $data = $blog->getById($id);
        // Chuyển hướng đến view và truyền dữ liệu vào
        $this->view("client/blogDetail", [
            "headTitle" => "Blog",
            "blog"      => $data
        ]);
    }
}
