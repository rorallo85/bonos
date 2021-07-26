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
function imprimirCliente($cliente) {
  ob_start(); //Comienzo de la captura del buffer
?>

  <!DOCTYPE html>
  <html lang="es">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Impresion Cliente</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lobster">
  <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet"> 
  <link rel="stylesheet" href="css/styles.css">
  </head>
  <body>
  <nav class="navbar navbar-light bg-light">
    <div class="container-fluid justify-content-center">
      <span class="navbar-brand p-3 fs-1">Tarjeta de bonos</span>
    </div>
  </nav>
  <div class="container my-4 col-11 g-0">
    Nombre: <?= $cliente->nombre?><br>
    Apellidos: <?= $cliente->apellidos?><br>
    Nº Bonos por tarjeta: <?= $cliente->cantidad_bonos?>

    <div class="table-responsive-md my-3">
      <table class="table bg-light text-center align-middle">
        <thead>
          <tr>
            <th class="p-3">Bono</th>
            <th class="p-3">Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php
              imprimirBonos($cliente);
            ?>
          </tbody>
        </table>
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
function imprimirBonos($cliente) {
    $i=0;
    foreach($cliente->tarjeta_bonos->bonos as $numero => $bono){
      $i++;
      echo "<tr><td>Bono nº {$i}</td><td>{$bono->fecha}</td></tr>";
    }
 
  
}
?>