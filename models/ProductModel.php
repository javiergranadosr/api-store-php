<?php

require_once 'core/Connection.php';

class ProductModel{
  /************************************************************
    OBTENER PRODUCTOS
  *************************************************************/
  public static function index(){
    $query = "SELECT products.id, CONCAT(clients.name_client,' ',clients.surname) AS creator ,products.name AS product ,products.description, categories.name AS category, products.price 
    FROM products
    INNER JOIN clients ON products.id_client = clients.id
    INNER JOIN categories ON products.category = categories.id";
    $stmt = Connection::db_connect()->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = null;
  }
  /************************************************************
    CREAR PRODUCTO
  *************************************************************/
  public static function create($DATA){
    $query = "INSERT INTO products (id_client,name, description, category, price, updated_at) VALUES (:id_client,:name,:description,:category,:price,NOW())";
    $stmt = Connection::db_connect()->prepare($query);
    $stmt->bindParam(":id_client", $DATA['id_client'], PDO::PARAM_INT);
    $stmt->bindParam(":name", $DATA['name'], PDO::PARAM_STR);
    $stmt->bindParam(":description", $DATA['description'], PDO::PARAM_STR);
    $stmt->bindParam(":category", $DATA['category'], PDO::PARAM_INT);
    $stmt->bindParam(":price", $DATA['price']);
    
    if ($stmt->execute()) {
      return "OK";
    } else {
      print_r(Connection::db_connect()->errorInfo());
    }
    $stmt = null;
  }
  /************************************************************
    ACTUALIZAR PRODUCTO
   *************************************************************/
  public static function update($DATA){
    $query = "UPDATE products SET 
      name = :name,
      description = :description,
      category = :category,
      price = :price,
      updated_at = NOW()
      WHERE id = :id
    ";

    $stmt = Connection::db_connect()->prepare($query);

    $stmt->bindParam(":id", $DATA['id'], PDO::PARAM_INT);
    $stmt->bindParam(":name", $DATA['name'], PDO::PARAM_STR);
    $stmt->bindParam(":description", $DATA['description'], PDO::PARAM_STR);
    $stmt->bindParam(":category", $DATA['category'], PDO::PARAM_INT);
    $stmt->bindParam(":price", $DATA['price']);
   

    if ($stmt->execute()) {
      return "OK";
    } else {
      print_r(Connection::db_connect()->errorInfo());
    }
    $stmt = null;
  }
  /************************************************************
    OBTENER UN PRODUCTO
  *************************************************************/
  public static function show($ID){
    $query = "SELECT products.id, products.id_client AS id_creator, CONCAT(clients.name_client,' ',clients.surname) AS creator ,products.name AS product ,products.description, categories.name AS category, products.price
    FROM products
    INNER JOIN clients ON products.id_client = clients.id
    INNER JOIN categories ON products.category = categories.id
    WHERE products.id = :id";
    $stmt= Connection::db_connect()->prepare($query);
    $stmt->bindParam(":id",$ID,PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = null;
  }
  /************************************************************
    ELIMINAR PRODUCTO
   *************************************************************/
  public static function delete($ID){
    $query = "DELETE FROM products WHERE id = :id";
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
