<?php
class CategoryController{
  /************************************************************
    LISTAR CATEGORÍAS
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

          $categories = CategoryModel::index();
          if (!empty($categories)) {
            http_response_code(200);
            $json = array(
              'status' => http_response_code(),
              'total_categories' => COUNT($categories),
              'categories' => $categories
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          } else {
            http_response_code(200);
            $json = array(
              'status' => http_response_code(),
              'total_categories' => COUNT($categories),
              'message' => "No hay categorías disponibles"
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
        'message' => "No esta autorizado para listar categorías"
      );
    }
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
  }
  /************************************************************
    CREAR CATEGORÍA
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
          if ($DATA['name'] != "") {
              /************************************************************
            VALIDAR NOMBRE DE CATEGORÍA
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
            VALIDAR CATEGORÍA QUE NO ESTE REPETIDO
           *************************************************************/
          $categories = CategoryModel::index();
          foreach ($categories as $category) {
            if ($category['category'] == $DATA['name']) {
              http_response_code(404);
              $json = array(
                'status' => http_response_code(),
                'message' => "La categoría ya existe en la base de datos"
              );
              echo json_encode($json, JSON_UNESCAPED_UNICODE);
              return;
            }
          }
          /************************************************************
            INGRESAR DATOS AL MODELO
           *************************************************************/
          $DATA = [
            'id_client' => $client['id'],
            'name' => $DATA['name']
          ];
          $create = CategoryModel::create($DATA);
          /************************************************************
            OBTENER RESPUESTA 
           *************************************************************/
          if ($create == "OK") {
            http_response_code(200);
            $json = array(
              'status' => http_response_code(),
              'message' => "Categoría creada con éxito"
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          }
          }else{
            http_response_code(404);
            $json = array(
              'status' => http_response_code(),
              'message' => "Error, no se permiten datos vacíos"
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
        'message' => "No esta autorizado para crear categorías"
      );
    }
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
  }
  /************************************************************
    EDITAR CATEGORÍA
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
          if ($DATA['name'] != "") {
            /************************************************************
            VALIDAR NOMBRE DE CATEGORÍA
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
            VALIDAR ID DE LA CATEGORÍA CREADO POR UN CLIENTE
           *************************************************************/
          $category = CategoryModel::show($ID);
          if ($category['id_creator'] == $client['id']) {
            /************************************************************
                INGRESAR DATOS AL MODELO
             *************************************************************/
            $DATA = [
              "id" => $ID,
              "name" => $DATA["name"]
            ];
            $update = CategoryModel::update($DATA);
            /************************************************************
                OBTENER RESPUESTA
             *************************************************************/
            if ($update == "OK") {
              http_response_code(200);
              $json = array(
                'status' => http_response_code(),
                'message' => "Su categoría ha sido actualizada con éxito"
              );
              echo json_encode($json, JSON_UNESCAPED_UNICODE);
              return;
            }
          } else {
            http_response_code(404);
            $json = array(
              'status' => http_response_code(),
              "detalle" => "No esta autorizado para modificar esta categoría"
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          }
          }else{
            http_response_code(404);
              $json = array(
                'status' => http_response_code(),
                'message' => "Error, no se permiten datos vacíos"
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
        "message" => "No esta autorizado para recibir las categorías"
      );
    }
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
  }
  /************************************************************
    LISTAR UNA CATEGORÍA
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

          $category = CategoryModel::show($ID);
          if (!empty($category)) {
            http_response_code(200);
            $json = array(
              'status' => http_response_code(),
              'category' => $category
            );
            echo json_encode($json, JSON_UNESCAPED_UNICODE);
            return;
          } else {
            http_response_code(200);
            $json = array(
              'status' => http_response_code(),
              'total_categories' => COUNT($category),
              'message' => "No hay categorías disponibles"
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
        'message' => "No esta autorizado para listar categorías"
      );
    }
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
  }
  /************************************************************
    ELIMINAR CATEGORÍA 
   *************************************************************/
  public function delete($ID) {
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
            VALIDAR ID DE LA CATEGORÍA CREADO POR UN CLIENTE
           *************************************************************/
          $category = CategoryModel::show($ID);
          if (!empty($category)) {
              if ($category['id_creator'] == $client['id']) {
                /************************************************************
                  INGRESAR DATOS AL MODELO
                 *************************************************************/
                $delete = CategoryModel::delete($ID);
                /************************************************************
                  OBTENER RESPUESTA
                 *************************************************************/
                if ($delete == "OK") {
                  http_response_code(200);
                  $json = array(
                    'status' => http_response_code(),
                    "message" => "Su categoría ha sido eliminada con éxito"
                  );
                  echo json_encode($json, JSON_UNESCAPED_UNICODE);
                  return;
                }
              } else {
                http_response_code(404);
                $json = array(
                  'status' => http_response_code(),
                  "message" => "No esta autorizado para eliminar esta categoría"
                );
                echo json_encode($json, JSON_UNESCAPED_UNICODE);
                return;
              }
            
          } else {
            http_response_code(404);
            $json = array(
              'status' => http_response_code(),
              "message" => "La categoría que desea eliminar no existe o ya ha sido eliminada."
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
        "message" => "No esta autorizado para recibir las categorías"
      );
    }
    echo json_encode($json, JSON_UNESCAPED_UNICODE);
  }
}
