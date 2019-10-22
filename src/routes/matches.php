<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get all the matches
$app->get('/api/matches', function (Request $request, Response $response) {
$tournament = $request->getQueryParam('tournament');
if($tournament){


$sql = "SELECT * from matches WHERE (team_1 in ( SELECT id FROM teams where tournament = $tournament ) or team_2 in ( SELECT id FROM teams where tournament = $tournament))";

}else{
  $sql = "SELECT * FROM matches";
}
  try {
      // Get db object
      $db = new db();
      // Connect
      $db = $db->connect();

      $stmt = $db->query($sql);
      $matches = $stmt->fetchAll(PDO::FETCH_OBJ);
      $db = null;

      $matchesResponse = array('matches'=>$matches);
      $newResponse = $response->withJson($matchesResponse);
      return $newResponse;
  } catch (PDOException $e) {
      echo '{"error":{"text": '.$e->getMessage().'}}';
  }
});


// Add match
$app->post('/api/matches', function (Request $request, Response $response) {

    // Get the user's details from the request body
    $team_1 = $request->getParam('team_1');
    $team_2 = $request->getParam('team_2');
    $tournament = $request->getParam('tournament');

    // Verify that the information is present
    if ($team_1 && $team_2) {

        try {

$sql_round = "SELECT current_round FROM tournaments where id = $tournament";

// Get db object
$db = new db();
// Connect
$db = $db->connect();

$stmt_round = $db->query($sql_round);
$rounds = $stmt_round->fetchAll(PDO::FETCH_OBJ);



          // Store the information in the database
          $sql = "INSERT INTO matches (team_1, team_2,tournament,round) VALUES (:team_1,:team_2,:tournament,:round)";


          $stmt = $db->prepare($sql);

          $zero = 0;
          $stmt->bindparam(':team_1', $team_1);
          $stmt->bindparam(':team_2', $team_2);
          $stmt->bindparam(':tournament', $tournament);
          $stmt->bindparam(':round', $rounds[0]->current_round);

          $stmt->execute();



          $newResponse = $response->withStatus(200);
          $body = $response->getBody();
          $body->write('{"status": "success","message": "match added"}');
          $newResponse = $newResponse->withBody($body);
          return $newResponse;


      } catch (PDOException $e) {
          echo '{"error":{"text": '.$e->getMessage().'}}';
      }
    } else { // if ($name && $username && $password && $email) {
        return respondWithError($response, 'Incorrect fields', 401);
    }
});


// Report winner
$app->put('/api/matches', function (Request $request, Response $response) {

    // Get the user's details from the request body

    $match_id = $request->getParam('match_id');
    $winner = $request->getParam('winner');
    $score_team_1 = $request->getParam('score_team_1');
    $score_team_2 = $request->getParam('score_team_2');

    // Verify that the information is present
    if ($match_id && $winner) {

        try {

          $sql = "UPDATE matches SET
                  winner = :winner,
                  score_team_1 = :score_team_1,
                  score_team_2 = :score_team_2
                  WHERE id = $match_id";

          // Get db object
          $db = new db();
          // Connect
          $db = $db->connect();

          $stmt = $db->prepare($sql);

          $zero = 0;
          $stmt->bindparam(':winner', $winner);

          if($score_team_1){
            $stmt->bindparam(':score_team_1', $score_team_1);
          }else{
            $stmt->bindparam(':score_team_1', $zero);
          }

          if($score_team_2){
            $stmt->bindparam(':score_team_2', $score_team_2);
          }else{
            $stmt->bindparam(':score_team_2', $zero);
          }

          $stmt->execute();

          $sql_calculate = "SELECT tournament FROM matches WHERE id = $match_id";
          $stmt_calculate = $db->query($sql_calculate);
          $tournaments = $stmt_calculate->fetchAll(PDO::FETCH_OBJ);

          if(!calculatePoints($tournaments[0]->tournament)){
            return respondWithError($response, 'Problem calculating points', 401);
          }else{

          $newResponse = $response->withStatus(200);
          $body = $response->getBody();
          $body->write('{"status": "success","message": "match updated"}');
          $newResponse = $newResponse->withBody($body);
          return $newResponse;
        }

      } catch (PDOException $e) {
          echo '{"error":{"text": '.$e->getMessage().'}}';
      }
    } else { // if ($name && $username && $password && $email) {
        return respondWithError($response, 'Incorrect fields', 401);
    }
});
