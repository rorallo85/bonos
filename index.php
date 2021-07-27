<?php
/**
* Index.php
*
* Parte de codigo que contiene el formulario de login y redirige a principal.php
*
* Incluye la clase bd.php
*
* @author Roberto Orallo Vigo
*
* @package web_bonos
*/
?>

<?php 
require_once("clases/Cliente.php");
include_once("vistas/tablaClientes.php");
include_once("vistas/formClientes.php");
include_once("vistas/tarjetaBonos2.php");
include_once("vistas/buscadorClientes.php");
include_once("vistas/impCliente.php");

/**
 * Comprueba los datos de login con la base de datos, si es correcto redirige  a principal.php y guarda la sesion
 */
if($_SERVER["REQUEST_METHOD"] =="POST" && isset($_POST['guardar_cliente'])){
  $cliente = Cliente::crearObjetoCliente($_POST['cliente']);
  $cliente->guardar();
  echo json_encode(['OK'=>$cliente, 'html'=> filaCliente($cliente)]);
  exit(0);
}

if($_SERVER["REQUEST_METHOD"] =="POST" && isset($_POST['eliminar_cliente'])){
  Cliente::eliminar($_POST['id_cliente']);
  echo json_encode(['OK'=>$_POST['id_cliente']]);
  exit(0);
}

if($_SERVER["REQUEST_METHOD"] =="POST" && isset($_POST['mostrar_bonos'])){
  $cliente = Cliente::getClienteById($_POST['id_cliente']);
  $final = count($cliente->bonos);
  echo json_encode(['OK'=>$cliente, 'html'=>mostrarBonos($cliente->bonos[$final-1], $final, $final)]);
  exit(0);
}

if($_SERVER["REQUEST_METHOD"] =="POST" && isset($_POST['guardar_sesion'])){
  $bono = Bono::getBonoById($_POST['id_cliente'], $_POST['id_bono']);
  $bono->anadirSesion($_POST['sesion']);
  $cliente = Cliente::getClienteById($_POST['id_cliente']);
  $final = count($cliente->bonos);
  $actual = $_POST['bono_actual'];
  echo json_encode(['OK'=>$cliente, 'html'=> mostrarBonos($cliente->bonos[$actual-1], $actual, $final)]);
  exit(0);
}

if($_SERVER["REQUEST_METHOD"] =="POST" && isset($_POST['borrar_sesion'])){
  Sesion::eliminar($_POST['id_sesion']);
  $cliente = Cliente::getClienteById($_POST['id_cliente']);
  $final = count($cliente->bonos);
  $actual = $_POST['bono_actual'];
  echo json_encode(['OK'=>$cliente, 'html'=> mostrarBonos($cliente->bonos[$actual-1], $actual, $final)]);
  exit(0);
}

if($_SERVER["REQUEST_METHOD"] =="POST" && isset($_POST['crear_bono'])){
  $cliente = Cliente::getClienteById($_POST["id_cliente"]);
  $cliente->anadirBonoCliente($_POST["bono"]);
  $final = count($cliente->bonos);
  echo json_encode(['OK'=>$cliente, 'html'=> mostrarBonos($cliente->bonos[$final-1], $final, $final)]);
  exit(0);
}

if($_SERVER["REQUEST_METHOD"] =="POST" && isset($_POST['moverse_bonos'])){
  $cliente = Cliente::getClienteById($_POST["id_cliente"]);
  $actual = $_POST['bono_actual'];  
  $final = count($cliente->bonos);
  if($_POST['moverse_bonos'] == 'bono_anterior'){
    echo json_encode(['OK'=>$cliente, 'html'=> mostrarBonos($cliente->bonos[$actual-2], $actual-1, $final)]);
  }else{
    echo json_encode(['OK'=>$cliente, 'html'=> mostrarBonos($cliente->bonos[$actual], $actual+1, $final)]);
  }
  exit(0);
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar_cliente'])){
  $respuesta = Cliente::getClienteByString($_POST['buscar']);
  if(is_array($respuesta)){
    $html="";
    foreach($respuesta as $cliente){
      $html .= filaCliente($cliente);
    }
    echo json_encode(['OK'=>$respuesta, 'html'=>$html]);
  }else{
    echo json_encode(['OK'=>$respuesta, 'html'=>filaCliente($respuesta)]);
  }
  exit(0);
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['borrar_filtros'])){
  $clientes = Cliente::getClientes();
  $html="";
  foreach($clientes as $cliente){
    $html .= filaCliente($cliente);
  }
  echo json_encode(['OK'=>$clientes, 'html'=>$html]);
  exit(0);
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['imprimir_cliente'])){
  $cliente = Cliente::getClienteById($_POST['id_cliente']);
  echo json_encode(['OK'=>$cliente, 'html'=>imprimirCliente($cliente)]);
  exit(0);
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['imprimir_bono'])){
  $cliente = Cliente::getClienteById($_POST['id_cliente']);
  $posicion = $_POST['bono_actual']-1;
  echo json_encode(['OK'=>$cliente, 'html'=>imprimirBonoActual($cliente, $cliente->bonos[$posicion])]);
  exit(0);
}

/**
 * Carga la página principal
 */
  echo mostrarCliente(Cliente::getClientes());
?>