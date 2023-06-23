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
                    id, role_id, email, name, profile_photo 
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
    
    public function getUserInfoById($id)
    {
        $table_name = "users";
        $role_id    = 2;

        $query = "SELECT
                    id, role_id, email, name, profile_photo 
                FROM
                    " . $table_name . "
                WHERE
                    id = ?
                AND
                    role_id = ?
                LIMIT
                    0,1";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(1, $id);
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

    public function uploadThumbnail($file) 
    {
        $target_dir    = "uploads/thumbnails/";
        $file_name     = round(microtime(true)) . '-' . basename($file["name"]);
        $target_file   = $target_dir . $file_name;
        $uploadOk      = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check file size is not bigger than 2MB
        if ($file["size"] > 2097152) 
        {
            $uploadOk = 2;
        }
        elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") // Allow certain file formats
        {
            $uploadOk = 3;
        }
        else 
        {
            if (!move_uploaded_file($file["tmp_name"], $target_file)) 
            {
                $uploadOk = 4;              
            }
        }

        return $uploadOk;
    }

    public function getVideos($user_id)
    {
        $table_name = "videos";

        $query = "SELECT
                    id, user_id, title, description, video_link, thumbnail, category_id 
                FROM
                    " . $table_name . " 
                WHERE user_id = $user_id 
                ORDER BY id DESC 
                LIMIT
                    0,10";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($rows)
        {
            return $rows;
        }
        return [];
    }

    public function insertUser($name, $email, $password)
    {
        if ($name !== "" && $email !== "" && $password !== "")
        {
            $user_info = $this->getUserInfo($email);
            if ($user_info === false)
            {
                $table_name = "users";

                try 
                {
                    // Insert user into database
                    $query = "INSERT INTO
                    " . $table_name . "
                    SET 
                    role_id=:role_id, name=:name, email=:email, password=:password, created_at=:created_at";

                    $stmt = $this->dbConnection->prepare($query);

                    $role_id  = 2;
                    $name     = htmlspecialchars(strip_tags($name));
                    $email    = htmlspecialchars(strip_tags($email));
                    $password = password_hash($password, PASSWORD_DEFAULT);

                    // to get time-stamp for 'created' field
                    $timestamp = date('Y-m-d H:i:s');

                    // bind values
                    $stmt->bindParam(":role_id", $role_id);
                    $stmt->bindParam(":name", $name);
                    $stmt->bindParam(":email", $email);
                    $stmt->bindParam(":password", $password);
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
        }
        else 
        {
            return false;
        }
    }

    public function updateUser($data, $user_id)
    {
        $table_name = "users";

        try 
        {
            if ($data['pfile'] == '')
            {
                $query = "UPDATE
                    " . $table_name . "
                SET
                    name=:name,
                    email=:email 
                WHERE 
                    id=:id";
            }
            else 
            {
                $query = "UPDATE
                    " . $table_name . "
                SET
                    name=:name,
                    email=:email, 
                    profile_photo=:profile_photo 
                WHERE 
                    id=:id";
            }
            

            $stmt = $this->dbConnection->prepare($query);

            // Posted values
            $name  = htmlspecialchars(strip_tags($data['name']));
            $email = htmlspecialchars(strip_tags($data['email']));
            $pfile = htmlspecialchars(strip_tags($data['pfile']));

            // Bind parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            
            if ($data['pfile'] != '')
            {
                $stmt->bindParam(':profile_photo', $pfile);
            }                
            
            $stmt->bindParam(':id', $user_id);

            // Execute the query
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
}