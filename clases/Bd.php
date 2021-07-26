<?php
/**
* bd.php(class) 
*
* Clase bd que implementa metodos y propiedades de la clase
*
* No se puede acceder directamente, tiene que ser llamdo por otra clase
*
* @author Roberto Orallo Vigo
*
* @package web_parking
*/

if(count(get_included_files()) ==1) {
    exit("Acceso directo no permitido.");
  }

class Bd{

    private static $conexion; 

    private function __construct() {

    }

    public static function getConexion() {
        /**
         * INFORMACIÓN DE LA BASE DE DATOS
         * dbname = 'bonos'
         * host=127.0.0.1
         * usuario= 'root'
         * clave=''
         */
        //TODO Implementar este metodo
        if (!isset(self::$conexion)) {
           
            try{
                $cadena_conexion = 'mysql:dbname=bonos2;host=127.0.0.1';
                $usuario = 'bonos2';
                $clave = 'bonos';
                self::$conexion = new PDO($cadena_conexion, $usuario, $clave, array(PDO::ATTR_PERSISTENT => true));
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
                throw new Exception('Error conexion con la base de datos: ' . $e->getMessage());
            }
        }

        return self::$conexion;
    
    }

}