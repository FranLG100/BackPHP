<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App;

//GET ALL
$app->get('/usuarios', function(Request $request, Response $response){
    $sql = "SELECT * FROM USUARIOS";
    try{
      $db = new db();
      $db = $db->conectDB();
      $resultado = $db->query($sql);
      if ($resultado->rowCount() > 0){
        $clientes = $resultado->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($clientes);
      }else {
        echo json_encode("No existen clientes en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  });

  $app->get('/mongopruebas', function(Request $request, Response $response){
    $m = new MongoDB\Client('mongodb+srv://fralg100:canon100@clusterfran-65tu8.gcp.mongodb.net/test?retryWrites=true&w=majority');
    $result = $m->pruebas->primera_coleccion->find()->toArray();
    //print_r($result);
    echo json_encode($result);
    foreach ($result as $document) {
    echo $document['nombre'] . "\n";
    }
  }); 


  $app->post('/mongocentros', function(Request $request, Response $response){
    $cif = $request->getParam('cif');
	  $nombre = $request->getParam('nombre');
    $telefono = $request->getParam('telefono');
    $email = $request->getParam('email');
    $direccion = $request->getParam('direccion');

    $m = new MongoDB\Client('mongodb+srv://fralg100:canon100@clusterfran-65tu8.gcp.mongodb.net/test?retryWrites=true&w=majority');
    $m->reservas->centros->insertOne([
      'cif' => $cif,
      'nombre' => $nombre,
      'telefono' => $telefono,
      'email' => $email,
      'direccion' => $direccion
    ]);


});

$app->run();