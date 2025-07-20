<?php
class Connect
{
    public $conn;
    public function __construct()
    {
        $this->conn = connect_db();
    }
    public function __destruct()
    {
        $this->conn = null;
    }
    public function get_table($table_name, $conditions = [], $columns = "*", $limit = null)
    {
        $sql = "SELECT $columns FROM $table_name";
        $params = [];

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $whereClauses = [];

            foreach ($conditions as $column => $value) {
                $whereClauses[] = "$column = :$column";
                $params[":$column"] = $value;
            }

            $sql .= implode(" AND ", $whereClauses);
        }

        if ($limit) {
            $sql .= " LIMIT :limit";
        }

        $data = $this->conn->prepare($sql);

        foreach ($params as $param => $value) {
            $data->bindValue($param, $value);
        }

        if ($limit) {
            $data->bindValue(":limit", $limit, PDO::PARAM_INT);
        }

        $data->execute();
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }

}
class UserModel extends Connect
{
    public $conn;

    public function get_user_email($email)
    {
        $sql = "SELECT * FROM users where email = :email";
        $data = $this->conn->prepare($sql);
        $data->bindParam(":email", $email);
        $data->execute();
        return $data->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($userId, $newHashedPassword)
    {
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'password' => $newHashedPassword,
            'id' => $userId
        ]);
    }

}