<?php
class blog extends ControllerBase
{
    public function Index()
    {
        $blog = $this->model("blogModel");
        $blogList = $blog->getAll()->fetch_all(MYSQLI_ASSOC);
        $blogListPopular = $blog->getPopular()->fetch_all(MYSQLI_ASSOC);
        $this->view("client/blogList", [
            "headTitle" => "Blog",
            "blogList" => $blogList,
            "blogListPopular" => $blogListPopular
        ]);
    }

    public function detail($id)
    {
        $blog = $this->model("blogModel");
        //view
        $blog->view($id);
        
        $data = $blog->getById($id);

        $this->view("client/blogDetail", [
            "headTitle" => "Blog",
            "blog" => $data
        ]);
    }
}
