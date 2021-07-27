<?php
/**
* tablaClientes.php
*
* Archivo que contiene la interfaz de la parte de gestión de clientes
*
* @author Roberto Orallo Vigo
*
* @package web_parking
*/

if(count(get_included_files()) ==1) {
  exit("Acceso directo no permitido.");
}

/**
 * funcion que muestra la tabla con los clientes que se le pasan
 * 
 * @param $clientes Array que contiene los datos de los clientes
 * @return $texto Variable con la tabla y los modal de la página de clientes
 */
function mostrarCliente($clientes) {
  ob_start(); //Comienzo de la captura del buffer
?>

  <!DOCTYPE html>
  <html lang="es">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Administración Bonos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lobster">
  <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet"> 
  <link rel="stylesheet" href="css/styles.css">
  </head>
  <body>
  <nav class="navbar navbar-light bg-light">
    <div class="container-fluid justify-content-center">
      <span class="navbar-brand p-3 fs-1">Administración de Bonos</span>
    </div>
  </nav>
  <div class="container my-4 col-11 g-0">
      <?php echo mostrarBuscadorClientes(); ?>
      <button class="btn btn-dark " id="btnNuevoCliente" data-bs-toggle="modal" data-bs-target="#ModalGuardarCliente">Nuevo Cliente</button>
      <div class="table-responsive-md my-3">
        <table class="table bg-light text-center align-middle">
          <thead>
            <tr>
                <th class="p-3">Nombre</th>
                <th class="p-3">Apellidos</th>
                <th class="p-3">Teléfono</th>
                <th class="p-3">Bonos</th>
                <th class="p-3">Acciones</th>
                
            </tr>
          </thead>
          <tbody id="TablaClienteBody">
          <?php
              if($clientes != null){ // Muestra los clientes de $clientes si los hubiera
                foreach ($clientes as $cliente) {
                    echo filaCliente($cliente);
                } 
              }
            ?>
          </tbody>
        </table>
      </div>      
    </div>

    <!-- Modal Guardar Cliente-->
    <div class="modal fade" id="ModalGuardarCliente" tabindex="-1" aria-labelledby="ModalGuardarLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content bg-light">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalGuardarLabel">Nuevo Cliente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <?= mostrarFormularioCliente();?>
          </div>
          <div class="modal-footer">
            <button type="button" id="btnCerrarModalCliente" class="btn btn-dark" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" id="btnGuardarModalCliente" class="btn btn-success" data-bs-dismiss="modal">Guardar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Bonos Cliente-->
    <div class="modal fade" id="ModalBonos" tabindex="-1" aria-labelledby="ModalBonosLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content bg-light">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalBonosLabel">Bonos Cliente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="cuerpoModalBonos">
            
          </div>
          <div class="modal-footer">
            <a href="" id="btnImprimirBono" class="me-3 fs-3 text-dark"><i class="bi bi-printer-fill"></i></a>
            <button type="button" id="btnNuevoBono" class="btn btn-outline-success">Nuevo Bono</button>
            <button type="button" id="btnCerrarModalBonos" class="btn btn-dark" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Eliminar Cliente-->
    <div class="modal fade" id="ModalEliminarCliente" tabindex="-1" aria-labelledby="ModalEliminarLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content bg-light">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalEliminarLabel">Eliminar Cliente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            ¿Esta seguro de que desa eliminar el cliente <span id="span_eliminar"> y todos sus bonos?<span>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="btnCerrarModalEliminar" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-danger" id="btnEliminarModal" data-bs-dismiss="modal">Eliminar</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
  <script src="js/funciones_cliente.js"></script>

  </body>
  </html>

<?php
  $texto = ob_get_contents(); // Guardo el buffer en una variable
  ob_end_clean(); // Limpio el buffer
  return $texto;
}


/**
 * funcion que crea una fila de la tabla con los datos del cliente
 * 
 * @param $cli Objeto cliente que contiene los datos de los clientes
 * @return $texto Tr de la tabla de lientes con los datos de un cliente 
 */
function filaCliente($cli) {
    ob_start();
?>
  <tr id="Cliente<?=$cli->id_cliente;?>">
    <td class="p-2 td_nombre"><?=$cli->nombre;?></td>
    <td class="p-2 td_apellidos"><?=$cli->apellidos;?></td>
    <td class="p-2 td_telefono"><?=$cli->telefono;?></td>
    <td class="p-2 td_tarjeta"><button class="btn btn-outline-dark btnBonos" id_cliente="<?=$cli->id_cliente;?>" data-bs-toggle="modal" data-bs-target="#ModalBonos">Abrir</button></td>
    <td class="p-2 td_acciones">
      <a href="" class="btnImprimirCliente fs-5 text-dark" id_cliente="<?=$cli->id_cliente;?>"><i class="bi bi-printer-fill"></i></a>
      <a href="" class="btnModificarCliente fs-5 mx-1 text-dark" id_cliente="<?=$cli->id_cliente;?>" data-bs-toggle="modal" data-bs-target="#ModalGuardarCliente"><i class="bi bi-pencil-square"></i></a>
      <a href="" class="btnEliminarCliente fs-5 text-dark" id_cliente="<?=$cli->id_cliente;?>" data-bs-toggle="modal" data-bs-target="#ModalEliminarCliente"><i class="bi bi-trash-fill"></i></a>
    </td>
  </tr>
<?php 
  $texto = ob_get_contents();
  ob_end_clean();
  return $texto;
}
?>