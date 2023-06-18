<?php

class ClassCreateTable
{
    private $dbConnection;

    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function createDatabaseTables()
    {
        $sqlUserRoleTable = "CREATE TABLE IF NOT EXISTS `user_roles` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(32) NOT NULL,
            `created_at` datetime NOT NULL,
            `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        if ($this->dbConnection->query($sqlUserRoleTable) === FALSE)
        {
            echo "Error creating table ";
        }

        $sqlUserRolePermissionTable = "CREATE TABLE IF NOT EXISTS `role_permissions` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(32) NOT NULL,
            `created_at` datetime NOT NULL,
            `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        if ($this->dbConnection->query($sqlUserRolePermissionTable) === FALSE)
        {
            echo "Error creating table ";
        }

        $sqlUserTable = "CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `role_id` int(11) NOT NULL,
            `name` varchar(32) NOT NULL,
            `email` varchar(128) NOT NULL,
            `password` varchar(255) NOT NULL,
            `created_at` datetime NOT NULL,
            `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`role_id`) REFERENCES user_roles(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        if ($this->dbConnection->query($sqlUserTable) === FALSE)
        {
            echo "Error creating table ";
        }

        $sqlUserRoleTable = "CREATE TABLE IF NOT EXISTS `user_role_permissions` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `role_id` int(11) NOT NULL,
            `permission_id` int(11) NOT NULL,
            `created_at` datetime NOT NULL,
            `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

        if ($this->dbConnection->query($sqlUserRoleTable) === FALSE)
        {
            echo "Error creating table ";
        }

        $sqlCategoryTable = "CREATE TABLE IF NOT EXISTS `categories` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(256) NOT NULL,
            `created_at` datetime NOT NULL,
            `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

        if ($this->dbConnection->query($sqlCategoryTable) === FALSE)
        {
            echo "Error creating table ";
        }

        $sqlVideoTable = "CREATE TABLE IF NOT EXISTS `videos` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `title` varchar(255) NOT NULL,
            `description` text NULL,
            `video_link` text NULL,
            `category_id` int(11) NOT NULL,
            `created_at` datetime NOT NULL,
            `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`category_id`) REFERENCES categories(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        if ($this->dbConnection->query($sqlVideoTable) === FALSE)
        {
            echo "Error creating table ";
        }
    }

    public function seedAdmin()
    {
        $table_name = "users";

        // Check admin user is exists or not, if exists then do not insert again.
        if (!$this->isAdminExists())
        {
            // Insert roles into database
            $this->insertRoles();

            // Insert admin user into database
            $query = "INSERT INTO
            " . $table_name . "
            SET
            role_id=:role_id, name=:name, email=:email, password=:password, created_at=:created_at";

            $stmt = $this->dbConnection->prepare($query);

            $role_id  = 1;
            $name     = htmlspecialchars(strip_tags('Admin'));
            $email    = htmlspecialchars(strip_tags('admin@test.com'));
            $password = password_hash('admin123', PASSWORD_DEFAULT);

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
    }

    public function seedUser()
    {
        $table_name = "users";

        // Check admin user is exists or not, if exists then do not insert again.
        if (!$this->isUserExists())
        {
            // Insert roles into database
            $this->insertRoles();

            // Insert admin user into database
            $query = "INSERT INTO
            " . $table_name . "
            SET
            role_id=:role_id, name=:name, email=:email, password=:password, created_at=:created_at";

            $stmt = $this->dbConnection->prepare($query);

            $role_id  = 2;
            $name     = htmlspecialchars(strip_tags('User'));
            $email    = htmlspecialchars(strip_tags('user@test.com'));
            $password = password_hash('user123', PASSWORD_DEFAULT);

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
    }

    private function insertRoles()
    {
        $table_name = "user_roles";

        $timestamp = date('Y-m-d H:i:s');

        $data = [
            [
                'name'       => 'admin',
                'created_at' => $timestamp,
            ],
            [
                'name'       => 'user',
                'created_at' => $timestamp,
            ],
        ];

        $sql = "INSERT INTO $table_name(name, created_at) VALUES(:name, :created_at)";

        $stmt = $this->dbConnection->prepare($sql);

        foreach ($data as $row)
        {
            $stmt->execute($row);
        }
    }

    private function isAdminExists()
    {
        $table_name = "users";
        $email      = "admin@test.com";

        $query = "SELECT
                    email
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
            return true;
        }
        return false;
    }

    private function isUserExists()
    {
        $table_name = "users";
        $email      = "user@test.com";

        $query = "SELECT
                    email
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
            return true;
        }
        return false;
    }
}