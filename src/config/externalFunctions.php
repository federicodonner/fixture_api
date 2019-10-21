<?php

use \Psr\Http\Message\ResponseInterface as Response;

// Return the login record from the token, or an empty array if none exists
  function verifyToken(String $access_token)
  {
      if (!empty($access_token)) {
          $sql = "SELECT * FROM logins WHERE token = '$access_token'";
          try {
              // Get db object
              $db = new db();
              // Connect
              $db = $db->connect();
              $stmt = $db->query($sql);
              $users = $stmt->fetchAll(PDO::FETCH_OBJ);
              return $users;
          } catch (PDOException $e) {
              echo '{"error":{"text": '.$e->getMessage().'}}';
          }
      } else {
          return [];
      }
  };

// Return a response with a 401 not allowed error.
 function respondWithError(Response $response, String $errorText, Int $status)
 {
     $newResponse = $response->withStatus($status);
     $body = $response->getBody();
     $body->write('{"status": "error","message": "'.$errorText.'"}');
     $newResponse = $newResponse->withBody($body);
     return $newResponse;
 };




function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
 {
     $pieces = [];
     $max = mb_strlen($keyspace, '8bit') - 1;
     for ($i = 0; $i < $length; ++$i) {
         $pieces []= $keyspace[random_int(0, $max)];
     }
     return implode('', $pieces);
 };

function calculatePoints($tournament){
  try {
   // Get db object
   $db = new db();
   // Connect
   $db = $db->connect();

    $sql_tournament = "SELECT * FROM tournaments where id = $tournament";
    $stmt = $db->query($sql_tournament);
    $tournaments = $stmt->fetchAll(PDO::FETCH_OBJ);

    $points_per_win = $tournaments[0]->points_per_win;
    $points_per_draw = $tournaments[0]->points_per_draw;
    $points_per_lose = $tournaments[0]->points_per_lose;

    $sql = "SELECT * FROM teams WHERE tournament = $tournament";
    $a = $sql;
    $stmt = $db->query($sql);
    $teams = $stmt->fetchAll(PDO::FETCH_OBJ);

    forEach($teams as $team){
      $team_id = $team->id;
      $team_points = 0;
      $team_matches = 0;
      $sql_matches = "SELECT * FROM matches WHERE team_1 = $team_id OR team_2 = $team_id";
      $stmt_matches = $db->query($sql_matches);
      $matches = $stmt_matches->fetchAll(PDO::FETCH_OBJ);

      forEach($matches as $match){
        if($match->winner == $team_id){
          $team_points = $team_points + $points_per_win;
        }else if($match->winner == '-99'){
          $team_points = $team_points + $points_per_draw;
        }else{
          $team_points = $team_points + $points_per_lose;
        }
        $team_matches = $team_matches + 1;
      }

      $sql_update = "UPDATE teams SET
                            points = $team_points,
                            rounds_played = $team_matches
                            WHERE id = $team_id";
                            $stmt_update = $db->prepare($sql_update);
                          $stmt_update->execute();
    }

return true;
   } catch (PDOException $e) {
       return false;
   }



 }
