<?php

namespace App\Model;

use App\Model\AuthorModel;

class BookModel extends AbstractManager
{
    private $authorModel;
    public const TABLE = 'books';

    public function __construct()
    {
        parent::__construct();
        $this->authorModel = new AuthorModel();
    }

    public function createBook($title, $authorId)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . static::TABLE . " (name, author) VALUES (:title, :authorId)");
        $statement->bindParam(':title', $title, \PDO::PARAM_STR);
        $statement->bindParam(':authorId', $authorId, \PDO::PARAM_INT);

        if ($statement->execute()) {
            return $this->selectOneById($this->pdo->lastInsertId());
        } else {
            return false;
        }
    }

    public function updateBook($bookId, $title, $authorId)
    {
        $statement = $this->pdo->prepare("UPDATE " . static::TABLE . " SET name=:title, author=:authorId WHERE id=:id");
        $statement->bindParam(':title', $title, \PDO::PARAM_STR);
        $statement->bindParam(':authorId', $authorId, \PDO::PARAM_INT);
        $statement->bindParam(':id', $bookId, \PDO::PARAM_INT);

        if ($statement->execute()) {
            return $this->selectOneById($bookId);
        } else {
            return false;
        }
    }

    public function getBookById($bookId)
    {
        $bookData = $this->selectOneById($bookId);

        if ($bookData) {
            $authorData = $this->authorModel->selectOneById($bookData['author']);

            return [
                "data" => [
                    "id" => $bookData['id'],
                    "type" => "book",
                    "title" => $bookData['name'],
                    "author" => [
                        "id" => $authorData['id'],
                        "name" => $authorData['name']
                    ]
                ]
            ];
        } else {
            return ["error" => "Book not found"];
        }
    }

    public function getAllBooks()
    {
        $booksData = $this->selectAll();

        if ($booksData) {
            $formattedBooks = ["data" => []];

            foreach ($booksData as $bookData) {
                $authorData = $this->authorModel->selectOneById($bookData['author']);

                $formattedBooks["data"][] = [
                    "id" => $bookData['id'],
                    "type" => "book",
                    "title" => $bookData['name'],
                    "author" => [
                        "id" => $authorData['id'],
                        "name" => $authorData['name']
                    ]
                ];
            }
            return $formattedBooks;
        } else {
            return ["error" => "No books found"];
        }
    }

    public function deleteBook($bookId)
    {
        $this->delete($bookId);
        return true;
    }
}
?>