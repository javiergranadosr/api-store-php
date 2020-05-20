<?php
/***********************************************************************
  CLASE CONEXION A LA BASE DE DATOS
***********************************************************************/
class Connection{
  /*************************************************************
   FUNCION CONECTAR
  *************************************************************/
  static public function db_connect(){
    try {
      $connection = new PDO('mysql:host=HOST;dbname=DATABASE','USER','PASSWORD',array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
      PDO::ATTR_ERRMODE,
      PDO::ERRMODE_EXCEPTION));
      return $connection;
    } catch (PDOException $e) {
      echo '<p>¡Error!: <mark>'.$e-> getMessage().
      '</mark> </p>';
      die();
    }
  }
}

