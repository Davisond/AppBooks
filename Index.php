<?php

class Index {

    // Método para pegar todos os livros
    public function getBooks(){
        $url = 'http://localhost/api/books';
               
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $books = json_decode($response, true);
        return $books ? $books : null;

    }

    // Método para criar um livro
    public function createBook($title, $author, $review){
        $url = 'http://localhost/api/books/';
        $data = array('title' => $title, 'author' => $author, 'review' => $review);
        $dataString = json_encode($data); 
        $ch = curl_init($url); 
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);    
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch); 
        curl_close($ch); 
        return $response;
    }

    // Método para deletar um livro
    public function deleteBook($id){
        $url = 'http://localhost/api/books/' . $id;
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); 
        $response = curl_exec($ch); 
        curl_close($ch); 
    }

    // Método para atualizar um livro
    public function updateBook($id, $title, $author, $review){
        $url = 'http://localhost/api/books/' . $id;

        $data = array('title' => $title, 'author' => $author, 'review' => $review);
        $dataString = json_encode($data); 
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
            'Content-Type: application/x-www-form-urlencoded',
        ));
        $response = curl_exec($ch); 
        curl_close($ch); 
    }
}


$index = new Index();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $review = $_POST['review'];
        $index->createBook($title, $author, $review);
        echo "<p>Created!</p>";

    }

    if (isset($_POST['delete'])) {
        $id = $_POST['delete_id'];
        $index->deleteBook($id);
        echo "<p>Deleted successfully!</p>";
    }

    if (isset($_POST['edit'])) {
        $id = $_POST['edit_id'];
        $title = $_POST['edit_title'];
        $author = $_POST['edit_author'];
        $review = $_POST['edit_review'];
        $index->updateBook($id, $title, $author, $review);
        echo "<p>Edited successfully!</p>";
    }
}


$books = $index->getBooks();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Review</title>
</head>
<body>

<h1>Book Review</h1>

<!-- Exibindo a lista de livros -->
<ul>
    <?php
    if ($books) {
        foreach ($books as $book) {
            if ($book) {
                echo "<li>Id: {$book['id']}, Title: {$book['title']}, Author: {$book['author']}, Review: {$book['review']}</li>";
            } else {
                echo '<li>Book not found</li>';
            }
        }
    } else {
        echo '<li>No books found</li>';
    }
    ?>
</ul>


<h2>Register a book</h2>
<form method="POST" action="index.php">
    <label for="title">Title: </label>
    <input type="text" name="title" required><br>
    <label for="author">Author: </label>
    <input type="text" name="author" required><br>
    <label for="review">Review: </label>
    <textarea name="review" required></textarea><br>
    <button type="submit" name="create">Register your new book</button>
</form>


<h2>Delete a Book</h2>
<form method="POST" action="index.php">
    <label for="delete_id">Book ID: </label>
    <input type="text" name="delete_id" required><br>
    <button type="submit" name="delete">Delete</button>
</form>


<h2>Edit a Book</h2>
<form method="POST" action="index.php">
    <label for="edit_id">Book ID: </label>
    <input type="text" name="edit_id" required><br>
    <label for="edit_title">Title: </label>
    <input type="text" name="edit_title"><br>
    <label for="edit_author">Author: </label>
    <input type="text" name="edit_author"><br>
    <label for="edit_review">Review: </label>
    <textarea name="edit_review"></textarea><br>
    <button type="submit" name="edit">Edit</button>
</form>

</body>
</html>
