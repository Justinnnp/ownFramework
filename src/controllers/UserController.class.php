<?php

require_once ROOT_DIR . '/rb.php';
require_once ROOT_DIR . '/src/db/database.php';
require_once ROOT_DIR . '/src/services/UserService.class.php';

class UserController
{
    public function loginGET()
    {
        if (isset($_SESSION['token'])) {
            loadView('userLogout.twig');
        }
        loadView('userLoginForm.twig');
    }
    public function loginPOST()
    {
        $password = $_POST['password'];
        $username = $_POST['username'];
        $rows = R::getAll("SELECT * FROM users WHERE password = :password AND username = :username", [':password' => $password, ':username' => $username]);

        if (count($rows) > 0) {
            $token = bin2hex(random_bytes(50));

            $_SESSION['token'] = $token;
            $_SESSION['username'] = $username;

            $session = R::dispense('sessions');
            $session->token = $_SESSION['token'];
            $session->username = $_SESSION['username'];
            R::store($session);

            header('Location: /');
            (new UserService())->validateLoggedIn();
        } else {
            echo "De username of password is fout ingevuld.";
        }
    }
    public function logoutPOST()
    {
        session_destroy();
        R::getAll("DELETE FROM sessions WHERE token = :token", [':token' => $_SESSION['token']]);
        header("Location: /");
    }
}
