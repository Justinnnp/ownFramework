<?php
define("ROOT_DIR", realpath(__DIR__ . "/.."));

require_once ROOT_DIR . '/vendor/autoload.php';
require_once ROOT_DIR . '/src/db/database.php';
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

session_start();

if (isset($_GET['url'])) {
    $url = explode("/", $_GET['url']);
}
if (!empty($url) && isset($url[0])) {
    $controller = ucfirst($url[0]) . "Controller";

    if (file_exists(ROOT_DIR . "/src/controllers/$controller.class.php")) {
        require_once ROOT_DIR . "/src/controllers/$controller.class.php";
        $con = new $controller();
        $method = ($url[1] ?? "index") . $_SERVER["REQUEST_METHOD"];

        if (method_exists($controller, $method)) {
            $con->$method() . "<br>";
        } else {
            include(ROOT_DIR . '/src/errors/404.php');
        }
    } else {
        include(ROOT_DIR . '/src/errors/404.php');
    }
} else {
    require_once ROOT_DIR . '/src/controllers/HomeController.class.php';
    (new HomeController())->indexGET();
}

function loadView($renderFile, $params = [])
{
    $loader = new FilesystemLoader(realpath(ROOT_DIR . '/src/views'));
    $twig = new Environment($loader);
    echo $twig->render($renderFile, $params);
}
