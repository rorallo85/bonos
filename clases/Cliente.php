<?php
/**
* Cliente.php(class) 
*
* Clase cliente que implementa metodos y propiedades de la clase
*
* No se puede acceder directamente, tiene que ser llamdo por otra clase
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
require_once('Bono.php');

class Cliente {
   
   public  $id_cliente;
   public  $nombre;
   public  $apellidos;
   public  $telefono;
   public  $bonos;
   
    /**
     * función que crea un objeto cliente a partir de un array
     * 
     * @param $arrayCliente Contiene los datos del cliente
     * @return $cli Instancia de la clase Cliente creado a partir del array
     */
    public static function crearObjetoCliente($arrayCliente) {
        $cli = new Cliente();

        if (isset($arrayCliente['id_cliente'])) {
            $cli->id_cliente = $arrayCliente['id_cliente'];
        }
        $cli->nombre = $arrayCliente['nombre'];
        $cli->apellidos = $arrayCliente['apellidos'];
        $cli->telefono = $arrayCliente['telefono'];
        if (isset($arrayCliente['id_cliente'])) {
            $cli->bonos = Bono::getBonos($cli->id_cliente);
        }else{
            $cli->bonos = [];
        }       
                      
        return $cli;
    }

    /**
     * funcion que guarda un objeto Cliente en la base de datos
     *  o lo modifica si ya tiene un id_cliente definido 
     */
    public function guardar(){  
       $datos = [':nombre'=>$this->nombre,
       ':apellidos'=>$this->apellidos,
       ':telefono'=>$this->telefono];

        try {
            if (isset($this->id_cliente)) {
                $sql = "UPDATE clientes SET 
                nombre = :nombre,
                apellidos = :apellidos,
                telefono = :telefono                
                WHERE id_cliente = :id_cliente";
                $datos[':id_cliente'] = $this->id_cliente;
                         
            } else {
                $sql = "INSERT into clientes (nombre, apellidos, telefono) 
                VALUES (:nombre, :apellidos, :telefono)";
               
            }
            
            $preparada = Bd::getConexion()->prepare($sql);	
            $preparada->execute($datos); //die(print_r($stmt->errorInfo(), true));;
            if (!isset($this->id_cliente)) {
                $this->id_cliente = Bd::getConexion()->lastInsertId();
                $this->bonos = Bono::crearObjetoBono(['id_cliente' => $this->id_cliente]);
                $this->bonos->guardar();
            }

        } catch (PDOException $e) {
            throw new Exception('Error con la base de datos: ' . $e->getMessage());
        }
    }

   /**
     * funcion que elimina un registro de cliente de la base de datos
     * 
     * @params $id ID del cliente a eliminar
     */
    public static function eliminar($id) {
        try {
            $sql = "DELETE FROM clientes where id_cliente = :id_cliente"; 
            $preparada = Bd::getConexion()->prepare($sql);
            $preparada->execute([':id_cliente'=>$id]);
        } catch (PDOException $e) {
            throw new Exception('Error con la base de datos: ' . $e->getMessage());
        }
    }

    /**
     * funcion que recupera de la base de datos todos los clientes
     * 
     * @return $clientes Array con todos los clientes de la Base de datos 
     */
    public static function getClientes(){
        $clientes = [];
        try {
            $sql = "SELECT * FROM clientes";
            $preparada = Bd::getConexion()->prepare($sql);	
            $preparada->execute();
            if ($preparada->rowCount() > 0) {
                $resultado = $preparada->fetchAll(PDO::FETCH_ASSOC);
                foreach($resultado as $cliente){
                    array_push($clientes, self::crearObjetoCliente($cliente));
                }
            }
        } catch (PDOException $e) {
            throw new Exception('Error con la base de datos: ' . $e->getMessage());
        }
        
        return $clientes;
    }

   /**
     * funcion que recupera de la base de datos un cliente con la ID indicada
     * 
     * @param $id ID del cliente que se quiere recuperar
     * @return $cliente instancia del cliente con el ID seleccionado 
     */
    public static function getClienteById($id) {
        $cliente = new Cliente();
        $array_clientes = array_filter( self::getClientes(),function ($cli) use ($id) {
            return $cli->id_cliente == $id;
        }) ;
        if (count($array_clientes)==1) {
            $cliente = array_shift($array_clientes);
        }
        return $cliente;
    }

    /**
     * funcion que recupera de la base de datos un cliente con el nombre o apellidos indicado
     * 
     * @param $id ID del cliente que se quiere recuperar
     * @return $cliente instancia del cliente con el ID seleccionado 
     */
    public static function getClienteByString($string) {
        $cliente = new Cliente();
        $texto = $string['texto'];
        $propiedad = $string['propiedad'];
        if($propiedad == 'nombre'){
            $array_clientes = array_filter( self::getClientes(),function ($cli) use ($texto) {
                return $cli->nombre == $texto;
            });            
        }else if($propiedad == 'apellidos'){
            $array_clientes = array_filter( self::getClientes(),function ($cli) use ($texto) {
                return $cli->apellidos == $texto;
            });            
        }else{
            $array_clientes = array_filter( self::getClientes(),function ($cli) use ($texto) {
                return $cli->telefono == $texto;
            });  
        }
        if (count($array_clientes)==1) {
            $cliente = array_shift($array_clientes);
            
        }else{
            $cliente = $array_clientes;
        }

        return $cliente;
        
    }


    /**
     * funcion que recupera de la base de datos un cliente con el nombre o apellidos indicado
     * 
     * @param $id ID del cliente que se quiere recuperar
     * @return $cliente instancia del cliente con el ID seleccionado 
     */
    public function anadirBonoCliente($arrayBono) {
        $bono = Bono::crearObjetoBono($arrayBono);
        $bono->guardar();
        array_push($this->bonos, $bono);
    }
    
}

