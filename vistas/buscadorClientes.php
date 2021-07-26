<?php
/**
* buscadorClientes.php
*
* Archivo que contiene el formulario para buscar Clientes
*
* @author Roberto Orallo Vigo
*
* @package web_bonos
*/

if(count(get_included_files()) ==1) {
  exit("Acceso directo no permitido.");
}

function mostrarBuscadorClientes(){
  ob_start();
?>
  <!--Formulario para buscar un cliente-->
  <div class="alert alert-light bg-light" role="alert">
    <form id="formBuscarCliente" class="row g-0" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
      <div class="col-auto mx-2 my-1">  
        <select class="form-select" name="buscar[propiedad]" aria-label="Selecciona campo en el que buscar">
          <option value="nombre" selected>Nombre</option>
          <option value="apellidos">Apellidos</option>
          <option value="telefono">Teléfono</option>
        </select>
      </div>
      <div class="col-auto mx-2 my-1">
        <input id="texto_buscar" type="text" class="form-control" name="buscar[texto]" placeholder="Introduzca texto..." required>
      </div>
      <div class="col-auto mx-2 my-1">
        <button type="button" id="btnBuscar" class="btn btn-dark px-4">Buscar</button>
      </div>
      <div class="col-auto mx-2 my-1">
        <a href="#" id="reiniciarBuscar" class="text-secondary align-middle"><i class="bi bi-x-circle"></i> Eliminar filtros</a>
      </div>      
    </form>
  </div>
   

<?php
  $texto = ob_get_contents();
  ob_end_clean();
  return $texto;
}
?>