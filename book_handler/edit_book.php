<?php
require_once('../db.php');

$isbn = '';
$author = '';
$title = '';
$price = '';
$category = '';

if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];

    if (!isset($_POST['submit'])) {
        $query = "SELECT b.isbn, b.author, b.title, b.price, c.name AS category_name 
                    FROM books b
                    INNER JOIN categories AS c ON b.categoryid = c.categoryid
                    WHERE b.isbn='" . $isbn . "'";
        $result = $db->query($query);
        if (!$result) {
            die("Could not query the database: <br />" . $db->error);
        } else {
            while ($row = $result->fetch_object()) {
                $isbn = $row->isbn;
                $author = $row->author;
                $title = $row->title;
                $price = $row->price;
                $category = $row->category_name;
            }
        }
    } else {
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
            $query = "UPDATE books 
                        SET isbn = '" . $isbn . "', 
                            author = '" . $author . "',
                            title = '" . $title . "',
                            price = '" . $price . "',
                            categoryid = (SELECT categoryid FROM categories WHERE name = '" . $category . "')
                        WHERE isbn = '" . $isbn . "'
                    ";
            $result = $db->query($query);
            if (!$result) {
                die("Could not query the database: <br />" . $db->error . '<br>Query: ' . $query);
            } else {
                $db->close();
                header('Location: ../index.php');
            }
        }
    }
}
?>
<?php include('./header.php') ?>
<br>
<div class="card mt-4">
    <div class="card-header">Edit Book Data</div>
    <div class="card-body">
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?isbn=' . $isbn ?>" method="post" autocomplete="on">
            <div class="form-group">
                <label for="isbn">ISBN:</label>
                <input type="text" class="form-control" id="isbn" name="isbn" value="<?= $isbn; ?>">
                <div class="error">
                    <?php if (isset($error_isbn))
                        echo $error_isbn ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="author">Author:</label>
                    <input type="text" class="form-control" id="author" name="author" value="<?= $author; ?>">
                <div class="error">
                    <?php if (isset($error_author))
                        echo $error_author ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= $title; ?>">
                <div class="error">
                    <?php if (isset($error_title))
                        echo $error_title ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?= $price; ?>">
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
                            $isSelected = ($category == $categoryName) ? 'selected' : '';

                            echo "<option value=\"$categoryName\" $isSelected>$categoryName</option>";
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
                <a href="../index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
<?php include('./footer.php') ?>
<?php
$db->close();
?>