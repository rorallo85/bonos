<?php
/**
* Bono.php(class) 
*
* Clase Bono que implementa metodos y propiedades de la clase y agrupa las sesiones
*
* No se puede acceder directamente, tiene que ser llamado por otra clase
*
* Incluye la clase Bd.php
* 
* @author Roberto Orallo Vigo
*
* @package web_bonos
*/

if(count(get_included_files()) ==1) {
    exit("Acceso directo no permitido.");
}

require_once('Bd.php');
require_once('Sesion.php');

class Bono{

    public $id_bono;
    public $id_cliente;
    public $numero_sesiones;
    public $sesiones;
       
    /**
     * función que crea un objeto Bono a partir de un array
     * 
     * @param $arrayBono Contiene los datos de la tarifa
     * @return $bon Instancia de la clase Bono creado a partir del array
     */
    public static function crearObjetoBono($arrayBono) {
        $bon = new Bono();
        if(isset($arrayBono['id_bono'])){
           $bon->id_bono = $arrayBono['id_bono'];
        }
        $bon->id_cliente = $arrayBono['id_cliente'];
        $bon->numero_sesiones = 5;
        if(isset($arrayBono['id_bono'])){
            $bon->sesiones = Sesion::getSesiones($arrayBono['id_bono']);
        }else{
            $bon->sesiones = [];
        }

        return $bon;
    }


    /**
     * funcion que guarda un objeto Bono en la base de datos
     *  o lo modifica si ya tiene un id_bono definido 
     */
    public function guardar(){  
        $datos = [':id_cliente'=>$this->id_cliente,
        ':numero_sesiones'=>$this->numero_sesiones];
 
         try {
             if (isset($this->id_bono)) {
                 $sql = "UPDATE bonos SET 
                 id_cliente = :id_cliente,
                 numero_sesiones = :numero_sesiones,
                 WHERE id_bono = :id_bono";
                 $datos[':id_bono'] = $this->id_bono;
                          
             } else {
                 $sql = "INSERT into bonos (id_cliente, numero_sesiones) 
                 VALUES (:id_cliente, :numero_sesiones)";
                
             }
             
             $preparada = Bd::getConexion()->prepare($sql);	
             $preparada->execute($datos); //die(print_r($stmt->errorInfo(), true));
             if (!isset($this->id_bono)) {
                 $this->id_bono = Bd::getConexion()->lastInsertId();
             }
 
         } catch (PDOException $e) {
             throw new Exception('Error con la base de datos: ' . $e->getMessage());
         }
    }

    /**
     * funcion que recupera de la base de datos todos los bonos del cliente
     * 
     * @return $bonos Array con todos los bonos de la Base de datos
     */
    public static function getBonos($id_cliente){
        $datos[':id_cliente'] = $id_cliente;
        $bonos = [];
        try {
            $sql = "SELECT * FROM bonos WHERE id_cliente = :id_cliente";
            $preparada = Bd::getConexion()->prepare($sql);	
            $preparada->execute($datos);
            if ($preparada->rowCount() > 0) {
                $resultado = $preparada->fetchAll(PDO::FETCH_ASSOC);
                foreach($resultado as $bono){
                    array_push($bonos, self::crearObjetoBono($bono));
                }
            }
        } catch (PDOException $e) {
            throw new Exception('Error con la base de datos: ' . $e->getMessage());
        }
        
        return $bonos;
    }
 

    /**
     * funcion que recupera de la base de datos un cliente con la ID indicada
     * 
     * @param $id ID del cliente que se quiere recuperar
     * @return $cliente instancia del cliente con el ID seleccionado 
     */
    public static function getBonoById($id_cliente, $id_bono) {
        $bono = new Bono();
        $array_bonos = array_filter( self::getBonos($id_cliente),function ($bon) use ($id_bono) {
            return $bon->id_bono == $id_bono;
        }) ;
        if (count($array_bonos)==1) {
            $bono = array_shift($array_bonos);
        }
        return $bono;
    }

    /**
     * funcion que añade una sesion al bono
     */
    public function anadirSesion($arraySesion){
        $sesion = Sesion::crearObjetoSesion($arraySesion);
        try {
            $sesion->guardarSesion($arraySesion);
            if(empty($this->sesiones)){
                $this->sesiones = [$sesion];
            }else{
                array_push($this->sesiones, $sesion);
            }
        } catch (Exception $e) {
            throw new Exception('Error al añadir bono: ' . $e->getMessage());
        }
    }

