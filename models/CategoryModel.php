<?php

require_once 'core/Connection.php';

class CategoryModel{
  /************************************************************
    OBTENER CATEGORÍAS
  *************************************************************/
  public static function index(){
    $query = "SELECT categories.id, CONCAT(clients.name_client,' ',clients.surname) AS creator ,categories.name AS category 
    FROM categories
    INNER JOIN clients ON categories.id_client = clients.id";
    $stmt = Connection::db_connect()->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = null;
  }
  /************************************************************
    CREAR CATEGORÍA
  *************************************************************/
  public static function create($DATA){
    $query = "INSERT INTO categories (id_client,name,updated_at) VALUES (:id_client,:name,NOW())";
    $stmt = Connection::db_connect()->prepare($query);
    $stmt->bindParam(":id_client", $DATA['id_client'], PDO::PARAM_INT);
    $stmt->bindParam(":name", $DATA['name'], PDO::PARAM_STR);
    
    if ($stmt->execute()) {
      return "OK";
    } else {
      print_r(Connection::db_connect()->errorInfo());
    }
    $stmt = null;
  }
  /************************************************************
    ACTUALIZAR CATEGORÍA
   *************************************************************/
  public static function update($DATA){
    $query = "UPDATE categories SET 
      name = :name,
      updated_at = NOW()
      WHERE id = :id
    ";

    $stmt = Connection::db_connect()->prepare($query);

    $stmt->bindParam(":id", $DATA['id'], PDO::PARAM_INT);
    $stmt->bindParam(":name", $DATA['name'], PDO::PARAM_STR);
   

    if ($stmt->execute()) {
      return "OK";
    } else {
      print_r(Connection::db_connect()->errorInfo());
    }
    $stmt = null;
  }
  /************************************************************
    OBTENER UNA CATEGORÍA
  *************************************************************/
  public static function show($ID){
    $query = "SELECT categories.id,categories.id_client AS id_creator,CONCAT(clients.name_client,' ',clients.surname) AS creator ,categories.name AS category 
    FROM categories
    INNER JOIN clients ON categories.id_client = clients.id 
    WHERE categories.id = $ID";
    $stmt= Connection::db_connect()->prepare($query);
    $stmt->bindParam(":id",$ID,PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = null;
  }
  /************************************************************
    ELIMINAR CATEGORÍA
   *************************************************************/
  public static function delete($ID){
    $query = "DELETE FROM categories WHERE id = :id";
    $stmt = Connection::db_connect()->prepare($query);
    $stmt->bindParam(":id", $ID, PDO::PARAM_INT);
    if ($stmt->execute()) {
      return "OK";
    } else {
      print_r(Connection::db_connect()->errorInfo());
    }
    $stmt = null;
  }
}
