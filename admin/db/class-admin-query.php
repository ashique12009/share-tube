<?php

class ClassAdminQuery
{
    private $dbConnection;

    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function verifyAdminCredentials($email, $password)
    {
        $table_name = "users";
        $role_id    = 1;

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

    public function getAdminInfo($email)
    {
        $table_name = "users";
        $role_id    = 1;

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

    public function insertCategory($category_name)
    {
        if (!$this->isCategoryExists($category_name))
        {
            $table_name = "categories";

            // Insert admin user into database
            $query = "INSERT INTO
            " . $table_name . "
            SET
            name=:name, created_at=:created_at";

            $stmt = $this->dbConnection->prepare($query);

            $name = htmlspecialchars(strip_tags($category_name));

            // to get time-stamp for 'created' field
            $timestamp = date('Y-m-d H:i:s');

            // bind values
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":created_at", $timestamp);

            if ($stmt->execute())
            {
                return true;
            }
            return false;
        }
    }

    public function isCategoryExists($category_name)
    {
        $table_name = "categories";

        $query = "SELECT
                    name
                FROM
                    " . $table_name . "
                WHERE
                    name = ?
                LIMIT
                    0,1";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(1, $category_name);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row)
        {
            return true;
        }
        return false;
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

    public function deleteCategory($id)
    {
        $table_name = "categories";

        // Check if category exists in videos table
        if ($this->isCategoryExistsInVideo($id)) 
        {
            return false;
        }
        else
        {
            $query = "DELETE FROM " . $table_name . " WHERE id = ?";

            $stmt = $this->dbConnection->prepare($query);
            $stmt->bindParam(1, $id);

            if ($stmt->execute())
            {
                return true;
            }
            return false;
        }
    }

    private function isCategoryExistsInVideo($category_id)
    {
        $table_name = "videos";

        $query = "SELECT
                    name
                FROM
                    " . $table_name . "
                WHERE
                    category_id = ?
                LIMIT
                    0,1";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(1, $category_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row)
        {
            return true;
        }
        return false;
    }

    public function getCategoryName($cat_id)
    {
        $table_name = "categories";

        $query = "SELECT
                    id, name
                FROM
                    " . $table_name . "
                WHERE 
                    id = ?
                LIMIT
                    0,10";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(1, $cat_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row)
        {
            return $row;
        }
        return '';
    }

    function updateCategory($cat_id, $cat_name)
    {
        $table_name = "categories";
        try {
            $query = "UPDATE $table_name SET name=:name WHERE id=:cat_id LIMIT 1";
            $statement = $this->dbConnection->prepare($query);
            $statement->bindParam(':name', $cat_name);
            $statement->bindParam(':cat_id', $cat_id, PDO::PARAM_INT);
            $query_execute = $statement->execute();
    
            if ($query_execute)
            {
                return true;
            }
            return false;  
        } 
        catch(PDOException $e) 
        {
            echo $e->getMessage();
        }
    }

    public function getVideos()
    {
        $table_name = "videos";
        $user_table_name = "users";

        $query = "SELECT
                    videos.id, user_id, title, description, video_link, thumbnail, category_id, profile_photo 
                FROM
                    " . $table_name . " 
                INNER JOIN " . $user_table_name . " 
                ON " . $table_name . ".user_id = " . $user_table_name . ".id 
                WHERE 
                    status=1 
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

    public function getVideoByVlink($vfile)
    {
        $table_name = "videos";
        $cat_table_name = "categories";

        $query = "SELECT
                    videos.id, user_id, title, description, video_link, thumbnail, category_id, name 
                FROM
                    " . $table_name . " 
                INNER JOIN " . $cat_table_name . " 
                ON " . $table_name . ".category_id = " . $cat_table_name . ".id 
                WHERE 
                    status=1 
                    AND 
                    video_link='" . $vfile . "' 
                ORDER BY id DESC 
                LIMIT
                    0,1";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute();

        $rows = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rows)
        {
            return $rows;
        }
        return [];
    }

    public function getRecentCategories()
    {
        $table_name = "categories";

        $query = "SELECT
                    id, name
                FROM
                    " . $table_name . "
                ORDER BY id DESC 
                LIMIT
                    0,3";

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($rows)
        {
            return $rows;
        }
        return [];
    }

    public function getCategoryVideos($cat_id)
    {
        $table_name = "videos";
        $user_table_name = "users";
        $category_table_name = "categories";

        $query = "SELECT
                    videos.id, user_id, title, description, video_link, thumbnail, category_id, profile_photo 
                FROM
                    " . $table_name . " 
                INNER JOIN " . $user_table_name . " 
                ON " . $table_name . ".user_id = " . $user_table_name . ".id 
                INNER JOIN " . $category_table_name . " 
                ON " . $table_name . ".category_id = " . $category_table_name . ".id 
                WHERE 
                    status=1 
                AND 
                    ".$table_name.".category_id = " . $cat_id . "
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

    public function getSearchedVideos($search_keyword)
    {
        $table_name = "videos";
        $user_table_name = "users";
        $category_table_name = "categories";

        $query = "SELECT
                    videos.id, user_id, title, description, video_link, thumbnail, category_id, profile_photo 
                FROM
                    " . $table_name . " 
                INNER JOIN " . $user_table_name . " 
                ON " . $table_name . ".user_id = " . $user_table_name . ".id 
                INNER JOIN " . $category_table_name . " 
                ON " . $table_name . ".category_id = " . $category_table_name . ".id 
                WHERE 
                    status=1 
                AND 
                    ".$table_name.".title LIKE '%".$search_keyword."%' 
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
}