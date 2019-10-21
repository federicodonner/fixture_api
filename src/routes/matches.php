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
    $score_team_1 = $request->getParam('score_team_1');
    $score_team_2 = $request->getParam('score_team_2');
    $winner = $request->getParam('winner');

    // Verify that the information is present
    if ($team_1 && $team_2 && $winner) {
        // Verify that the email has an email format



            try {

                    // Store the information in the database
                    $sql = "INSERT INTO matches (team_1, team_2, score_team_1, score_team_2, winner) VALUES (:team_1,:team_2,:score_team_1,:score_team_2,:winner)";

                    // Get db object
                    $db = new db();
                    // Connect
                    $db = $db->connect();

                    $stmt = $db->prepare($sql);

                    $zero = 0;

                    $stmt->bindparam(':team_1', $team_1);
                    $stmt->bindparam(':team_2', $team_2);
                    $stmt->bindparam(':winner', $winner);
                    if($score_team_1){
                      $stmt->bindparam(':score_team_1', $score_team_1);
                    }else{
                      $stmt->bindparam(':score_team_1', $zero);
                    }

                    if($score_team_1){
                      $stmt->bindparam(':score_team_2', $score_team_2);
                    }else{
                      $stmt->bindparam(':score_team_2', $zero);
                    }

                    $stmt->execute();

                    $sql = "SELECT tournament FROM teams WHERE id in ($team_1, $team_2)";
                    $stmt = $db->query($sql);
                    $tournaments = $stmt->fetchAll(PDO::FETCH_OBJ);



                    if(!calculatePoints($tournaments[0]->tournament)){
                      return respondWithError($response, 'Problem calculating points', 401);
                    }else{

                    $newResponse = $response->withStatus(200);
                    $body = $response->getBody();
                    $body->write('{"status": "success","message": "match added"}');
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


// // Update product
// $app->put('/api/usuario/{id}', function (Request $request, Response $response) {
//     $params = $request->getBody();
//     if ($request->getHeaders()['HTTP_AUTHORIZATION']) {
//         $access_token = $request->getHeaders()['HTTP_AUTHORIZATION'][0];
//         $access_token = explode(" ", $access_token)[1];
//         // Find the access token, if a user is returned, post the products
//         if (!empty($access_token)) {
//             $user_found = verifyToken($access_token);
//             if (!empty($user_found)) {
//                 $id = $request->getAttribute('id');
//
//                 $price_s = $request->getParam('price_s');
//                 $price_l = $request->getParam('price_l');
//                 $menuMonday = $request->getParam('menuMonday');
//                 $menuTuesday = $request->getParam('menuTuesday');
//                 $menuWednesday = $request->getParam('menuWednesday');
//                 $menuThursday = $request->getParam('menuThursday');
//                 $menuFriday = $request->getParam('menuFriday');
//                 $menuSaturday = $request->getParam('menuSaturday');
//                 $menuSunday =  $request->getParam('menuSunday');
//
//                 $sql = "UPDATE almuerzos SET
//         price_s = :price_s,
//         price_l = :price_l,
//         menuMonday = :menuMonday,
//         menuTuesday = :menuTuesday,
//         menuWednesday = :menuWednesday,
//         menuThursday = :menuThursday,
//         menuFriday = :menuFriday,
//         menuSaturday = :menuSaturday,
//         menuSunday = :menuSunday
//         WHERE id = $id";
//
//                 try {
//                     // Get db object
//                     $db = new db();
//                     // Connect
//                     $db = $db->connect();
//
//                     $stmt = $db->prepare($sql);
//
//                     $stmt->bindParam(':price_s', $price_s);
//                     $stmt->bindParam(':price_l', $price_l);
//                     $stmt->bindParam(':menuMonday', $menuMonday);
//                     $stmt->bindParam(':menuTuesday', $menuTuesday);
//                     $stmt->bindParam(':menuWednesday', $menuWednesday);
//                     $stmt->bindParam(':menuThursday', $menuThursday);
//                     $stmt->bindParam(':menuFriday', $menuFriday);
//                     $stmt->bindParam(':menuSaturday', $menuSaturday);
//                     $stmt->bindParam(':menuSunday', $menuSunday);
//
//                     $stmt->execute();
//
//                     echo('{"notice":{"text":"product updated"}}');
//                 } catch (PDOException $e) {
//                     echo '{"error":{"text": '.$e->getMessage().'}}';
//                 }
//             } else {
//                 return respondWithError($response, 'Error de login, usuario no encontrado');
//             }
//         } else {
//             return respondWithError($response, 'Error de login, falta access token');
//         }
//     } else {
//         return respondWithError($response, 'Error de encabezado HTTP');
//     }
// });
