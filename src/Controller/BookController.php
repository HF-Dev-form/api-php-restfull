<?php

namespace App\Controller;

use App\Model\BookModel;

class BookController
{
    private $bookModel;

    public function __construct()
    {
        $this->bookModel = new BookModel();
    }

    // GET /books/{id}
    public function getBook($id)
    {
        $book = $this->bookModel->getBookById($id);

        header('Content-Type: application/json');
        echo json_encode($book);
    }

    // POST /books
    public function createBook()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $title = $data['title'] ?? null;
        $author = $data['author'] ?? null;

        if ($title && $author) {
            $book = $this->bookModel->createBook($title, $author);
            if ($book) {
                header('Content-Type: application/json');
                echo json_encode($book);
                return;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
    }

    // PUT /books/{id}
    public function updateBook($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $title = $data['title'] ?? null;
        $author = $data['author'] ?? null;

        if ($title && $author) {
            $book = $this->bookModel->updateBook($id, $title, $author);
            if ($book) {
                header('Content-Type: application/json');
                echo json_encode($book);
                return;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
    }

    // DELETE /books/{id}
    public function deleteBook($id)
    {
        $success = $this->bookModel->deleteBook($id);

        header('Content-Type: application/json');
        if ($success) {
            echo json_encode(['message' => 'Book deleted successfully']);
        } else {
            echo json_encode(['error' => 'Book not found']);
        }
    }

    // GET /books
    public function getBooks()
    {
        $books = $this->bookModel->getAllBooks();

        header('Content-Type: application/json');
        echo json_encode($books);
    }
}
?>