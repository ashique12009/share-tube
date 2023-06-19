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
        $role_id    = 2;

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
        $role_id    = 2;

        $query = "SELECT
                    id, email, name
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

    public function insertVideo($data)
    {
        $table_name = "videos";

        try {
            // Insert admin user into database
            $query = "INSERT INTO
            " . $table_name . "
            SET
            user_id=:user_id, title=:title, description=:description, thumbnail=:thumbnail, video_link=:video_link, category_id=:category_id, created_at=:created_at";

            $stmt = $this->dbConnection->prepare($query);

            $title   = htmlspecialchars(strip_tags($data['title']));
            $user_id = $data['user_id'];
            $desc    = htmlspecialchars(strip_tags($data['desc']));
            $cat_id  = $data['cat_id'];
            $thumb   = htmlspecialchars(strip_tags($data['thumb']));
            $vfile   = htmlspecialchars(strip_tags($data['vfile']));

            // to get time-stamp for 'created' field
            $timestamp = date('Y-m-d H:i:s');

            // bind values
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":description", $desc);
            $stmt->bindParam(":thumbnail", $thumb);
            $stmt->bindParam(":video_link", $vfile);
            $stmt->bindParam(":category_id", $cat_id);
            $stmt->bindParam(":created_at", $timestamp);

            if ($stmt->execute())
            {
                return true;
            }
            return false;
        } 
        catch (Exception $e) 
        {
            return false;
        }        
    }

    public function getCategories()
    {
        $table_name = "categories";

        $query = "SELECT
                    id, name
                FROM
                    " . $table_name . "
                LIMIT
                    0,10";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute();

        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($row)
        {
            return $row;
        }
        return [];
    }

}