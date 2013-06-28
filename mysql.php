<?php
/**
 * @author Artur Aralin
 * @name Smart PHPMySQLi
 * Version Modules
 * @construc-build 2
 * @query-build 4
*/
class spm{
     
     private static $mysqli;
     
     public function __construct($options = array()) {  
          $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_BASE);
          // var_dump($mysqli);
          if($mysqli->error == null){
               $mysqli->set_charset(CHARSET);
               self::$mysqli = $mysqli; 
               /*SET DEFINES*/   
               define('SPM_NAME','SMART PHPMySQLi');
               define('SPM_GET_ERRORS','get_error'); 
               
               if(!is_null($options['unset_defines']) && $options['unset_defines'] == true){
                    $arr = array('DB_HOST','DB_BASE','DB_USER','DB_PASSWORD');
                    for($i = 0;$i < count($arr);$i++){
                         define($arr[$i],NULL);
                    }
               }                              
          }else echo(SPM_NAME.'Ошибка соединения с MySQL');                      
     }
     
  
  
     //Метот запроса в БД
     public function query($query,$options = null){
          if($a = self::doStable($options) === false || self::CheckPattern($query,$options['guard_pattern']) === false || self::CheckQuery($query) == false){
              echo 'hello';
          }else{
                    $res = self::$mysqli->query($query); //Выполняем запрос
                    $mysqli = $res;
                    if(preg_match('/select/i',$query)){
                         //Записываем в нужный формат
                         switch($options){
                              case 'assoc':
                              $res = $res->fetch_assoc();
                              break;
                                                  
                              default:
                              $res = $res->fetch_array();
                         }    
                         return self::getErrors($options['get_errors'],$query,$mysqli,$res);
                    }else return true;            
               }            
     }
     
     private function CheckPattern($query,$pattern){
          if(!is_null($pattern)){
               if(preg_match($pattern,$query) == false)
                    return false;    
          }else
               return true; 
               
                
     }
     
     private function doStable($options){
           if(!is_null($options) && !is_array($options))
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
     
     private function getErrors($option,$query,$mysqli,$result){
          
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