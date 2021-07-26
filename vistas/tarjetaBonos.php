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

function mostrarTarjeta($tarjeta_bonos = null, $numero_bonos=null){
  ob_start();
?>
  <!--Tabla para sellar o modificar bonos-->
  <table class="table table-bordered text-center" id="tabla_bonos">
    <tbody>
      <tr>
        <td>
          <div class="mostrar_fecha my-2"><?php if($tarjeta_bonos != null && isset($tarjeta_bonos[0])){echo $tarjeta_bonos[0]->fecha;} ?></div>
          <?php if($tarjeta_bonos != null  && isset($tarjeta_bonos[0])){ ?>
            <button type="button" class="btn btn-danger btnBorrar" id_bono=<?=$tarjeta_bonos[0]->id_bono ?> >Borrar</button>
          <?php }else{ ?>
            <button type="button" class="btn btn-success btnSellar">Sellar</button>
          <?php } ?>
        </td>
        <td>
          <div class="mostrar_fecha my-2"><?php if($tarjeta_bonos != null && isset($tarjeta_bonos[1])){echo $tarjeta_bonos[1]->fecha;} ?></div>
          <?php if($tarjeta_bonos != null && isset($tarjeta_bonos[1])){ ?>
            <button type="button" class="btn btn-danger btnBorrar" id_bono=<?=$tarjeta_bonos[1]->id_bono ?> >Borrar</button>
          <?php }else{ ?>
            <button type="button" class="btn btn-success btnSellar">Sellar</button>
          <?php } ?>
        </td>
        <td>
          <div class="mostrar_fecha my-2"><?php if($tarjeta_bonos != null && isset($tarjeta_bonos[2])){echo $tarjeta_bonos[2]->fecha;} ?></div>
          <?php if($tarjeta_bonos != null && isset($tarjeta_bonos[2])){ ?>
            <button type="button" class="btn btn-danger btnBorrar" id_bono=<?=$tarjeta_bonos[2]->id_bono ?> >Borrar</button>
          <?php }else{ ?>
            <button type="button" class="btn btn-success btnSellar">Sellar</button>
          <?php } ?>
        </td>
      </tr>
      <tr>
        <td>
          <div class="mostrar_fecha my-2"><?php if($tarjeta_bonos != null && isset($tarjeta_bonos[3])){echo $tarjeta_bonos[3]->fecha;} ?></div>
          <?php if($tarjeta_bonos != null && isset($tarjeta_bonos[3])){ ?>
            <button type="button" class="btn btn-danger btnBorrar" id_bono=<?=$tarjeta_bonos[3]->id_bono ?> >Borrar</button>
          <?php }else{ ?>
            <button type="button" class="btn btn-success btnSellar">Sellar</button>
          <?php } ?>
        </td>
        <td>
          <div class="mostrar_fecha my-2"><?php if($tarjeta_bonos != null && isset($tarjeta_bonos[4])){echo $tarjeta_bonos[4]->fecha;} ?></div>
          <?php if($tarjeta_bonos != null && isset($tarjeta_bonos[4])){ ?>
            <button type="button" class="btn btn-danger btnBorrar" id_bono=<?=$tarjeta_bonos[4]->id_bono ?> >Borrar</button>
          <?php }else{ ?>
            <button type="button" class="btn btn-success btnSellar">Sellar</button>
          <?php } ?>
        </td>
      </tr>
    </tbody>  
  </table>   

<?php
  $texto = ob_get_contents();
  ob_end_clean();
  return $texto;
}
?>