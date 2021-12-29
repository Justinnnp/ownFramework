<?php

require_once ROOT_DIR . '/rb.php';
require_once ROOT_DIR . '/src/db/database.php';

class UserService
{
    public function validateLoggedIn()
    {
        $rows = R::getAll("SELECT token FROM sessions WHERE token = :token AND username = :username", [':token' => $_SESSION['token'], ':username' => $_SESSION['username']]);
        if (isset($_SESSION['token']) && $_SESSION['token'] === $rows[0]['token']) {
        } else {
            header('Location: /user/login');
            exit();
        }
    }
}