    /**
     * funcion que borra una sesion del bono
     */
    public function borrarSesion($id_sesion){
        $sesion = Sesion::recuperarSesionId($id_sesion);
        try {
            $sesion->eliminar();
            unset($this->sesiones->$sesion);
            
        } catch (Exception $e) {
            throw new Exception('Error al eliminar la sesion: ' . $e->getMessage());
        }
    }
    /**
     * funcion que añade un bono a la tarjeta 
     */
    /*public function anadirBono($bono){
        $bono = Bono::crearObjetoBono($bono);
        try {
            $bono->guardarBono($this->nombre);
            if(empty($this->bonos)){
                $this->bonos = [$bono];
            }else{
                array_push($this->bonos, $bono);
            }
        } catch (Exception $e) {
            throw new Exception('Error al añadir bono: ' . $e->getMessage());
        }
    }*/

    /**
     * funcion que añade un bono a la tarjeta 
     */
    public function eliminarBono($id_bono){
        try {
            foreach ($this->bonos as $key => $bono) {
                if($bono->id_bono == $id_bono){
                    unset($this->bonos[$key]);
                }
            }

            Bono::eliminar($id_bono, $this->nombre);
            
        } catch (Exception $e) {
            throw new Exception('Error al añadir bono: ' . $e->getMessage());
        }
    }
    
    /**
     * funcion que elimina un registro de tarifa de la base de datos
     * 
     * @params $id ID de la tarifa a eliminar
     */
    public static function eliminarTarjeta($id_cliente) {
        try {
            $sql = "DROP TABLE tarjeta_{$id_cliente}"; 
            $preparada = Bd::getConexion()->prepare($sql);
            $preparada->execute();
        } catch (PDOException $e) {
            throw new Exception('Error con la base de datos: ' . $e->getMessage());
        }
    }

    /**
     * funcion que muestra los bonos que contiene la tarjeta
     * 
     * @param $id ID de la tarifa que se quiere recuperar
     * @return $tarifa instancia de la tarifa con el ID seleccionado 
     */
    public function arrayBonosTarjeta() {
        $array_bonos = [];
        if(!empty($this->bonos)){
            foreach($this->bonos as $bono){
                array_push($array_bonos, $bono->fecha);
            }
        }                            
        return $array_bonos;
    }

    /**
     * funcion que recupera de la base de datos una tarifa con el ID indicado
     * 
     * @param $id ID de la tarifa que se quiere recuperar
     * @return $tarifa instancia de la tarifa con el ID seleccionado 
     */
    public static function getTarjetaById($tarjeta_id) {
        $tarjeta = new Tarjeta();
        $tarjeta->nombre = $tarjeta_id;
        $tarjeta->bonos = Bono::getBonos($tarjeta->nombre);
                            
        return $tarjeta;
    }

    /**
     * funcion que crea una tarjeta para un cliente nuevo
     * 
     * @param $id ID de la tarifa que se quiere recuperar
     * @return $tarifa instancia de la tarifa con el ID seleccionado 
     */
    public static function nuevaTarjeta($id_cliente) {
        $tarjeta = new Tarjeta();
        $tarjeta->nombre = 'tarjeta_'.strval($id_cliente);
        try{            
            $sql = "CREATE TABLE IF NOT EXISTS {$tarjeta->nombre} 
            (id_bono int(10) AUTO_INCREMENT PRIMARY KEY,
            fecha date NOT NULL
            ) ENGINE=INNODB;";
            $preparada = Bd::getConexion()->prepare($sql);	
            $preparada->execute();

            $sql = "UPDATE clientes SET 
            tarjeta_bonos = :tabla
            WHERE id_cliente = :id_cliente;";
            $datos[':tabla'] = $tarjeta->nombre;
            $datos[':id_cliente'] = $id_cliente;
            $preparada = Bd::getConexion()->prepare($sql);	
            $preparada->execute($datos);
           
        }catch(PDOException $e) {
            throw new Exception('Error con la base de datos: ' . $e->getMessage());
        }

        return $tarjeta;
    }    

    /**
     * funcion que muestra los bonos que contiene la tarjeta
     * 
     * @param $id ID de la tarifa que se quiere recuperar
     * @return $tarifa instancia de la tarifa con el ID seleccionado 
     */
    public function reiniciarTarjeta() {
        $tabla = $this->nombre;
        try{            
            $sql = "DELETE FROM {$tabla} 
            WHERE id_bono >= 0";
            $preparada = Bd::getConexion()->prepare($sql);
            $preparada->execute();	
        }catch(PDOException $e) {
            throw new Exception('Error con la base de datos: ' . $e->getMessage());
        }

        $this->bonos = null;

    }

}

