<!DOCTYPE html>
<html>

<head>
    <title>Detail Buku</title>
    <link rel="stylesheet" href="css/detail.css">
</head>

<body>
    <h1>Detail Buku</h1>

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
            echo "<th>Ulasan</th><th>Tanggal Ulasan</th></tr>";
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
        echo "<h2>Beri Ulasan</h2>";
        echo "<textarea name='ulasan' rows='4' cols='50' required></textarea><br>";
        echo "<input type='submit' name='submit' value='Kirim Ulasan'>";
        echo "</form>";

        // Proses pengiriman ulasan
        if (isset($_POST['submit'])) {
            // Tangkap data ulasan dari formulir
            $rating = $_POST['rating'];
            $ulasan = $_POST['ulasan'];

            // Simpan ulasan ke database
            $sql = "INSERT INTO book_reviews ( isbn, review)
                    VALUES ('$isbn',  '$ulasan')";

            if ($db->query($sql) === TRUE) {
                // Lakukan pengalihan (redirect) ke halaman ini lagi untuk menghindari ulasan yang tersimpan di cache
                redirect("detail.php?isbn=$isbn");
            } else {
                echo "<p>Terjadi kesalahan saat menyimpan ulasan: " . $db->error . "</p>";
            }
        }
    } else {
        echo "<p>ISBN buku tidak valid.</p>";
    }

    $db->close();
    ?>
</body>

</html>