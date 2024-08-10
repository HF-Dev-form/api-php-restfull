<?php

require_once '../config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Router.php'; 

$router = new Router();

// Define routes
$router->get('/books/{id}', 'BookController@getBook');
$router->get('/books', 'BookController@getBooks');
$router->get('/author/{name}/books', 'AuthorController@getBooksByAuthor');
$router->post('/books', 'BookController@createBook');
$router->put('/books/{id}', 'BookController@updateBook');
$router->delete('/books/{id}', 'BookController@deleteBook');

// Dispatch the request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);