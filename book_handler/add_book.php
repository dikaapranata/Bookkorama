<?php
require_once('../db.php');

if (isset($_POST['submit'])) {
    $valid = TRUE;

    $isbn = test_input($_POST['isbn']);
    if ($isbn == '') {
        $error_isbn = 'ISBN is required';
        $valid = FALSE;
    }

    $author = test_input($_POST['author']);
    if ($author == '') {
        $error_author = 'Author is required';
        $valid = FALSE;
    }

    $title = test_input($_POST['title']);
    if ($title == '') {
        $error_title = 'Title is required';
        $valid = FALSE;
    }

    $price = test_input($_POST['price']);
    if ($price == '') {
        $error_price = 'Price is required';
        $valid = FALSE;
    }

    $category = $_POST['category'] ?? '';
    if ($category == '') {
        $error_category = 'Category is required';
        $valid = FALSE;
    }

    if ($valid) {
        // Kueri
        $query = "INSERT INTO books (isbn, author, title, price, categoryid)
            VALUES ('" . $isbn . "',
                    '" . $author . "',
                    '" . $title . "',
                    '" . $price . "',
                    (SELECT categoryid FROM categories WHERE name = '" . $category . "'))
        ";


        // Eksekusi kueri
        $result = $db->query($query);
        if (!$result) {
            die('Could not query the database: <br/>' . $db->error . '<br>Query:' . $query);
        } else {
            $db->close();
            header('Location: view_books.php');
        }
    }
}
?>
<?php include('./header.php') ?>
<br>
<div class="card mt-4">
    <div class="card-header">Add Book Data</div>
    <div class="card-body">
        <form action="add_book.php" method="POST" autocomplete="on">
            <div class="form-group">
                <label for="isbn">ISBN:</label>
                <input type="text" class="form-control" id="isbn" name="isbn">
                <div class="error">
                    <?php if (isset($error_isbn))
                        echo $error_isbn ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="author">Author:</label>
                    <input type="text" class="form-control" id="author" name="author">
                    <div class="error">
                    <?php if (isset($error_author))
                        echo $error_author ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" class="form-control" id="title" name="title">
                    <div class="error">
                    <?php if (isset($error_title))
                        echo $error_title ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01">
                    <div class="error">
                    <?php if (isset($error_price))
                        echo $error_price ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select name="category" id="category" class="form-control">
                        <option value="" selected disabled>--Select a Category--</option>
                        <?php
                    $query = 'SELECT name FROM categories';
                    $result = $db->query($query);

                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $categoryName = $row['name'];
                            echo "<option value=\"$categoryName\">$categoryName</option>";
                        }

                        $result->free_result();
                    } else {
                        echo 'Error:' . $db->error;
                    }
                    ?>
                </select>
                <div class="error">
                    <?php if (isset($error_category))
                        echo $error_category ?>
                    </div>
                </div>
                <br>
                <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
                <a href="view_books.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
<?php include('./footer.php') ?>
<?php
$db->close();
?>