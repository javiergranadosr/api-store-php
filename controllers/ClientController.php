<?php

class ClientController{
  /*************************************************************
    CREAR CLIENTE
  *************************************************************/
  public function create($DATA){
    /************************************************************
      VALIDAR NOMBRE
    *************************************************************/
    if ($DATA['name_client'] != "" || $DATA['surname'] !="" || $DATA['email'] != "") {

      if (isset($DATA["name_client"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $DATA["name_client"])) {
        http_response_code(404);
        $json = array(
          "status" => http_response_code(),
          "message" => "Error en el campo nombre, sólo se permite letras"
        );
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        return;
      }
      /************************************************************
        VALIDAR APELLIDO
       *************************************************************/
      if (isset($DATA["surname"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $DATA["surname"])) {
        http_response_code(404);
        $json = array(
          "status" => http_response_code(),
          "message" => "Error en el campo apellidos, sólo se permite letras"
        );
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        return;
      }
      /************************************************************
        VALIDAR EMAIL
       *************************************************************/
      if (isset($DATA["email"]) && !preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $DATA["email"])) {
        http_response_code(404);
        $json = array(
          "status" => http_response_code(),
          "message" => "Error en el campo email, coloca un email válido"
        );
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        return;
      }
      /************************************************************
        VALIDAR EMAIL QUE NO ESTE REPETIDO
       *************************************************************/
      $client = ClientModel::index();
      foreach ($client as $key => $valueClient) {
        if ($valueClient["email"] == $DATA["email"]) {
          http_response_code(404);
          $json = array(
            "status" => http_response_code(),
            "message" => "El email ya existe en la base de datos"
          );
          echo json_encode($json, JSON_UNESCAPED_UNICODE);
          return;
        }
      }
      /************************************************************
        GENERAR CREDENCIALES DEL CLIENTE
       *************************************************************/
      $id_client = str_replace("$", "a", crypt($DATA["name_client"] . $DATA["surname"] . $DATA["email"], '$2a$07$m9U2fVSKjI1FeCSX$Iz6R2XsLBbr74*hRKbLlVYH$'));
      $secret_key = str_replace("$", "o", crypt($DATA["email"] . $DATA["surname"] . $DATA["name_client"], '$2a$07$m9U2fVSKjI1FeCSX$Iz6R2XsLBbr74*hRKbLlVYH$'));
      /************************************************************
        LLEVAR DATOS AL MODELO
       *************************************************************/
      $DATA = array(
        "name_client" => $DATA['name_client'],
        "surname" => $DATA['surname'],
        "email" => $DATA['email'],
        "id_client" => $id_client,
        "secret_key" => $secret_key
      );
      $create = ClientModel::create($DATA);
      /************************************************************
        RESPUESTA DEL MODELO
       *************************************************************/
      if ($create == "OK") {
        http_response_code(200);
        $json = array(
          "status" => http_response_code(),
          "message" => "Registro exitoso, tome sus credenciales y guárdelas",
          "credentials" => array("id_client" => $id_client, "secret_key" => $secret_key)
        );
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        return;
      }
    }else{
      http_response_code(404);
        $json = array(
          "status" => http_response_code(),
          "message" => "Error no se permiten datos vacíos"
        );
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        return;
    }
    
  }
}
