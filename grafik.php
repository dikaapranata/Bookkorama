<!DOCTYPE html>
<html>

<head>
    <title>Grafik Data Buku</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <h1>Grafik Jumlah Perkategori</h1>

    <canvas id="myChart"></canvas>

    <?php
    // Koneksi ke database
    require_once('./db.php');

    // Query untuk mengambil jumlah buku dalam setiap kategori
    $sql = "SELECT categories.name AS category_name, COUNT(books.categoryid) as jumlah_buku
            FROM books
            LEFT JOIN categories ON books.categoryid = categories.categoryid
            GROUP BY books.categoryid, categories.name";
    $result = $db->query($sql);

    $dataKategori = array();
    $dataJumlahBuku = array();

    while ($row = $result->fetch_assoc()) {
        $category = $row['category_name'];
        $jumlahBuku = $row['jumlah_buku'];

        // Menambahkan data ke array
        $dataKategori[] = $category;
        $dataJumlahBuku[] = $jumlahBuku;
    }
    ?>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($dataKategori); ?>,
                datasets: [{
                    label: 'Jumlah Buku',
                    data: <?php echo json_encode($dataJumlahBuku); ?>,
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