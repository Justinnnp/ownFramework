<?php
require_once ROOT_DIR . '/rb.php';

try {
    R::setup('mysql:host=localhost;dbname=todos', 'root', '');
} catch (PDOException $e) {
    echo $e->getMessage();
}
