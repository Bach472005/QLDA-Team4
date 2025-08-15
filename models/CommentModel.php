<?php 

class CommentModel extends Connect
{
    public function get_comments($product_id){
        $sql = "SELECT
                    comments.id,
                    comments.user_id,
                    comments.comment, 
                    comments.rating, 
                    comments.date, 
                    users.name AS user_name
                FROM comments
                JOIN products ON comments.product_id = products.id
                JOIN users ON comments.user_id = users.id
                WHERE comments.product_id = :product_id
                ORDER BY comments.date DESC";
        $data = $this->conn->prepare($sql);
        $data->bindParam(":product_id", $product_id);
        $data->execute();
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }
    public function post_comment($product_id, $user_id, $rating, $comment){
        $sql = "INSERT INTO comments
                    (product_id, user_id, rating, comment)
                    values (:product_id, :user_id, :rating, :comment)";
        $data = $this->conn->prepare($sql);
        $data->bindParam(":product_id", $product_id, PDO::PARAM_INT);
        $data->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $data->bindParam(":rating", $rating, PDO::PARAM_INT);
        $data->bindParam(":comment", $comment, PDO::PARAM_STR);
        $data->execute();
    }
    public function delete_comment($comment_id){
        $sql = "DELETE from comments where id = :id";
        $data = $this->conn->prepare($sql);
        $data->bindParam(":id", $comment_id);
        $data->execute();
    }
}