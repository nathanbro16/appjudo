<?php
Class DB 
{
    private $connection;

    public function __construct(bool $DEBUG, string $Dir)
    {
        require_once $Dir.'conf.php';
        try {
		    $BD_Connect = new PDO('mysql:host='.$DB['HOST'].';dbname='.$DB['NAME'].';charset=utf8', $DB['LOGIN'], $DB['PASSWORD']);
		    $BD_Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $this->connection = $BD_Connect;
        }catch (PDOException $e){
            http_response_code(500);
            require $Dir.'error/503-DB.php';
            die();
        }catch (Exception $e){
            die('Erreur SQL : '.$e->getMessage());
        }
    }
    public function BD_Connetion(){
        return $this->connection;
    }
}
