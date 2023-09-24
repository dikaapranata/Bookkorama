<!DOCTYPE html>
<html>

<head>
    <title>Filter Pencarian Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <style>
    </style>
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

    <div class="container">
        <h1>Filter Pencarian Buku</h1>

        <form method="post" action="index.php">
            Pilih Kategori:
            <select name="categoryid">
                <option value="" disabled selected>==Semua Kategori==</option>
                <option value="1">Computer</option>
                <option value="2">Design</option>
                <option value="3">Fiction</option>
                <option value="4">Cooking</option>
                <option value="5">Architecture</option>
            </select>
            <input type="submit" name="cari" value="Cari">
        </form>

        <?php
        // Koneksi ke database
        require_once('./db.php');

        if (isset($_POST['cari'])) {
            // Pastikan $_POST['cari'] telah diatur sebelum mengambil categoryid
            if (isset($_POST['categoryid'])) {
                $selectedCategory = $_POST['categoryid'];

                // Query pencarian berdasarkan categoryid jika bukan "Semua Kategori"
                if (!empty($selectedCategory)) {
                    // Dapatkan nama kategori yang sesuai berdasarkan ID
                    $categoryQuery = "SELECT name FROM categories WHERE categoryid = $selectedCategory";
                    $categoryResult = $db->query($categoryQuery);
                    if ($categoryResult->num_rows > 0) {
                        $categoryRow = $categoryResult->fetch_assoc();
                        $categoryName = $categoryRow['name'];
                    } else {
                        $categoryName = "Tidak Diketahui"; // Jika ID kategori tidak ditemukan
                    }

                    // Query untuk pencarian buku dengan kategori yang sesuai
                    $sql = "SELECT * FROM books WHERE categoryid = $selectedCategory";
                } else {
                    // Jika "Semua Kategori" dipilih, tampilkan semua buku
                    $sql = "SELECT * FROM books";
                    $categoryName = "Semua Kategori";
                }

                $result = $db->query($sql);

                // Bagian tabel hasil pencarian
                if ($result->num_rows > 0) {
                    // Ubah pesan untuk menampilkan nama kategori
                    echo "<h2>Hasil Pencarian untuk Kategori: <span>$categoryName</span></h2>";
                    echo "<table class='table'>";
                    echo "<thead><tr><th>ISBN</th><th>Title</th><th>Author</th><th>Price</th><th>Detail</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['isbn'] . "</td>";
                        echo "<td>" . $row['title'] . "</td>";
                        echo "<td>" . $row['author'] . "</td>";
                        echo "<td>" . $row['price'] . "</td>";
                        echo "<td><a href='detail.php?isbn=" . $row['isbn'] . "'>Detail</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<p>Tidak ada hasil pencarian untuk Kategori: $categoryName</p>";
                }
            }
        }

        // Code to display the table sorted by category (initially hidden)
        $sqlCategory = "SELECT DISTINCT categoryid FROM books";
        $resultCategory = $db->query($sqlCategory);

        if ($resultCategory->num_rows > 0) {
            echo "<h2>Daftar Buku Berdasarkan Kategori:</h2>";
            echo "<button onclick=\"toggleCategoryTable()\" class=\"btn btn-primary\">Klik untuk memperlihatkan/menyembunyikan tabel</button>";
            echo "<a href=\"./book_handler/add_book.php\" class=\"btn btn-primary\">+ Add Book Data</a>";
            echo "<table id='categoryTable' class='table'>";
            echo "<thead><tr><th>Category</th><th>ISBN</th><th>Title</th><th>Author</th><th>Price</th><th>Action</th></tr></thead>";
            echo "<tbody>";

            while ($rowCategory = $resultCategory->fetch_assoc()) {
                $categoryId = $rowCategory['categoryid'];

                // Get category name based on category ID (replace with your database query)
                $categoryName = getCategoryName($categoryId);

                echo "<tr><td>$categoryName</td></tr>";

                $sqlBooks = "SELECT * FROM books WHERE categoryid = $categoryId";
                $resultBooks = $db->query($sqlBooks);

                while ($rowBook = $resultBooks->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td></td>";
                    echo "<td>" . $rowBook['isbn'] . "</td>";
                    echo "<td>" . $rowBook['title'] . "</td>";
                    echo "<td>" . $rowBook['author'] . "</td>";
                    echo "<td>" . $rowBook['price'] . "</td>";
                    echo '<td><a class="btn btn-warning btn-sm" href="./book_handler/edit_book.php?id=' . $rowBook['isbn'] . '">Edit</a>&nbsp;<a class="btn btn-danger btn-sm" href="./book_handler/confirm_delete_book.php?id=' . $rowBook['isbn'] . '">Delete</a></td>';
                    echo "</tr>";
                }
            }
            echo "</tbody>";
            echo "</table>";
        }

        // Function to get category name (replace with your database query)
        function getCategoryName($categoryId)
        {
            // Replace this function with your database query logic.
            // For now, we'll use a simple array as an example.
            $categories = [
                "1" => "Computer",
                "2" => "Design",
                "3" => "Fiction",
                "4" => "Cooking",
                "5" => "Architecture"
            ];

            return isset($categories[$categoryId]) ? $categories[$categoryId] : "Unknown";
        }

        // JavaScript function to toggle visibility of the category table
        echo "<script>";
        echo "function toggleCategoryTable() {";
        echo "  var categoryTable = document.getElementById('categoryTable');";
        echo "  if (categoryTable.style.display === 'none') {";
        echo "    categoryTable.style.display = 'table';";
        echo "  } else {";
        echo "    categoryTable.style.display = 'none';";
        echo "  }";
        echo "}";
        echo "</script>";

        $db->close();
        ?>

    </div>
</body>

</html>