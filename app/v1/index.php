<?php
 
    require 'vendor/autoload.php';
    require 'Connection.php';
    
    use PostgreSQLTutorial\Connection as Connection;
    
    try {
        Connection::get()->connect();
        echo 'A connection to the PostgreSQL database sever has been established successfully.';
    } catch (\PDOException $e) {
        echo $e->getMessage();
    }

?>