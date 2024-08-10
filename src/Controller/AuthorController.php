<?php

namespace App\Controller;

use App\Model\AuthorModel;

class AuthorController
{
    private $authorModel;

    public function __construct()
    {
        $this->authorModel = new AuthorModel();
    }

    // GET /author/{name}/books
    public function getBooksByAuthor($name)
    {
        $author_name = strtolower(str_replace('-', ' ', $name));
        $books = $this->authorModel->getBooksByAuthor($author_name);

        header('Content-Type: application/json');
        echo json_encode($books);
    }
}
?>