<?php 
class ClassDBConfig
{
    private $host          = "localhost";
    private $database_name = "php_video_sharing_app";
    private $username      = "root";
    private $password      = "";
    public $connection;

    public function getConnection()
    {
        $this->connection = null;

        try {
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
        }
        catch (PDOException $exception)
        {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->connection;
    }
}