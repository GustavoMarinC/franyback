<?php

require_once 'vendor/autoload.php';

$app = new \Slim\Slim();
$db = new mysqli('localhost', 'root', '','frany_bkack');

//configuracion HEADERS

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}


$app->get("/pruebas",function() use($app,$db){
    echo 'hola mundo desde slim php';
});
// Listar todas las imagenes de una tabla

$app->get('/fotos/:tabla',function($tabla) use($db,$app){
    $sql = 'SELECT * FROM `'.$tabla.'`';
    $query = $db->query($sql); 

    $productos = array();
while($producto = $query->fetch_assoc()){
    $productos[]=$producto;
}

$result = array(
    'status' => 'succes',
    'code' => 200,
    'data' => $productos
);

echo json_encode($result);

});


// devolver  un solo producto
$app->get('/producto/:id',function($id) use($db,$app){
    $sql='SELECT * FROM producto WHERE id = '.$id;
    $query=$db->query($sql);
    $result = array(
        'status'=> 'ERROR',
        'codigo'=> 404,
        'message' => 'producto no disponible'
    );
    if($query->num_rows == 1){
        $producto = $query->fetch_assoc();
        $result = array(
            'status'=> 'succes',
            'codigo'=> 200,
            'data' => $producto
        ); 
    }

    echo json_encode($result);
});

// eliminar  un producto
$app->get('/delete-producto/:id',function($id) use($db, $app){
    $sql = 'DELETE FROM producto WHERE id = '.$id;
    $query = $db->query($sql);

    if($query){
        $result = array(
            'status'=> 'succes',
            'codigo'=> 200,
            'message' => 'El producto se a eliminado'
        );
    }else{  
        $result = array(
            'status'=> 'ERROR',
            'codigo'=> 404,
            'message' => 'El producto no se a eliminado'
        );
    }

    echo json_encode($result);
});
// actualizar unproducto
$app->post('/update-producto/:id',function($id) use($db,$app){
    $json = $app->request->post('json');
    $data = json_decode($json, true);

    $sql = "UPDATE producto SET ".
           "nombre = '{$data["nombre"]}', ".
           "descripcion = '{$data["descripcion"]}', ";

    if(isset($data['imagen'])){
        $sql .= "imagen = '{$data["imagen"]}',";
    }
          
    $sql .= "precio = '{$data["precio"]}' WHERE id = {$id};";

    $query = $db->query($sql);

    if($query){
        $result = array(
            'status'=> 'success',
            'codigo'=> 200,
            'message' => 'El producto se actualizo'
        );
    }else{
        $result = array(
            'status'=> 'ERROR',
            'codigo'=> 404,
            'message' => 'El producto no se actualizo'
        );
    }
     echo json_encode($result);
});
//SUBIR UNA IMAGEN

$app->post('/upload-file',function()use($db,$app){
    $result = array(
        'status'=> 'ERROR',
        'codigo'=> 404,
        'message' => 'el archivo no a podido subirse'
    );

    if(isset($_FILES['uploads'])){
       $piramideUploader = new PiramideUploader();

       $upload = $piramideUploader->upload('image',"uploads", "uploads", array('image/jpeg','image/png', 'image/gif'));
       $file = $piramideUploader->getInfoFile();
       $file_name = $file['complete_name'];

       if(isset($upload)&&$upload["uploaded"]==false){
        $result = array(
            'status'=> 'ERROR',
            'codigo'=> 404,
            'message' => 'el archivo no a podido subirse'
        );
       }else{
        $result = array(
            'status'=> 'success',
            'codigo'=> 200,
            'message' => 'el archivo se a podido subirse',
            'file_name' => $file_name
        );
       }
    }

    echo json_encode($result);
});

//Guardar foto

$app->post("/guardar_foto",function() use($app,$db){
    $json = $app->request->post('json');
    $data = json_decode($json, true);

    if(!isset($data['nombre'])){
        $data['nombre']=null;
    }

    if(!isset($data['descripcion'])){
        $data['descripcion']=null;
    }
    if(!isset($data['imagen'])){
        $data['imagen']=null;
    }


    $query = "INSERT INTO {$data['tabla']} VALUES(null,".
            "'{$data['nombre']}',".
            "'{$data['descripcion']}',".
            "'{$data['imagen']}'".
            ");";

    $insert = $db ->query($query);

    $result = array(
        'status' => 'ERROR',
        'code' => 404,
        'messages' => 'No se inserto'
    );

    if($insert){
        $result = array(
            'status' => 'succes',
            'code' => 200,
            'messages' => 'Se inserto con exito'
        );
    }
    echo json_encode($result);
});
//login
$app->post('/login',function() use($db,$app){
    $json = $app->request->post('json');
    $data = json_decode($json,true);

    $email = "{$data["email"]}";
    $pass = "{$data["pass"]}";

   // $pass = "{$data["pass"]}";
 
    $sql = "SELECT id_user, email, pass, level FROM users WHERE email ='".$email."' AND pass ='".$pass."'";
    $query = $db->query($sql); 
    $result = array(
        'status'=> 'ERROR',
        'codigo'=> 404,
        'message' => 'usuario incorrecto'
    );
    if($query->fetch_assoc()){
        $usuario = $query->fetch_assoc();
        $result = array(
            'status'=> 'succes',
            'codigo'=> 200,
            'data' => $usuario
        ); 
    }
   
    echo json_encode($result);
 
});

$app->get('/delete-foto',function() use($db, $app){
    $json = $app->request->post('json');
    $data = json_decode($json,true);

    $sql = "DELETE FROM {$data['tabla']} WHERE id = {$data["id"]}";
    $query = $db->query($sql);

    if($query){
        $result = array(
            'status'=> 'succes',
            'codigo'=> 200,
            'message' => 'El producto se a eliminado'
        );
    }else{  
        $result = array(
            'status'=> 'ERROR',
            'codigo'=> 404,
            'message' => 'El producto no se a eliminado'
        );
    }

    echo json_encode($result);
});

$app->run();

