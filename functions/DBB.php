<?php
Class DB 
{
    private $connection;

    public function __construct(bool $DEBUG)
    {
        try {
		    $BD_Connect = new PDO('mysql:host='.$DB_HOST.';dbname='.$DB_NAME.';charset=utf8', $DB_LOGIN, $DB_PASSWORD);
		    $BD_Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $BD_Connect = $this->connection;
        }catch (PDOException $e){
            http_response_code(500);
            require 'error/503-DB.php';
            die();
        }catch (Exception $e){
            die('Erreur SQL : '.$e->getMessage());
        }
    }
}
