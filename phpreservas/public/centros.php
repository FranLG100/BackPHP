<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App;

header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Allow: GET, POST, OPTIONS, PUT, DELETE");
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == "OPTIONS") {
			die();
		}
//GET ALL
$app->get('/centros', function(Request $request, Response $response){
    $m = new MongoDB\Client('mongodb+srv://fralg100:canon100@clusterfran-65tu8.gcp.mongodb.net/test?retryWrites=true&w=majority');
    $result = $m->reservas->centros->find()->toArray();
    echo json_encode($result);
  }); 

// GET Recuperar usuario por ID 
$app->get('/centros/{id}', function(Request $request, Response $response){
    $id_centro = intval($request->getAttribute('id'));
    $m = new MongoDB\Client('mongodb+srv://fralg100:canon100@clusterfran-65tu8.gcp.mongodb.net/test?retryWrites=true&w=majority');
    $result = $m->reservas->centros->findOne(['ID'=>$id_centro]);
    echo json_encode($result);
}); 

// POST Crear nuevo usuario
$app->post('/centros', function(Request $request, Response $response){
    $id = $request->getParam('id');
    $cif = $request->getParam('cif');
	$nombre = $request->getParam('nombre');
    $telefono = $request->getParam('telefono');
    $email = $request->getParam('email');
    $direccion = $request->getParam('direccion');

    $m = new MongoDB\Client('mongodb+srv://fralg100:canon100@clusterfran-65tu8.gcp.mongodb.net/test?retryWrites=true&w=majority');
    $m->reservas->centros->insertOne([
      'ID' => $id,
      'CIF' => $cif,
      'NOMBRE' => $nombre,
      'TELEFONO' => $telefono,
      'EMAIL' => $email,
      'DIRECCION' => $direccion
    ]);
});

// PUT Modificar usuario
$app->put('/centros/modificar/{id}', function(Request $request, Response $response){
   $id_centro = $request->getAttribute('id');
	$cif = $request->getParam('cif');
   $nombre = $request->getParam('nombre');
   $telefono = $request->getParam('telefono');
   $email = $request->getParam('email');
   $direccion = $request->getParam('direccion');
   $activo = $request->getParam('activo'); 
  
  $sql = "UPDATE CENTROS SET
          CIF =:cif,
		  NOMBRE = :nombre,
          TLF = :telefono,
          EMAIL = :email,
          DIRECCION = :direccion,
          ACTIVO = :activo
        WHERE id = $id_centro";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
	  $resultado->bindParam(':cif', $cif);
    $resultado->bindParam(':nombre', $nombre);
    $resultado->bindParam(':telefono', $telefono);
    $resultado->bindParam(':email', $email);
    $resultado->bindParam(':direccion', $direccion);
    $resultado->bindParam(':activo', $activo);
    $resultado->execute();
    echo json_encode("Centro modificado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 
// DELETE borar cliente 
$app->delete('/centros/delete/{id}', function(Request $request, Response $response){
   $id_centro = $request->getAttribute('id');
   $sql = "DELETE FROM CENTROS WHERE ID = $id_centro";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
     $resultado->execute();
    if ($resultado->rowCount() > 0) {
      echo json_encode("Centro eliminado.");  
    }else {
      echo json_encode("No existe centro con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

$app->run();