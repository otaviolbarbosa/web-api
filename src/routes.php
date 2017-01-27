<?php
// Routes

// $app->get('/[{name}]', function ($request, $response, $args) {
//     // Sample log message
//     $this->logger->info("Slim-Skeleton '/' route");

//     // Render index view
//     return $this->renderer->render($response, 'index.phtml', $args);
// });


$app->get('/usuarios', function($request, $response, $args) {
  $stmt = getConn()->query("SELECT * FROM usuarios");
  $usuarios = $stmt->fetchObject(PDO::FETCH_OBJ);
  return $response->withJson($usuarios); // "{usuarios:".json_encode($usuarios)."}";
});

$app->get('/usuarios/{id}', function($request, $response, $args) {
  $sql = "SELECT * FROM usuarios WHERE id=:id";
  $stmt = getConn()->prepare($sql);
  $stmt->bindParam("id",$args['id']);
  $stmt->execute();
  $usuario = $stmt->fetchObject();
  return $response->withJson($usuario); // "{usuarios:".json_encode($usuarios)."}";
});

$app->post('/usuarios/auth', function($request, $response, $args) {
  $login = $request->getParsedBody();
  $login['password'] = md5($login['password']);
  $sql = "SELECT * FROM usuarios WHERE username=:username AND password=:password";
  $stmt = getConn()->prepare($sql);
  $stmt->bindParam("username",$login['username']);
  $stmt->bindParam("password",$login['password']);
  $stmt->execute();
  $usuario = $stmt->fetchObject();

  if($usuario)
    return $response->withJson($usuario); // "{usuarios:".json_encode($usuarios)."}";
  return $response->withStatus(403)->withJson(['mensagem'=>'Usuario não encontrado']);
});
