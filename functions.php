<?php
require_once('connection.php');
session_start();

function e($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function setToken()
{
    $_SESSION['token'] = sha1(uniqid(mt_rand(), true));
}

function checkToken($token)
{
    if (empty($_SESSION['token']) || ($_SESSION['token'] !== $token)) {
        $_SESSION['err'] = '不正な操作です';
        redirectToPostedPage();
    }
}

function unsetError()
{
    $_SESSION['err'] = '';
}

function redirectToPostedPage()
{
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

function getTodoList()
{
    return getAllRecords();
}

function getSelectedTodo($id)
{
    return getTodoTextById($id); 
}

function savePostedData($post)
{
    checkToken($post['token']);
    validate($post);
    $path = getRefererPath();
    switch ($path) {
        case '/new.php':
            createTodoData($post['todo']);
            break;
        case '/edit.php':
            updateTodoData($post);
            break;
        case '/index.php':
            deleteTodoData($post['id']);
            break;
        default:
            break;
    }
}

function getRefererPath()
{
    $urlArray = parse_url($_SERVER['HTTP_REFERER']);
    return $urlArray['path'];
}

function validate($post)
{
    if (isset($post['todo']) && $post['todo'] === '') {
        $_SESSION['err'] = '入力がありません';
        redirectToPostedPage();
    }
}
