<!DOCTYPE html>
<html>

<head>
    <title>Pembelian Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/beli.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light custom-bg">
        <div class="container">
            <a class="navbar-brand" href="./index.php">Filter Pencarian Buku</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./data_order.php">Data Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./pembelian.php">Pembelian</a>
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

            // Periksa apakah ISBN yang dikirimkan sesuai dengan buku yang ada dalam tabel books
            $checkISBNQuery = "SELECT isbn FROM books WHERE isbn = '$isbn'";
            $checkResult = $db->query($checkISBNQuery);

            if ($checkResult->num_rows === 0) {
                echo "ISBN yang Anda masukkan tidak sesuai dengan buku yang ada.";
            } else {
                // ISBN sesuai, lanjutkan dengan proses pembelian
                $insertPurchaseQuery = "INSERT INTO purchases (isbn, purchase_date)
                                        VALUES ('$isbn', NOW())";

                if ($db->query($insertPurchaseQuery) === TRUE) {
                    echo "Pembelian berhasil!";
                } else {
                    echo "Error: " . $db->error;
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
