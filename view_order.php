<html>
    <head>
        <?php 
        require_once('./db.php');

        include('./header.php');
        ?>

    </head>
    <body>
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
                            include('./header.php');
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
                                            echo "<td>".$row['orderid']."</td>";
                                            echo "<td>".$row['isbn']."</td>";
                                            echo "<td>".$row['title']."</td>";
                                            echo "<td>".$row['amount']."</td>";
                                            echo "<td>".$row['quantity']."</td>";
                                            echo "<td>".$row['price']."</td>";
                                            echo "<td>".$row['date']."</td>";
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
                                    };
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