<?php

class ClassUserQuery
{
    private $dbConnection;

    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function verifyCredentials($email, $password)
    {
        $table_name = "users";

        $query = "SELECT
                    email, password 
                FROM
                    " . $table_name . "
                WHERE
                    email = ?
                LIMIT
                    0,1";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(1, $email);
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

        $query = "SELECT
                    email, name  
                FROM
                    " . $table_name . "
                WHERE
                    email = ?
                LIMIT
                    0,1";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

}