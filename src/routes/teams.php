<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Get all the users
$app->get('/api/teams', function (Request $request, Response $response) {
  $tournament = $request->getQueryParam('tournament');
  if($tournament){
    $sql = "SELECT * FROM teams WHERE tournament = $tournament";
  }else{
    $sql = "SELECT * FROM teams";
  }
  try {
      // Get db object
      $db = new db();
      // Connect
      $db = $db->connect();

      $stmt = $db->query($sql);
      $teams = $stmt->fetchAll(PDO::FETCH_OBJ);
      $db = null;

      $teamsResponse = array('teams'=>$teams);
      $newResponse = $response->withJson($teamsResponse);
      return $newResponse;
  } catch (PDOException $e) {
      echo '{"error":{"text": '.$e->getMessage().'}}';
  }
});




// Get single user
$app->get('/api/teams/{id}', function (Request $request, Response $response) {

  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM teams WHERE id = $id";

  try {
      // Get db object
      $db = new db();
      // Connect
      $db = $db->connect();

      $stmt = $db->query($sql);
      $team = $stmt->fetchAll(PDO::FETCH_OBJ);


      // Add the users array inside an object
      if (!empty($team)) {

        $sql_matches = "SELECT * FROM matches WHERE (team_1 = $id or team_2 = $id)";
        $stmt_matches = $db->query($sql_matches);
        $matches = $stmt_matches->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $team[0]->matches = $matches[0];

          $teamsResponse = $team[0];
          $newResponse = $response->withJson($teamsResponse);
          return $newResponse;
      } else {
          return respondWithError($response, 'Incorrect id', 401);
      }
  } catch (PDOException $e) {
      echo '{"error":{"text": '.$e->getMessage().'}}';
  }

});



// Add team
$app->post('/api/teams', function (Request $request, Response $response) {

    // Get the user's details from the request body
    $name = $request->getParam('name');
    $player_1 = $request->getParam('player_1');
    $player_2 = $request->getParam('player_2');
    $tournament = $request->getParam('tournament');

    // Verify that the information is present
    if ($name && $player_1 && $player_2 && $tournament) {
        // Verify that the email has an email forma

            // Check that there is no other users's with the same username
            $sql = "SELECT name FROM teams where name = '$name' and tournament = $tournament";

            try {
                // Get db object
                $db = new db();
                // Connect
                $db = $db->connect();

                $stmt = $db->query($sql);
                $team_check = $stmt->fetchAll(PDO::FETCH_OBJ);

                if (empty($team_check)) {


                    // Store the information in the database
                    $sql = "INSERT INTO teams (name, player_1, player_2, points, rounds_played, tournament) VALUES (:name,:player_1,:player_2,:points,:rounds_played,:tournament)";

                    // Get db object
                    $db = new db();
                    // Connect
                    $db = $db->connect();

                    $stmt = $db->prepare($sql);

                    $zero = 0;

                    $stmt->bindparam(':name', $name);
                    $stmt->bindparam(':player_1', $player_1);
                    $stmt->bindparam(':player_2', $player_2);
                    $stmt->bindparam(':points', $zero);
                    $stmt->bindparam(':rounds_played', $zero);
                    $stmt->bindparam(':tournament', $tournament);

                    $stmt->execute();

                    $newResponse = $response->withStatus(200);
                    $body = $response->getBody();
                    $body->write('{"status": "success","message": "team added", "user": "'.$name.'"}');
                    $newResponse = $newResponse->withBody($body);
                    return $newResponse;
                } else { // if (empty($user)) {
                    return respondWithError($response, 'User already exists', 401);
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
