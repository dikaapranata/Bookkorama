<?php include('../header.php') ?>
<div class="card mt-5">
    <div class="card-header">Books Data</div>
    <div class="card-body">
        <a href="add_book.php" class="btn btn-primary mb-4">+ Add Book Data</a>
        <br>
        <table class="table table-striped">
            <tr>
                <th>ISBN</th>
                <th>Title</th>
                <th>Category</th>
                <th>Author</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            <?php
            // Include our login information
            require_once('../lib/db_login.php');

            // TODO 1: Tuliskan dan eksekusi query
            $query = 'SELECT 
                            books.isbn, 
                            books.title, 
                            categories.name AS category_name, 
                            books.author, 
                            books.price 
                        FROM books 
                        INNER JOIN categories ON books.categoryid = categories.categoryid
                        ORDER BY books.isbn
                    ';
            $result = $db->query($query);
            if (!$result) {
                die('Could not query the database: <br/>' . $db->error . '<br>Query:' . $query);
            }

            $i = 1;
            while ($row = $result->fetch_object()) {
                echo '<tr>';
                echo '<td>' . $row->isbn . '</td>';
                echo '<td>' . $row->title . '</td>';
                echo '<td>' . $row->category_name . '</td>';
                echo '<td>' . $row->author . '</td>';
                echo '<td>' . $row->price . '</td>';
                echo '<td>';
                echo '<a class="btn btn-warning btn-sm" href="edit_book.php?isbn='
                    . $row->isbn . '">Edit</a>&nbsp;&nbsp';
                echo '<a class="btn btn-danger btn-sm" href="confirm_delete_book.php?isbn='
                    . $row->isbn . '">Delete</a>';
                echo '</td>';
                // echo '<td><a class="btn btn-primary btn-sm" href="show_cart.php?id=' . $row->isbn . '">Add to Cart</a></td>';
                echo '</tr>';
                $i++;
            }
            echo '</table>';
            echo '<br />';
            echo 'Total Rows = ' . $result->num_rows;

            $result->free();
            $db->close();
            ?>
    </div>
</div>
<?php include('../footer.php') ?>