<?php

require_once ROOT_DIR . '/rb.php';
require_once ROOT_DIR . '/src/db/database.php';
require_once ROOT_DIR . '/src/services/UserService.class.php';

class TodoController
{
    public function indexGET()
    {
        (new UserService())->validateLoggedIn();

        $sort = isset($_GET['sort']) ? ($_GET['sort'] === 'DESC' ? 'DESC' : 'ASC') : 'DESC';
        
        $todos = R::getAll("SELECT * FROM todos WHERE author = :author ORDER BY todo $sort", [':author' => $_SESSION['username']]);
        $convertedTodos = R::convertToBeans("todo", $todos);
        $toggle = $sort == "DESC" ? "ASC" : "DESC";
        
        loadView('todoIndex.twig', ['todos' => $convertedTodos, 'toggle' => $toggle]);
    }

    public function addGET()
    {
        loadView('todoAdd.twig');
    }

    public function addPOST()
    {
        $author = $_POST['author'];
        $todoItem = $_POST['todo'];
        $checked = 0;

        $todo = R::dispense('todos');
        $todo->author = $author;
        $todo->todo = $todoItem;
        $todo->checked = $checked;
        R::store($todo);
        header("Location: /todo");
    }

    public function editGET()
    {
        $todos = R::getAll("SELECT * FROM todos WHERE author = :author", [':author' => $_SESSION['username']]);
        $convertedTodos = R::convertToBeans("todo", $todos);
        loadView('todoEdit.twig', ['todos' => $convertedTodos]);
    }
    public function editPOST()
    {
        $todoItem = $_POST['todo'];
        $id = $_POST['id'];
        $checked = $_POST['checked'] ? 1 : 0;

        if ($checked) {
            R::getAll("UPDATE todos SET checked = :checked WHERE id = :id", [':checked' => $checked, ':id' => $id]);
        } else {
            R::getAll("UPDATE todos SET checked = :checked WHERE id = :id", [':checked' => $checked, ':id' => $id]);
        }
        R::getAll("UPDATE todos SET todo = :todo WHERE id = :id", [':todo' => $todoItem, ':id' => $id]);
        header("Location: /todo");
    }
}