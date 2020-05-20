<?php
/************************************************************
  VALIDAR URL
 *************************************************************/
if (isset($_GET['URL'])) {
  /************************************************************
    OBTENER URL Y EL NUMERO (ID)
   *************************************************************/
  $URL = $_GET['URL'];
  $ID = intval(preg_replace('/[^0-9]+/', '', $URL));
  /**
   * ENDPOINTS
   * client: CREAR CLIENTE
   * category: CREAR CATEGORÍA
   * categories: LISTAR CATEGORIAS
   * category/ID: LISTAR UNA CATEGORÍA
   * update-category/ID: ACTUALIZAR CATEGORIA
   * delete-category/ID: ELIMINAR CATEGORIA
   * product: CREAR PRODUCTO
   * products: LISTAR PRODUCTOS
   * product/ID: LISTAR UN PRODUCTO
   * update-product/ID: ACTUALIZAR PRODUCTO
   * delete-product/ID: ELIMINAR PRODUCTO
   */
  /************************************************************
    PETICIONES POST
   *************************************************************/
  if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    /************************************************************
      http://api-store.test/client - CREAR CLIENTE
    *************************************************************/
    if ($URL == "client") {
      /************************************************************
        CAPTURAR DATOS
      *************************************************************/
      $DATA = [
        'name_client' => $_POST['name_client'],
        'surname' => $_POST['surname'],
        'email' => $_POST['email']
      ];
      
      /************************************************************
        ENVIAR DATOS AL CONTROLADOR
       *************************************************************/
      $create = new ClientController();
      $create->create($DATA);
      /************************************************************
        http://api-store.test/category - CREAR CATEGORÍA
      *************************************************************/
    } else if ($URL == "category") {
      /************************************************************
        CAPTURAR DATOS
       *************************************************************/
      $DATA = [
        'name' => $_POST['name']
      ];
      /************************************************************
        ENVIAR DATOS AL CONTROLADOR
      *************************************************************/
      $create = new CategoryController();
      $create->create($DATA);
      /************************************************************
        http://api-store.test/product - CREAR PRODUCTO
      *************************************************************/
    } else if ($URL == "product") {
      /************************************************************
        CAPTURAR DATOS
      *************************************************************/
      $DATA = [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'category' => $_POST['category'],
        'price' => $_POST['price']
      ];
      /************************************************************
        ENVIAR DATOS AL CONTROLADOR
       *************************************************************/
      $create = new ProductController();
      $create->create($DATA);
    } else {
      http_response_code(404);
      $json = array(
        "status" => http_response_code(),
        "message" => "Not Found POST"
      );
      echo json_encode($json, JSON_UNESCAPED_UNICODE);
      return;
    }
  }
  /************************************************************
    PETICIONES GET
   *************************************************************/
  if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {
    /************************************************************
      http://api-store.test/categories - LISTAR CATEGORÍAS
    *************************************************************/
    if ($URL == "categories") {
      /************************************************************
        LISTAR CATEGORÍAS
      *************************************************************/
      $categories = new CategoryController();
      $categories->index();
      /************************************************************
        http://api-store.test/category/ID - LISTAR UNA CATEGORÍA
      *************************************************************/
    } else if ($URL == "category/".$ID) {
      $category = new CategoryController();
      $category->show($ID);
      /************************************************************
        http://api-store.test/products/ - LISTAR PRODUCTOS
      *************************************************************/
    } else if($URL == "products"){
      /************************************************************
        LISTAR PRODUCTOS
      *************************************************************/
      $products = new ProductController();
      $products->index();
      /************************************************************
        http://api-store.test/product/ID -LISTAR UN PRODUCTO
      *************************************************************/
    }else if($URL == "product/".$ID){
      $product = new ProductController();
      $product->show($ID);
    } else {
      http_response_code(404);
      $json = array(
        "status" => http_response_code(),
        "message" => "Not Found GET"
      );
      echo json_encode($json, JSON_UNESCAPED_UNICODE);
      return;
    }
  }
  /************************************************************
    PETICIONES PUT
   *************************************************************/
  if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "PUT") {
    /************************************************************
      http://api-store.test/update-category/ID - ACTUALIZAR CATEGORÍA
     *************************************************************/
    if ($URL == "update-category/" . $ID) {
      /************************************************************
        CAPTURAR DATOS
       *************************************************************/
      $DATA = [];
      parse_str(file_get_contents('php://input'), $DATA); // CAPTURAMOS DATOS DEL FORMULARIO Y LOS INGRESAMOS A UN ARRAY
      /************************************************************
        ENVIAR DATOS AL CONTROLADOR
       *************************************************************/
      $update = new CategoryController();
      $update->update($ID, $DATA);
      /************************************************************
      http://api-store.test/update-product/ID - ACTUALIZAR PRODUCTO
       *************************************************************/
    } else if ($URL == "update-product/" . $ID) {
      /************************************************************
        CAPTURAR DATOS
      *************************************************************/
      $DATA = [];
      parse_str(file_get_contents('php://input'), $DATA); // CAPTURAMOS DATOS DEL FORMULARIO Y LOS INGRESAMOS A UN ARRAY
      /************************************************************
        ENVIAR DATOS AL CONTROLADOR
       *************************************************************/
      $update = new ProductController();
      $update->update($ID, $DATA);

    } else {
      http_response_code(404);
      $json = array(
        "status" => http_response_code(),
        "message" => "Not Found PUT"
      );
      echo json_encode($json, JSON_UNESCAPED_UNICODE);
      return;
    }
  }
  /************************************************************
    PETICIONES DELETE
   *************************************************************/
  if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "DELETE") {
    /************************************************************
      http://api-store.test/delete-category/ID - ELIMINAR CATEGORÍA
     *************************************************************/
    if ($URL == "delete-category/" . $ID) {
      /************************************************************
        ENVIAR DATOS AL CONTROLADOR
       *************************************************************/
      $update = new CategoryController();
      $update->delete($ID);
      /************************************************************
      http://api-store.test/delete-product/ID - ELIMINAR PRODUCTO
       *************************************************************/
    } else if ($URL == "delete-product/" . $ID) {
       /************************************************************
        ENVIAR DATOS AL CONTROLADOR
       *************************************************************/
      $delete = new ProductController();
      $delete->delete($ID);
    } else {
      http_response_code(404);
      $json = array(
        "status" => http_response_code(),
        "message" => "Not Found DELETE"
      );
      echo json_encode($json, JSON_UNESCAPED_UNICODE);
      return;
    }
  } 

} else {
  http_response_code(404);
      $json = array(
        "status" => http_response_code(),
        "message" => "Not Found URL"
      );
      echo json_encode($json, JSON_UNESCAPED_UNICODE);
      return;
}
