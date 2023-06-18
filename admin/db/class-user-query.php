<?php

class ClassUserQuery
{
    private $dbConnection;

    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function verifyUserCredentials($email, $password)
    {
        $table_name = "users";
        $role_id = 2;

        $query = "SELECT
                    email, password 
                FROM
                    " . $table_name . "
                WHERE
                    email = ? 
                AND 
                    role_id = ?
                LIMIT
                    0,1";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->bindParam(2, $role_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row)
        {
            if (password_verify($password, $row['password']))
            {
                return true;
            }
            return false;
        }
        return false;        
    }

    public function getUserInfo($email)
    {
        $table_name = "users";
        $role_id = 2;

        $query = "SELECT
                    email, name  
                FROM
                    " . $table_name . "
                WHERE
                    email = ?
                AND 
                    role_id = ?
                LIMIT
                    0,1";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->bindParam(2, $role_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

}