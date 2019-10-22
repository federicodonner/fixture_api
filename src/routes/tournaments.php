<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get All configs
$app->get('/api/tournaments', function(Request $request, Response $response){
  $sql = "SELECT * FROM tournaments";
  try{
    // Get db object
    $db = new db();
    // Connect
    $db = $db->connect();

    $stmt = $db->query($sql);
    $tournaments = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;

    $tournamentsResponse = array('tournaments'=>$tournaments);
    $newResponse = $response->withJson($tournamentsResponse);
    return $newResponse;

  }catch(PDOException $e){
    echo '{"error":{"text": '.$e->getMessage().'}}';
  }
});

// Get All configs
$app->get('/api/tournaments/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM tournaments where id=$id";
  try{
    // Get db object
    $db = new db();
    // Connect
    $db = $db->connect();

    $stmt = $db->query($sql);
    $tournaments = $stmt->fetchAll(PDO::FETCH_OBJ);

$sql = "SELECT * FROM teams WHERE tournament = $id";

$stmt = $db->query($sql);
$teams = $stmt->fetchAll(PDO::FETCH_OBJ);

$tournaments[0]->teams = $teams;

    $tournamentsResponse = array('tournaments'=>$tournaments);
    $newResponse = $response->withJson($tournamentsResponse);
    return $newResponse;

  }catch(PDOException $e){
    echo '{"error":{"text": '.$e->getMessage().'}}';
  }
});



// Update configuration
$app->post('/api/tournaments', function(Request $request, Response $response){
  $name = $request->getParam('name');
  $max_teams = $request->getParam('max_teams');
  $points_per_win = $request->getParam('points_per_win');
  $points_per_draw = $request->getParam('points_per_draw');
  $points_per_lose = $request->getParam('points_per_lose');
  $max_round = $request->getParam('max_round');

  if(!$max_teams){
    $max_teams = 1000;
  }

$sql = "INSERT INTO tournaments (name, max_teams, points_per_win, points_per_draw, points_per_lose, active,max_round,current_round,started) VALUES (:name,:max_teams,:points_per_win,:points_per_draw,:points_per_lose,:active,:max_round,:current_round,:started)";

  try{
    // Get db object
    $db = new db();
    // Connect
    $db = $db->connect();

    $stmt = $db->prepare($sql);
    $true = true;
    $zero = 0;
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':max_teams', $max_teams);
    $stmt->bindParam(':active', $true);
    $stmt->bindParam(':points_per_win', $points_per_win);
    $stmt->bindParam(':points_per_draw', $points_per_draw);
    $stmt->bindParam(':points_per_lose', $points_per_lose);
    $stmt->bindParam(':max_round', $max_round);
    $stmt->bindParam(':current_round', $zero);
    $stmt->bindParam(':started', $zero);

    $stmt->execute();

    $newResponse = $response->withStatus(200);
    $body = $response->getBody();
    $body->write('{"status": "success","message": "tournament created", "tournament": "'.$name.'"}');
    $newResponse = $newResponse->withBody($body);
    return $newResponse;

  }catch(PDOException $e){
    echo '{"error":{"text": '.$e->getMessage().'}}';
  }
});
