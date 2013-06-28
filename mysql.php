<?php
/**
 * @author Artur Aralin
 * @name Smart PHPMySQLi
 * Version Modules
 * @construc-build 3
 * @query-build 4
*/
class spm{
     
     private static $mysqli;
     
     private $host;
     
     private $user;
     
     private $password;
     
     private $base;
     
     private $charset = 'utf8';
     
     
     public function __construct($options = array('host'=>'localhost','user'=>'root','password'=>'','base'=>'testbase')){  
               $this->host = $options['host'];
               $this->user = $options['user'];
               $this->password = $options['password'];
               $this->base = $options['base'];
               if(!is_null($options['charset']))
                    $this->charset == $options['charset'];
                    /*Устанавливаем константы*/   
               define('SPM_NAME','SMART PHPMySQLi');
               define('SPM_GET_ERRORS','get_error');                                              
     } 
          
     public function connect(){
          self::$mysqli = self::MySQLiConnect();
     }  
     
     public function disconnect(){
          self::$mysqli = null;
     }                  
     
     private function MySQLiConnect(){
          if(is_null(self::$mysqli)){ //Если ранее одключение не создавалась
               $mysqli = new mysqli($this->host,$this->user,$this->password,$this->base);//Создаем объект с соединением
               if($mysqli->error == null){
                    $mysqli->set_charset($this->charset); //Устанавливаем кодировку
                    return $mysqli;     
               }else return false;
          }else return self::$mysqli; //Иначе возвращаем переменную с подкючением
                  
     }
  
  
     //Метот запроса в БД
     public function query($query,$options = null){
          if($a = self::doStable($query,$options) === false || self::CheckPattern($query,$options['guard_pattern']) === false || self::CheckQuery($query) == false){
              return false;
          }else{
                    $res = self::$mysqli->query($query); //Выполняем запрос
                    $mysqli = $res;
                         //Записываем в нужный формат
                         return self::CheckErrors($options['get_errors'],$query,$mysqli,$res);           
               }            
     }
     
     private function CheckPattern($query,$pattern){
          if(!is_null($pattern)){
               if(preg_match($pattern,$query) == false)
                    return false;    
          }else
               return true; 
               
                
     }
     
     private function doStable($query,$options){
           if((!is_null($options) && !is_array($options)) or !is_string($query))
               return false;
           else
               return true;        
     }
     
     private function CheckQuery($query){
               $pattern = '/(select|alert|delete|insert|create|update|drop)/i'; //Регулярное выражение для проверки входящего запроса
               
               if(preg_match($pattern,$query))
                    return true;
               else
                    return false;         
     }
     
     private function CheckErrors($option,$query,$mysqli,$result){
          
          if($option == SPM_GET_ERRORS){
               return array(
                    'result' => $mysqli,
                    'query' => $query,
                    'errors' => $mysqli->error);
          }else
              return $result;            
     }


     public function createTable($arr){
       array(
               
               'name'=>'',
               
               'columns'=>array('int(11)'),
               
               'engine'=>'InnoDB',
               
               'charset'=>'utf8'
          );
          
          for($i = 0;$i < count($arr['columns']);$i++){
               $columns = '`'.$arr.'`';
          }
          
          self::query('CREATE TABLE IF NOT EXISTS `'. $arr['name'] .'`');
     /*
          CREATE TABLE IF NOT EXISTS `1` (
  `3` int(11) NOT NULL,
  `2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */    
     }

}
?>