<?php
/**
* formClientes.php
*
* Archivo que contiene el formulario con los datos del Cliente
* Se usa para modificar o crear un cliente.
*
* @author Roberto Orallo Vigo
*
* @package web_parking
*/

if(count(get_included_files()) ==1) {
  exit("Acceso directo no permitido.");
}

function mostrarFormularioCliente(){
  ob_start();
?>
  <!--Formulario para guardar o modificar un cliente en la base de datos-->
  <form id="formGuardarCliente" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
    <input id="nombre_cli" type="text" class="form-control my-2" name="cliente[nombre]" placeholder="Nombre" required autofocus>
    <input id="apellidos_cli" type="text" class="form-control my-2" name="cliente[apellidos]" placeholder="Apellidos">
    <input id="telefono_cli" type="text" class="form-control my-2" name="cliente[telefono]" placeholder="Teléfono">
  </form>
   

<?php
  $texto = ob_get_contents();
  ob_end_clean();
  return $texto;
}
?>