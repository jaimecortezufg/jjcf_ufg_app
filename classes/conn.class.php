<?php
require("config.php");
class DB extends PDO {
	
    public $id = 0;
    public $n = 0;
    //public function __construct($dsn=NULL, $username = NULL, $password = NULL, $options = []) {
	public function __construct(){
		$configuracion = new configuracion();
		
        $default_options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        //$options = array_replace($default_options, $options);
		try{
			parent::__construct($configuracion->dsn, $configuracion->user, $configuracion->pass, $default_options);
		}catch(PDOException $e){
			throw new Exception($e->getMessage());
		}
    }

    public function run($sql, $args = NULL) {
        if (!$args) {
            return $this->query($sql);
        }
        $stmt = $this->prepare($sql);
        try {
            $this->beginTransaction();
            $stmt->execute($args);
            $this->id = intval($this->lastInsertId());
            $this->n = intval($stmt->rowCount());
            $this->commit();
            $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(\PDOException $e) {
            $this->rollback();
            echo($e->getMessage());
        }
        return $stmt;
    }
}

?>