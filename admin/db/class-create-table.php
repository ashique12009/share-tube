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
            `user_id` int(11) NOT NULL,
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
}