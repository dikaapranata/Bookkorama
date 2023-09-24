<!DOCTYPE html>
<html>

<head>
    <title>Grafik Jumlah Buku yang Sudah Dibeli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/order.css">
    <!-- Sertakan pustaka Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Navbar -->
    <!-- ... (kode navbar) -->

    <h1>Grafik Jumlah Buku yang Sudah Dibeli (Per Kategori)</h1>

    <?php
    require_once('./db.php');

    // Query untuk mengambil data jumlah buku yang sudah dibeli per kategori
    $sql = "SELECT c.name AS category, SUM(IFNULL(oi.quantity, 0)) AS total
        FROM categories c
        LEFT JOIN books b ON c.categoryid = b.categoryid
        LEFT JOIN order_items oi ON b.isbn = oi.isbn
        LEFT JOIN orders o ON oi.orderid = o.orderid
        GROUP BY c.name";




    $result = $db->query($sql);

    // Inisialisasi data untuk grafik
    $categories = [];
    $totals = [];


    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category'];
        $totals[] = $row['total'];
    }
    ?>

    <!-- Tampilkan grafik menggunakan Chart.js -->
    <div style="width: 80%; margin: auto;">
        <canvas id="myChart"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($categories); ?>,
                datasets: [{
                    label: 'Jumlah Buku yang Sudah Dibeli',
                    data: <?php echo json_encode($totals); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <!-- Tombol kembali ke halaman data_order.php -->
    <form method="POST" action="data_order.php">
        <input type="submit" name="back" value="Kembali">
    </form>

</body>

</html>