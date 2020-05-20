<?php

require_once 'core/Connection.php';

class ClientModel {
  /************************************************************
  OBTENER CLIENTES
  *************************************************************/
  public static function index(){
    $query = "SELECT * FROM clients";
    $stmt= Connection::db_connect()->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = null;
  }
  /************************************************************
  CREAR CLIENTE
  *************************************************************/
  public static function create($DATA){

    $query = "INSERT INTO clients (name_client,surname,email,id_client,secret_key,updated_at) VALUES (:name_client,:surname,:email,:id_client,:secret_key,NOW())";

    $stmt= Connection::db_connect()->prepare($query);

    $stmt->bindParam(":name_client",$DATA['name_client'],PDO::PARAM_STR);
    $stmt->bindParam(":surname",$DATA['surname'],PDO::PARAM_STR);
    $stmt->bindParam(":email",$DATA['email'],PDO::PARAM_STR);
    $stmt->bindParam(":id_client",$DATA['id_client'],PDO::PARAM_STR);
    $stmt->bindParam(":secret_key",$DATA['secret_key'],PDO::PARAM_STR);
  
   
    if ($stmt->execute()) {
      return "OK";
    }else{
      print_r(Connection::db_connect()->errorInfo());
    }
    $stmt = null;
  }

}