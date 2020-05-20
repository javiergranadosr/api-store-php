<?php

require_once "controllers/RouteController.php";
require_once "controllers/ClientController.php";
require_once "controllers/CategoryController.php";
require_once "controllers/ProductController.php";


require_once "models/ClientModel.php";
require_once "models/CategoryModel.php";
require_once "models/ProductModel.php";

$rutas = new RouteController();
$rutas->index();