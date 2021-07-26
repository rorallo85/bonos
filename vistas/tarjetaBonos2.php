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

function mostrarBonos($bono){
  ob_start();
?>
  <!--Tabla para sellar o modificar bonos-->
  <table class="table table-bordered text-center" id="tabla_bonos">
    <caption>Nº de Bono: <?= $bono->id_bono ?></caption>
    <tbody>
    <?php
      for($i = 0; $i < $bono->numero_sesiones; $i ++){
        if($i % 5 == 0){
          echo "<tr>";
        }
        ?>        
          <td>
            <div class="mostrar_fecha my-2"><?php if($bono->sesiones != null && isset($bono->sesiones[$i])){echo $bono->sesiones[$i]->fecha;} ?></div>
            <?php if($bono->sesiones != null  && isset($bono->sesiones[$i])){ ?>
              <button type="button" class="btn btn-danger btnBorrar mx-2" id_sesion=<?=$bono->sesiones[$i]->id_sesion ?> id_bono=<?=$bono->id_bono ?>>Borrar</button>
            <?php }else{ ?>
              <button type="button" class="btn btn-success btnSellar mx-2" id_bono=<?=$bono->id_bono ?>>Sellar</button>
            <?php } ?>
          </td>        
      <?php
        if(($i+1) % 5 == 0){
          echo "</tr>";
        }    
      } 
      ?>
    </tbody>  
  </table>   

<?php
  $texto = ob_get_contents();
  ob_end_clean();
  return $texto;
}

?>