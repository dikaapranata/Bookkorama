<html>

<head>
    <?php
    require_once('./db.php');

    include('./book_handler/header.php');
    ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
                        <a class="nav-link" href="./view_order.php"><strong>View Order</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./search.php">Pencarian Buku</a>
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
            <div class="card-header">Data Order</div>
            <div class="card-body">
                <form method="POST" action="">
                    Tanggal Mulai: <input type="date" name="start_date">
                    Tanggal Selesai: <input type="date" name="end_date">
                    <input type="submit" name="filter" value="Filter">
                </form>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>ISBN</th>
                            <th>Judul Buku</th>
                            <th>Total</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Tanggal Pembelian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Koneksi ke database
                        require_once('./db.php');
                        include('./book_handler/header.php');
                        // Query untuk mengambil data order berdasarkan rentang tanggal
                        if (isset($_POST['filter'])) {
                            $start_date = $_POST['start_date'];
                            $end_date = $_POST['end_date'];

                            // Validasi bahwa kedua tanggal telah diisi
                            if (!empty($start_date) && !empty($end_date)) {
                                $sql = "SELECT o.orderid, o.date, o.amount, b.title, b.isbn, b.price, oi.quantity 
                                            FROM orders o 
                                            JOIN order_items oi ON o.orderid = oi.orderid
                                            JOIN books b ON oi.isbn = b.isbn
                                            WHERE o.date BETWEEN '$start_date' AND '$end_date'";
                                $result = $db->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['orderid'] . "</td>";
                                        echo "<td>" . $row['isbn'] . "</td>";
                                        echo "<td>" . $row['title'] . "</td>";
                                        echo "<td>" . $row['amount'] . "</td>";
                                        echo "<td>" . $row['quantity'] . "</td>";
                                        echo "<td>" . $row['price'] . "</td>";
                                        echo "<td>" . $row['date'] . "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</table>";
                                } else {
                                    echo "<p>Tidak ada data order dalam rentang tanggal yang dipilih.</p>";
                                }
                            } else {
                                $sql = "SELECT *
                                            FROM orders
                                            LEFT JOIN order_items ON orders.orderid = order_items.orderid
                                            LEFT JOIN books ON order_items.isbn = books.isbn
                                            WHERE orders.orderid IS NOT NULL
                                            "
                                ;

                                $result = $db->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                    <td>" . $row['orderid'] . "</td>
                                                    <td>" . $row['isbn'] . "</td>
                                                    <td>" . $row['title'] . "</td>
                                                    <td>" . $row['amount'] . "</td>
                                                    <td>" . $row['quantity'] . "</td>
                                                    <td>" . $row['price'] . "</td>
                                                    <td>" . $row['date'] . "</td>
                                                </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='9'><center>Tidak ada data order</center></td></tr>";
                                }
                                ;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>