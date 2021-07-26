<?php
/**
* Bono.php(class) 
*
* Clase Bono que implementa metodos y propiedades de la clase
*
* No se puede acceder directamente, tiene que ser llamdo por otra clase
*
* Incluye la clase Bd.php
* 
* @author Roberto Orallo Vigo
*
* @package web_parking
*/

if(count(get_included_files()) ==1) {
    exit("Acceso directo no permitido.");
}

require_once('Bd.php');

class Sesion{
   
    public $id_sesion;
    public $id_bono;
    public $fecha;
   
    /**
     * función que crea un objeto bono a partir de un array
     * 
     * @param $arrayBono Contiene los datos del bono
     * @return $bon Instancia de la claseBono creado a partir del array
     */
    public static function crearObjetoSesion($arraySesion) {
        $ses = new Sesion();
        if (isset($arraySesion['id_sesion'])) {
            $ses->id_sesion = $arraySesion['id_bono'];
        }
        $ses->id_bono = $arraySesion['id_bono'];
        $ses->fecha = $arraySesion['fecha'];
                       
        return $ses;
    }

    /**
     * funcion que recupera de la base de datos todas los bonos
     * 
     * @return $bonos Array con todos los bonos de la Base de datos 
     */
    public static function getSesiones($id_bono){
        $sesiones = null;
        $datos = [':id_bono'=>$id_bono];
        try {
            $sql = "SELECT * FROM sesiones WHERE id_bono = :id_bono";
            $preparada = Bd::getConexion()->prepare($sql);	
            $preparada->execute($datos);
            if ($preparada->rowCount() > 0) {
                $sesiones = $preparada->fetchAll(PDO::FETCH_CLASS, __CLASS__);
            }
        } catch (PDOException $e) {
            throw new Exception('Error con la base de datos: ' . $e->getMessage());
        }
        
        return $sesiones;
    }


    /**
     * funcion que guarda un objeto Tarifa en la base de datos
     *  o lo modifica si ya tiene un id_tarifa definido 
     */
    public function guardarSesion(){  
        $datos = [':fecha'=>$this->fecha];
         try {
             if (isset($this->id_sesion)){
                 $sql = "UPDATE sesiones SET 
                 fecha = :fecha
                 WHERE id_sesion = :id_sesion";
                 $datos[':id_sesion'] = $this->id_sesion;
                 
             } else {
                 $sql = "INSERT into sesiones (id_bono, fecha) 
                 VALUES (:id_bono, :fecha)";
                 $datos[':id_bono'] = $this->id_bono;
             }
             
             $preparada = Bd::getConexion()->prepare($sql);	
             $preparada->execute($datos); //die(print_r($stmt->errorInfo(), true));;
             if (!isset($this->id_bono)) {
                 $this->id_bono = Bd::getConexion()->lastInsertId();
             }
 
         } catch (PDOException $e) {
             throw new Exception('Error con la base de datos: ' . $e->getMessage());
         }
         
     }


    /**
     * funcion que elimina un registro de tarifa de la base de datos
     * 
     * @params $id ID de la tarifa a eliminar
     */
    public static function eliminar($id_sesion) {
        try {
            $sql = "DELETE FROM sesiones where id_sesion = :id_sesion"; 
            $preparada = Bd::getConexion()->prepare($sql);
            $preparada->execute([':id_sesion'=>$id_sesion]);
        } catch (PDOException $e) {
            throw new Exception('Error con la base de datos: ' . $e->getMessage());
        }
    }

    /**
     * funcion que recupera una sesion con id determinado la base de datos
     * 
     * @params $id ID de la sesion a recuper
     */
    /*public static function recuperarSesionId($id_sesion) {
        try {
            $sql = "SELECT * FROM sesiones where id_sesion = :id_sesion"; 
            $preparada = Bd::getConexion()->prepare($sql);
            $preparada->execute([':id_sesion'=>$id_sesion]);
            $resultado = $preparada->fetchAll(PDO::FETCH_ASSOC);
            $sesion = self::crearObjetoSesion ($resultado);
            return $sesion;
        } catch (PDOException $e) {
            throw new Exception('Error con la base de datos: ' . $e->getMessage());
        }
    }*/


    /**
     * funcion que recupera de la base de datos todas los bonos
     * 
     * @return $bonos Array con todos los bonos de la Base de datos 
     */
    /*public static function getBonos($tarjeta){
        $bonos = null;
        try {
            $sql = "SELECT * FROM {$tarjeta}";
            $preparada = Bd::getConexion()->prepare($sql);	
            $preparada->execute();
            if ($preparada->rowCount() > 0) {
                $bonos = $preparada->fetchAll(PDO::FETCH_CLASS, __CLASS__);
            }
        } catch (PDOException $e) {
            throw new Exception('Error con la base de datos: ' . $e->getMessage());
        }
        
        return $bonos;
    }

    /**
     * funcion que guarda un objeto Tarifa en la base de datos
     *  o lo modifica si ya tiene un id_tarifa definido 
     */
    /*public function guardarBono($nombre_tarjeta){  
       $datos = [':fecha'=>$this->fecha];
        try {
            if (isset($this->id_bono)){
                $sql = "UPDATE {$nombre_tarjeta} SET 
                fecha = :fecha
                WHERE id_bono = :id_bono";
                $datos[':id_bono'] = $this->id_bono;
                
            } else {
                $sql = "INSERT into {$nombre_tarjeta} (fecha) 
                VALUES (:fecha)";
            }
            
            $preparada = Bd::getConexion()->prepare($sql);	
            $preparada->execute($datos); //die(print_r($stmt->errorInfo(), true));;
            if (!isset($this->id_bono)) {
                $this->id_bono = Bd::getConexion()->lastInsertId();
            }

        } catch (PDOException $e) {
            throw new Exception('Error con la base de datos: ' . $e->getMessage());
        }
        
    }

   

   

   /**
     * funcion que recupera de la base de datos una tarifa con el ID indicado
     * 
     * @param $id ID de la tarifa que se quiere recuperar
     * @return $tarifa instancia de la tarifa con el ID seleccionado 
     *//*
    public static function getTarjetaById($id) {
        $tarifa = new Tarifa();
        $array_tarifas = array_filter( self::getTarifas(),function ($tar) use ($id) {
            return $tar->id_tarifa == $id;
        }) ;
        if (count($array_tarifas)==1) {
            $tarifa = array_shift($array_tarifas);
        }
        return $tarifa;
    }*/

}

