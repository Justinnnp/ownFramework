<?php

class HomeController
{
    public function indexGET()
    {
        if (isset($_SESSION['token'])) {
            loadView('userLogout.twig');
        }

        loadView('index.twig');
    }
}
