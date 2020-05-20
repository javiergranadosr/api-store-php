<?php

class ProductController{
  /************************************************************
    LISTAR PRODUCTOS
   *************************************************************/
  public function index(){
    /************************************************************
      VALIDAR CREDENCIALES DEL CLIENTE
     *************************************************************/
    $clients = ClientModel::index();
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
      foreach ($clients as $client) {
        if (
          "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
          "Basic " . base64_encode($client['id_client'] . ":" . $client['secret_key'])
        ) {

          $products = ProductModel::index();
          if (!empty($products)) {
            http_response_code(200);
            $json = array(
              'status' => http_response_code(),
              'total_products' => COUNT($products),
              'products' => $products
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          } else {
            http_response_code(200);
            $json = array(
              'status' => http_response_code(),
              'total_products' => COUNT($products),
              'message' => "No hay productos disponibles"
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          }
        } else {
          http_response_code(404);
          $json = array(
            'status' => http_response_code(),
            'message' => "Token invalido"
          );
        }
      }
    } else {
      http_response_code(404);
      $json = array(
        'status' => http_response_code(),
        'message' => "No esta autorizado para listar productos"
      );
    }
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
  }
  /************************************************************
    CREAR PRODUCTO
   *************************************************************/
  public function create($DATA){
    /************************************************************
      VALIDAR CREDENCIALES DEL CLIENTE
    *************************************************************/
    $clients = ClientModel::index();
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
      foreach ($clients as $client) {
        if (
          "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
          "Basic " . base64_encode($client['id_client'] . ":" . $client['secret_key'])
        ) {
          if ($DATA['name'] != "" || $DATA['description'] != "" || $DATA['category'] != "" || $DATA['price'] != "" ) {
              /************************************************************
            VALIDAR NOMBRE DE PRODUCTO
           *************************************************************/
          if (isset($DATA['name']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $DATA['name'])) {
            http_response_code(404);
            $json = array(
              'status' => http_response_code(),
              'message' => "Error en el campo nombre, sólo se permite letras"
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          }
          /************************************************************
            VALIDAR DESCRIPCIÓN DEL PRODUCTO
           *************************************************************/
          if (isset($DATA['description']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $DATA['description'])) {
            http_response_code(404);
            $json = array(
              'status' => http_response_code(),
              'message' => "Error en el campo descripción, sólo se permite letras"
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          }
          /************************************************************
            VALIDAR CATEGORÍA DEL PRODUCTO
           *************************************************************/
          if (isset($DATA['category']) && !preg_match('/^[0-9]+$/', $DATA['category'])) {
            http_response_code(404);
            $json = array(
              'status' => http_response_code(),
              'message' => "Error en el campo categoría, sólo se permite numeros"
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          }
          /************************************************************
            VALIDAR PRECIO DEL PRODUCTO
           *************************************************************/
          if (isset($DATA['price']) && !preg_match('/^[0-9]+$/', $DATA['price'])) {
            http_response_code(404);
            $json = array(
              'status' => http_response_code(),
              'message' => "Error en el campo precio, sólo se permite numeros"
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          }
          /************************************************************
            INGRESAR DATOS AL MODELO
           *************************************************************/
          $DATA = [
            'id_client' => $client['id'],
            'name' => $DATA['name'],
            'description' => $DATA['description'],
            'category' => $DATA['category'],
            'price' => $DATA['price']
          ];
          $create = ProductModel::create($DATA);
          /************************************************************
            OBTENER RESPUESTA 
          *************************************************************/
          if ($create == "OK") {
            http_response_code(200);
            $json = array(
              'status' => http_response_code(),
              'message' => "Producto creado con éxito"
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          }
          }else{
            http_response_code(404);
            $json = array(
              'status' => http_response_code(),
              'message' => "Error no se permiten campos vacíos"
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          }
        } else {
          http_response_code(404);
          $json = array(
            'status' => http_response_code(),
            'message' => "Token invalido"
          );
        }
      }
    } else {
      http_response_code(404);
      $json = array(
        'status' => http_response_code(),
        'message' => "No esta autorizado para crear productos"
      );
    }
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
  }
  /************************************************************
    EDITAR PRODUCTO
   *************************************************************/
  public function update($ID, $DATA){
    /************************************************************
      VALIDAR CREDENCIALES DEL CLIENTE
     *************************************************************/
    $clients = ClientModel::index();
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
      foreach ($clients as $client) {
        if (
          "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
          "Basic " . base64_encode($client['id_client'] . ":" . $client['secret_key'])
        ) {
          if ($DATA['name'] != "" || $DATA['description'] != "" || $DATA['category'] !="" || $DATA['price'] != "") {
              /************************************************************
            VALIDAR NOMBRE DE PRODUCTO
           *************************************************************/
          if (isset($DATA['name']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $DATA['name'])) {
            http_response_code(404);
            $json = array(
              'status' => http_response_code(),
              'message' => "Error en el campo nombre, sólo se permite letras"
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          }
          /************************************************************
            VALIDAR DESCRIPCIÓN DEL PRODUCTO
           *************************************************************/
          if (isset($DATA['description']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $DATA['description'])) {
            http_response_code(404);
            $json = array(
              'status' => http_response_code(),
              'message' => "Error en el campo descripción, sólo se permite letras"
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          }
          /************************************************************
            VALIDAR CATEGORÍA DEL PRODUCTO
           *************************************************************/
          if (isset($DATA['category']) && !preg_match('/^[0-9]+$/', $DATA['category'])) {
            http_response_code(404);
            $json = array(
              'status' => http_response_code(),
              'message' => "Error en el campo categoría, sólo se permite numeros"
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          }
          /************************************************************
            VALIDAR PRECIO DEL PRODUCTO
           *************************************************************/
          if (isset($DATA['price']) && !preg_match('/^[0-9]+$/', $DATA['price'])) {
            http_response_code(404);
            $json = array(
              'status' => http_response_code(),
              'message' => "Error en el campo precio, sólo se permite numeros"
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          }
          /************************************************************
            VALIDAR ID DEl PRODUCTO CREADO POR UN CLIENTE
           *************************************************************/
          $product = ProductModel::show($ID);
         
            if ($product['id_creator'] == $client['id']) {
              /************************************************************
                INGRESAR DATOS AL MODELO
              *************************************************************/
              $DATA = [
                'name' => $DATA['name'],
                'description' => $DATA['description'],
                'category' => $DATA['category'],
                'price' => $DATA['price'],
                'id' => $ID
              ];
              $create = ProductModel::update($DATA);
              /************************************************************
                OBTENER RESPUESTA 
               *************************************************************/
              if ($create == "OK") {
                http_response_code(200);
                $json = array(
                  'status' => http_response_code(),
                  'message' => "Producto actualizado con éxito"
                );
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                return;
              }
            } else {
              http_response_code(404);
              $json = array(
                'status' => http_response_code(),
                "detalle" => "No esta autorizado para modificar este producto"
              );
              echo json_encode($json, JSON_UNESCAPED_UNICODE);
              return;
            }
          }else{
            http_response_code(404);
              $json = array(
                'status' => http_response_code(),
                "detalle" => "Error no se permiten campos vacíos"
              );
              echo json_encode($json, JSON_UNESCAPED_UNICODE);
              return;
          }
        
          
        } else {
          http_response_code(404);
          $json = array(
            'status' => http_response_code(),
            "detalle" => "Token invalido"
          );
        }
      }
    } else {
      http_response_code(404);
      $json = array(
        'status' => http_response_code(),
        "message" => "No esta autorizado para recibir los productos"
      );
    }
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
  }
  /************************************************************
    LISTAR UN PRODUCTO
   *************************************************************/
  public function show($ID){
    /************************************************************
      VALIDAR CREDENCIALES DEL CLIENTE
    *************************************************************/
    $clients = ClientModel::index();
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
      foreach ($clients as $client) {
        if (
          "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
          "Basic " . base64_encode($client['id_client'] . ":" . $client['secret_key'])
        ) {

          $product = ProductModel::show($ID);
          if (!empty($product)) {
            http_response_code(200);
            $json = array(
              'status' => http_response_code(),
              'product' => $product
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          } else {
            http_response_code(200);
            $json = array(
              'status' => http_response_code(),
              'total_products' => COUNT($product),
              'message' => "No hay productos disponibles"
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          }
        } else {
          http_response_code(404);
          $json = array(
            'status' => http_response_code(),
            'message' => "Token invalido"
          );
        }
      }
    } else {
      http_response_code(404);
      $json = array(
        'status' => http_response_code(),
        'message' => "No esta autorizado para listar productos"
      );
    }
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
  }
  /************************************************************
    ELIMINAR PRODUCTO
   *************************************************************/
  public function delete($ID){
    /************************************************************
      VALIDAR CREDENCIALES DEL CLIENTE
     *************************************************************/
    $clients = ClientModel::index();
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
      foreach ($clients as $client) {
        if (
          "Basic " . base64_encode($_SERVER['PHP_AUTH_USER'] . ":" . $_SERVER['PHP_AUTH_PW']) ==
          "Basic " . base64_encode($client['id_client'] . ":" . $client['secret_key'])
        ) {
          /************************************************************
            VALIDAR ID DEL PRODUCTO CREADO POR UN CLIENTE
          *************************************************************/
          $product = productModel::show($ID);
          if (!empty($product)) {
            
              if ($product['id_creator'] == $client['id']) {
                /************************************************************
                  INGRESAR DATOS AL MODELO
                *************************************************************/
                $delete = productModel::delete($ID);
                /************************************************************
                  OBTENER RESPUESTA
                 *************************************************************/
                if ($delete == "OK") {
                  http_response_code(200);
                  $json = array(
                    'status' => http_response_code(),
                    "message" => "Su producto ha sido eliminado con éxito"
                  );
                  echo json_encode($json, JSON_UNESCAPED_UNICODE);
                  return;
                }
              } else {
                http_response_code(404);
                $json = array(
                  'status' => http_response_code(),
                  "message" => "No esta autorizado para eliminar este producto"
                );
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                return;
              }
            
          } else {
            http_response_code(404);
            $json = array(
              'status' => http_response_code(),
              "message" => "El producto que desea eliminar no existe o ya ha sido eliminado."
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          }
        } else {
          http_response_code(404);
          $json = array(
            'status' => http_response_code(),
            "message" => "Token invalido"
          );
        }
      }
    } else {
      http_response_code(404);
      $json = array(
        'status' => http_response_code(),
        "message" => "No esta autorizado para recibir los productos"
      );
    }
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
  }
  
}
