<!DOCTYPE html>
<html>

<head>
    <title>Pembelian Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/beli.css">
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
                        <a class="nav-link" href="./pembelian.php"><strong>Pembelian</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./view_order.php">View Order</a>
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

    <h1>Pembelian Buku</h1>

    <?php
    // Koneksi ke database
    require_once('./db.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Proses pembelian
        if (isset($_POST['isbn'])) {
            $isbn = $_POST['isbn'];

            // Periksa status buku sebelum memproses pembelian
            $checkStatusQuery = "SELECT status FROM books WHERE ISBN = '$isbn'";
            $statusResult = $db->query($checkStatusQuery);

            if ($statusResult->num_rows > 0) {
                $statusRow = $statusResult->fetch_assoc();
                if ($statusRow['status'] == 1) {
                    echo "Anda sudah membeli buku ini sebelumnya.";
                } else {
                    // Perbarui nilai status menjadi TRUE (atau 1)
                    $updateQuery = "UPDATE books SET status = TRUE WHERE ISBN = '$isbn'";
                    if ($db->query($updateQuery) === TRUE) {
                        echo "Pembelian berhasil! Status buku telah diubah.";

                        // Insert data ke tabel orders
                        $currentDate = date("Y-m-d");
                        $endDate = date("Y-m-d", strtotime("+3 days"));

                        $insertOrderQuery = "INSERT INTO orders (start_date, end_date, order_date, price, isbn, title, total_price, user_id, tf)
                                            SELECT '$currentDate', '$endDate', '$currentDate', Price, ISBN, Title, Price, NULL, NULL
                                            FROM books
                                            WHERE ISBN = '$isbn'";

                        if ($db->query($insertOrderQuery) === TRUE) {
                            echo " Data pembelian berhasil dimasukkan ke tabel orders.";
                        } else {
                            echo "Error: " . $db->error;
                        }
                    } else {
                        echo "Error: " . $db->error;
                    }
                }
            }
        }
    }

    // Query untuk mengambil semua data buku
    $query = "SELECT ISBN, Title, Author, Price FROM books";

    $result = $db->query($query);

    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>ISBN</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Harga</th>
                    <th>Action</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["ISBN"] . "</td>
                    <td>" . $row["Title"] . "</td>
                    <td>" . $row["Author"] . "</td>
                    <td>" . $row["Price"] . "</td>
                    <td>
                        <form method='post'>
                            <input type='hidden' name='isbn' value='" . $row["ISBN"] . "'>
                            <button type='submit'>Beli</button>
                        </form>
                    </td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "Tidak ada buku yang tersedia.";
    }

    $db->close();
    ?>

    <p><a href="index.php">Kembali ke Beranda</a></p>
</body>

</html>