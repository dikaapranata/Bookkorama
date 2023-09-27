<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light custom-bg">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./index.php">Filter Buku</a>
                    <li class="nav-item">
                        <a class="nav-link" href="./data_order.php">Data Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./pembelian.php">Pembelian</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./view_order.php">View Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./search.php"><strong>Pencarian Buku</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./landingpage.php">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="card mt-4">
            <div class="card-header">Find Book</div>
            <div class="card-body">
                <form method="post" action="search.php">
                    <div class="form-group">
                        <label for="category" class="fw-bold">Select Cateogry</label>
                        <select name="category" class="form-control" id="category">
                            <option value="0">Select Category</option>
                            <option value="1">Computer</option>
                            <option value="2">Design</option>
                            <option value="3">Fiction</option>
                            <option value="4">Cooking</option>
                            <option value="5">Architecture</option>
                        </select>
                    </div>
                    <br>

                    <div class="form-group">
                        <label for="search" class="fw-bold">Search</label>
                        <input type="search" id="search" name="search" class="form-control rounded"
                            placeholder="Input ISBN / Author / Title" aria-label="Search"
                            aria-describedby="search-addon" />
                    </div>
                    <br>

                    <div class="form-group">
                        <div class="fw-bold">Price</div>
                        <div class="d-flex gap-3">
                            <div>
                                <label for="min_price">Minimum Price:</label>
                                <input class="form-control rounded " type="number" id="min_price" name="min_price"
                                    min="0">
                            </div>
                            <div>
                                <label for="max_price">Maximum Price:</label>
                                <input class="form-control rounded " type="number" id="max_price" name="max_price"
                                    min="0">
                            </div>
                        </div>
                    </div>
                    <br>

                    <table class="table table-striped">

                        <?php
                        // include our login information
                        require_once('db.php');

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {

                            $whereClauses = [];

                            if (!empty($_POST['search'])) {
                                $search = $_POST['search'];
                                $whereClauses[] = " (title LIKE '%$search%' OR author LIKE '%$search%' OR isbn LIKE '%$search%')";
                            }


                            if (isset($_POST['category']) and $_POST['category'] != 0) {
                                $category = $_POST['category'];
                                $whereClauses[] = " (categoryid = '$category')";
                            }

                            if (!empty($_POST['min_price'])) {
                                $minPrice = (float) $_POST['min_price'];
                                $whereClauses[] = " (price >= $minPrice)";
                            }

                            if (!empty($_POST['max_price'])) {
                                $maxPrice = (float) $_POST['max_price'];
                                $whereClauses[] = " (price <= $maxPrice)";
                            }

                            if (!empty($whereClauses)) {
                                $query = "SELECT * FROM books WHERE";

                                $query .= implode(" AND", $whereClauses);
                            }
                        }

                        if (isset($query)) {
                            $result = $db->query($query);
                            if (!$result) {
                                die("Could not the query the database: <br />" . $db->error . "<br>Query: " . $query);
                            }

                            // fetch and display the results
                            if ($result->num_rows > 0) {
                                echo '
                                <tr>
                                    <th>ISBN</th>
                                    <th>Author</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                                ';

                                while ($row = $result->fetch_object()) {
                                    echo '<tr>';
                                    echo '<td>' . $row->isbn . '</td>';
                                    echo '<td>' . $row->author . '</td>';
                                    echo '<td>' . $row->title . '</td>';
                                    echo '<td> $' . $row->price . '</td>';
                                    echo '<td><a class="btn btn-primary" href="show_cart.php?id=' . $row->isbn . '">Add to Cart</a></td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<div class="alert alert-warning" role="alert">
                                        Book not found
                                    </div>';
                            }
                            $result->free();
                            $db->close();
                        }
                        ?>
                    </table>

                    <input type="submit" class="btn btn-primary" name="Submit">
                </form>
                <br />
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8"
        crossorigin="anonymous"></script>
</body>

</html>