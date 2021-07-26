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

/*if($_SERVER["REQUEST_METHOD"] =="POST" && isset($_POST['crear_bono'])){
  $bono = Bono::crearObjetoBono($_POST['bono']);
  $bono->guardar();
  echo json_encode(['OK'=>$bono, 'html'=> mostrarBonos($bono)]);
  exit(0);
}*/

if($_SERVER["REQUEST_METHOD"] =="POST" && isset($_POST['mostrar_bonos'])){
  $cliente = Cliente::getClienteById($_POST['id_cliente']);
  $final = count($cliente->bonos);
  echo json_encode(['OK'=>$cliente, 'html'=>mostrarBonos($cliente->bonos[$final-1])]);
  exit(0);
}

if($_SERVER["REQUEST_METHOD"] =="POST" && isset($_POST['guardar_sesion'])){
  $bono = Bono::getBonoById($_POST['id_cliente'], $_POST['id_bono']);
  $bono->anadirSesion($_POST['sesion']);
  $cliente = Cliente::getClienteById($_POST['id_cliente']);
  $final = count($cliente->bonos);
  echo json_encode(['OK'=>$cliente, 'html'=> mostrarBonos($cliente->bonos[$final-1])]);
  exit(0);
}

if($_SERVER["REQUEST_METHOD"] =="POST" && isset($_POST['borrar_sesion'])){
  Sesion::eliminar($_POST['id_sesion']);
  $cliente = Cliente::getClienteById($_POST['id_cliente']);
  $final = count($cliente->bonos);
  echo json_encode(['OK'=>$cliente, 'html'=> mostrarBonos($cliente->bonos[$final-1])]);
  exit(0);
}
/*if($_SERVER["REQUEST_METHOD"] =="POST" && isset($_POST['borrar_bono'])){
  $cliente = Cliente::getClienteById($_POST['id_cliente']);
  $cliente->tarjeta_bonos->eliminarBono($_POST['id_bono']);
  echo json_encode(['OK'=>$cliente, 'html'=>mostrarTarjeta($cliente->tarjeta_bonos->bonos, $cliente->cantidad_bonos)]);
  exit(0);
}*/

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

/*if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reiniciar_tarjeta'])){
  $cliente = Cliente::getClienteById($_POST['id_cliente']);
  $cliente->tarjeta_bonos->reiniciarTarjeta();
  echo json_encode(['OK'=>$cliente, 'html'=>mostrarTarjeta($cliente->tarjeta_bonos->bonos, $cliente->cantidad_bonos)]);
  exit(0);
}*/

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['imprimir_cliente'])){
  $cliente = Cliente::getClienteById($_POST['id_cliente']);
  echo json_encode(['OK'=>$cliente, 'html'=>imprimirCliente($cliente)]);
  exit(0);
}

/**
 * Carga la página principal
 */
  echo mostrarCliente(Cliente::getClientes());
?>