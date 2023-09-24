<!DOCTYPE html>
<html>

<head>
    <title>Grafik Total Buku yang Sudah Dibeli per Kategori</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
</head>

<body>
    <h1>Grafik Total Buku yang Sudah Dibeli per Kategori</h1>

    <?php
    // Koneksi ke database
    require_once('./db.php');

    // Query untuk mengambil data total buku yang sudah dibeli per kategori
    $sql = "SELECT categories.name AS category, COUNT(purchases.purchase_id) AS purchased_count
            FROM categories
            LEFT JOIN books ON categories.categoryid = books.categoryid
            LEFT JOIN purchases ON books.isbn = purchases.isbn
            GROUP BY categories.name";

    $result = $db->query($sql);

    // Menginisialisasi array untuk label kategori dan data jumlah buku yang sudah dibeli
    $categories = [];
    $purchasedCounts = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['category'];
            $purchasedCounts[] = $row['purchased_count'];
        }
    }

    $db->close();
    ?>

    <!-- Menampilkan Grafik -->
    <canvas id="myChart"></canvas>
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($categories); ?>,
                datasets: [{
                    label: 'Jumlah Buku yang Sudah Dibeli',
                    data: <?php echo json_encode($purchasedCounts); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    </script>
</body>

</html>
