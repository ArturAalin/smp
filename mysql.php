<?php
/**
 * @author Artur Aralin
 * @name Smart PHPMySQLi
 * Version Modules
 * @construc-build 2
 * @query-build 3
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
               define('SPM_NAME','SMART PHPMySQLi ');   
               
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
          /*Проверяем для стабильности*/
                  //Защитный паттерн
          if(!is_null($options['guard_pattern'])){
               if(preg_match($options['guard_pattern'],$query) == false){
                    echo(SPM_NAME.'Запрос не прошел защитный паттерн или паттерн задан не верно');
               }
          } 
         
          if(!is_null($options) && !is_array($options)) echo(SPM_NAME.'Опции в запросе дожны сообщаться массивом');
          
          $pattern = '/(select|alert|delete|insert|create|update|drop)/i'; //Регулярное выражение для проверки входящего запроса
          if(preg_match($pattern,$query)){ //Проверяем соответствует ли запрос паттерну
               if(is_null($options)){ //Проверяем опции на NULL
                    if(!is_null($query)){ //Если запрос не пустой
                         $res = self::$mysqli->query($query); //Выполняем запрос
                         if($res != false){//Если запрос прошел
                              if(preg_match('/select/i',$query)){
                                   //Записываем в нужный формат
                                   switch($options){
                                        case 'assoc':
                                        $res = $res->fetch_assoc();
                                        break;
                                        
                                        default:
                                        $res = $res->fetch_array();
                                   }    
                                   return $res;//Возвращаем Возвращаем массив данных     
                              }else return true;
                              
                         }else echo(SPM_NAME.'Не найдены элементы указанные в запросе');
                             
                    }else echo(SPM_NAME.'Ошибка! Был вызван метод с пустым значением запроса!');     
               }    
          }else echo(SPM_NAME.'В запросе было сообщено невозможное действие');
          
          
          
                   
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