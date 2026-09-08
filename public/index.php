<?php
session_set_cookie_params(36000);
session_start();

require_once 'vendor/autoload.php'; 
require_once '../framework/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('../views'); 
$twig = new \Twig\Environment($loader, [
    "debug" => true
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

$pdo = new PDO("mysql:host=localhost;dbname=planet_space;charset=utf8", "root", "");

$router = new Router($twig, $pdo);
$middlewareLogin = new LoginRequiredMiddleware();

$router->add("/login", LoginController::class);
$router->add("/logout", LogoutController::class);

$router->add("/", MainController::class)->middleware($middlewareLogin);
$router->add("/search", SearchController::class)->middleware($middlewareLogin);
$router->add("/space-object/(?P<id>\d+)/?", CosmoController::class)->middleware($middlewareLogin);

$router->add("/space-object/create", CosmoObjectCreateController::class)->middleware($middlewareLogin);
$router->add("/space-type/create", CosmoTypeCreateController::class)->middleware($middlewareLogin);
$router->add("/space-object/(?P<id>\d+)/edit", CosmoObjectUpdateController::class)->middleware($middlewareLogin);
$router->add("/space-object/(?P<id>\d+)/delete", CosmoObjectDeleteController::class)->middleware($middlewareLogin);

$router->get_or_default(Controller404::class);