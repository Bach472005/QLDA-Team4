<?php 

class CommentController
{
    public $commentModel;

    public function __construct(){
        $this->commentModel = new CommentModel();
    }

    public function get_comment() {
        $comments = $this->commentModel->get_comments();

        require_once './views/Comment/List.php';
    }
    public function delete_comment() {
        if(isset($_GET["comment_id"])){
            $comment_id = $_GET["comment_id"];
            $this->commentModel->delete_comment($comment_id);
            echo "<script> 
                        alert('Delete Success');
                        setTimeout(function(){
                            window.location.href = '?act=get_comment';
                        }, 1000); 
                      </script>";
        }
    }
}