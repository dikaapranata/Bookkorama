<!DOCTYPE html>
<html>


<head>
    <title>Data Order dan Grafik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/order.css">
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
    <h1>Data Order dan Grafik</h1>

    <!-- Filter Tanggal -->
    <form method="POST" action="">
        Tanggal Mulai: <input type="date" name="start_date">
        Tanggal Selesai: <input type="date" name="end_date">
        <input type="submit" name="filter" value="Filter">
    </form>

    <?php
    require_once('./db.php');

    // Query untuk mengambil data order berdasarkan rentang tanggal
    if (isset($_POST['filter'])) {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        // Validasi bahwa kedua tanggal telah diisi
        if (!empty($start_date) && !empty($end_date)) {
            $sql = "SELECT o.order_id, o.start_date, o.end_date, o.total_price, b.title, b.isbn, b.price 
                    FROM orders o 
                    JOIN books b ON o.isbn = b.isbn
                    WHERE o.start_date BETWEEN '$start_date' AND '$end_date'";
            $result = $db->query($sql);

            if ($result->num_rows > 0) {
                echo "<h2>Riwayat Order</h2>";
                echo "<table>";
                echo "<tr><th>Order ID</th><th>Tanggal Mulai</th><th>Tanggal Selesai</th><th>Total Harga</th><th>Title</th><th>ISBN</th><th>Price</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['order_id'] . "</td>";
                    echo "<td>" . $row['start_date'] . "</td>";
                    echo "<td>" . $row['end_date'] . "</td>";
                    echo "<td>" . $row['total_price'] . "</td>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['isbn'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Tidak ada data order dalam rentang tanggal yang dipilih.</p>";
            }
        } else {
            echo "<p>Silakan isi kedua tanggal sebelum melakukan filter.</p>";
        }
    }
    ?>

    <!-- Tombol Grafik Jumlah -->
    <form method="POST" action="grafik.php">
        <input type="submit" name="grafik_jumlah" value="Grafik Jumlah">
    </form>

    <!-- Tombol Grafik Dibeli -->
    <form method="POST" action="grafik_dua.php">
        <input type="submit" name="grafik_dibeli" value="Grafik Dibeli">
    </form>

</body>

</html>