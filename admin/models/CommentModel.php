<?php 

class CommentModel extends Connect
{
    public function get_comments(){
        $sql = "SELECT
                comments.id,
                comments.comment, 
                comments.rating, 
                comments.date, 
                products.name AS product_name, 
                users.name AS user_name 
                FROM comments
                JOIN products ON comments.product_id = products.id
                JOIN users ON comments.user_id = users.id
                ORDER BY comments.date DESC";
        $data = $this->conn->prepare($sql);
        $data->execute();
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }
    public function delete_comment($id){
        $sql = "DELETE from comments where id = :id";
        $data = $this->conn->prepare($sql);
        $data->bindParam(":id", $id, PDO::PARAM_INT);
        $data->execute();
    }
    
}