<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require '../src/config/db.php';

require '../src/config/externalFunctions.php';

$app = new \Slim\App;

// Customer routes
require '../src/routes/teams.php';
require '../src/routes/matches.php';
require '../src/routes/tournaments.php';
require '../src/routes/cors.php';


$app->run();

// echo('hla');
