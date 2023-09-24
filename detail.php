<!DOCTYPE html>
<html>

<head>
    <title>Detail Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/detail.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light custom-bg">
        <div class="container">
            <a class="navbar-brand" href="./index.php">Filter Pencarian Buku</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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

    <h1>Detail Buku</h1>
    <form method="post" action="">
        <h2>Beri Ulasan</h2>
        <textarea name='ulasan' rows='4' cols='50' required></textarea><br>
        <input type='submit' name='submit' value='Kirim Ulasan'>
    </form>

    <?php
    // Fungsi untuk melakukan pengalihan
    function redirect($url)
    {
        header("Location: $url");
        exit();
    }

    // Koneksi ke database
    require_once('./db.php');

    // Ambil ISBN buku dari parameter URL
    if (isset($_GET['isbn'])) {
        $isbn = $_GET['isbn'];

        // Query untuk mengambil data buku berdasarkan ISBN
        $sql = "SELECT * FROM books WHERE isbn = '$isbn'";
        $result = $db->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<h2>Detail Buku</h2>";
                echo "<table>";
                echo "<tr><td>ISBN</td><td>:</td><td>" . $row['isbn'] . "</td></tr>";
                echo "<tr><td>Judul</td><td>:</td><td>" . $row['title'] . "</td></tr>";
                echo "<tr><td>Penulis</td><td>:</td><td>" . $row['author'] . "</td></tr>";
                echo "<tr><td>Harga</td><td>:</td><td>" . $row['price'] . "</td></tr>";
                echo "</table>";
            }
        } else {
            echo "<p>Buku dengan ISBN $isbn tidak ditemukan.</p>";
        }

        // Query untuk mengambil ulasan buku
        $sql = "SELECT book_reviews.review
                FROM book_reviews
                WHERE isbn = '$isbn'";

        $result = $db->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2>Ulasan Buku</h2>";
            echo "<table border='1'>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['review'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Belum ada ulasan untuk buku ini.</p>";
        }

        // Formulir untuk memberikan ulasan
    } else {
        echo "<p>ISBN buku tidak valid.</p>";
    } // Proses pengiriman ulasan
    if (isset($_POST['submit'])) {
        // Tangkap data ulasan dari formulir
        $rating = $_POST['rating'];
        $ulasan = $_POST['ulasan'];

        // Save to database
        $sql = "INSERT INTO book_reviews ( isbn, review)
                VALUES ('$isbn',  '$ulasan')";

        if ($db->query($sql) === TRUE) {
            // Lakukan pengalihan (redirect) ke halaman ini lagi untuk menghindari ulasan yang tersimpan di cache
            redirect("index.php?isbn=$isbn");
        } else {
            echo "<p>Terjadi kesalahan saat menyimpan ulasan: " . $db->error . "</p>";
        }
    }

    $db->close();
    ?>


</body>

</html>