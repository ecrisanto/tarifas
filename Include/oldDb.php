<?php
/*class tarifa extends mysqli{
    private static $instance =null;
    
    private $user = "root";
    private $pass ="";
    private $dbName = "tarifasmym";
    private $dbHost = "localHost";
    
    public static function getInstance(){
        if(!self::$instance instanceof self){
            self::$instance = new self;                    
        }
        return self::$instance;
    }
    
    public function __clone() {
        trigger_error('Clone is not allowed',E_USER_ERROR);
    }
    
    public function __wakeup() {
        trigger_error('Desrializing is not allowd', E_USER_ERROR);
    }
    
    private function __construct() {
        parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
        
        if(mysqli_connect_error()){
            exit('Connect Error('.mysqli_connect_errno().')'.mysqli_connect_error());
        }
        parent::set_charset('utf-8');            
    }
    
    function get_aduana_from_trafico($trafico){
        $trafico = $this->real_escape_string($trafico);
        $aduana = $this->query("select aduana from trafico_facturar where trafico = '".$trafico."'");
        
        if($aduana->num_rows >0)
        {
            $row=$aduana->fetch_row();
            return $row[0];        
        }else {
            return null;    
        }                
    }
}*/
