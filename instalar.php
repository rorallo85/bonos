<?php
/**
* Instalar.php
*
* Parte de instalacion de la base de datos
*
* @author Roberto Orallo Vigo
*
* @package bonos
*/

$consulta="
    CREATE TABLE clientes(
    id_cliente INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(20) NOT NULL,
    apellidos varchar(50),
    telefono varchar(12),
    direccion varchar(100),
    tarjeta_bonos varchar(12),
    cantidad_bonos varchar(50)
    )ENGINE=INNODB;";


$servidor = "localhost";
$usuario = "root";
$password = "";
$conexion = new mysqli($servidor, $usuario, $password); // crea la conexión con MySQL
if($conexion->connect_error){
    die($mensaje = "<div class='alert alert-danger text-center'>Conexión fallida: ".$connect_error."</div>");
}
   
$sql = "
    DROP USER IF EXISTS 'bonos'@'localhost'; DROP DATABASE IF EXISTS bonos; CREATE DATABASE bonos;
    CREATE USER 'bonos'@'localhost' IDENTIFIED BY 'bonos';
    GRANT ALL PRIVILEGES ON bonos.* TO 'bonos'@'localhost';
    USE bonos;";
$sql .= $consulta;
if($conexion->multi_query($sql) === true){
    $mensaje = "<div class='alert alert-success text-center'>Base de datos creada correctamente...</div>";
}else{
    die($mensaje = "<div class='alert alert-danger text-center'>Error al crear base de datos: ".$conexion->error."</div>");
}
?>

<!DOCTYPE HTML>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <title>Instalación Base de datos MySQL</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>

<div class="row g-0 align-items-center" id="marco">
   <?= $mensaje ?>;
</div>
   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

</body>
</html>