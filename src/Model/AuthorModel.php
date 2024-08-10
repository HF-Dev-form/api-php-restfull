<?php

namespace App\Model;

class AuthorModel extends AbstractManager
{
    public const TABLE = 'author';

    public function getBooksByAuthor($authorName)
    {
        $statement = $this->pdo->prepare("SELECT books.id, books.name AS title FROM books JOIN author ON books.author = author.id WHERE author.name = :name");
        $statement->bindParam(':name', $authorName, \PDO::PARAM_STR);
        $statement->execute();
        $books = $statement->fetchAll();

        $formattedBooks = ["data" => []];
        foreach ($books as $book) {
            $formattedBooks["data"][] = [
                "id" => $book['id'],
                "type" => "book",
                "title" => $book['title']
            ];
        }

        return $formattedBooks;
    }
}
?>