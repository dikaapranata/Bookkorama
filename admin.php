<!DOCTYPE html>
<html>

<head>
    <title>CRUD Kelompok 9</title>
    <link rel="stylesheet" href="style.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tangkap elemen tombol dan formulir
            var tambahButton = document.getElementById('tambahButton');
            var formTambah = document.getElementById('formTambah');

            // Tambahkan event listener ke tombol
            tambahButton.addEventListener('click', function() {
                // Tampilkan formulir input
                formTambah.style.display = 'block';
            });
        });

        function showNotification(message) {
            var notification = document.getElementById("notification");
            notification.innerHTML = message;
            notification.style.display = "block";
            setTimeout(function() {
                notification.style.display = "none";
            }, 3000); // Notifikasi akan menghilang setelah 3 detik
        }
    </script>
</head>

<body>
    <h1>CRUD Kelompok 9</h1>
    <div id="notification" style="display: none; background-color: #4CAF50; color: white; padding: 10px; position: fixed; top: 0; left: 0; width: 100%; text-align: center;">
        Buku berhasil ditambahkan.
    </div>


    <?php
    // Koneksi ke database
    require_once('./db.php');

    // Fungsi Delete
    if (isset($_GET['delete'])) {
        $isbnToDelete = $_GET['delete'];

        $sql = "DELETE FROM books WHERE isbn='$isbnToDelete'";
        if ($conn->query($sql) === TRUE) {
            echo "Data buku berhasil dihapus.";
        } else {
            echo "Error: " . $sql . "<br>" . $db->error;
        }
    }

    // Fungsi Create (Tambah Data Buku)
    if (isset($_POST['create'])) {
        $isbn = $_POST['isbn'];
        $title = $_POST['title'];
        $categoryid = $_POST['categoryid'];
        $author = $_POST['author'];
        $price = $_POST['price'];

        $sql = "INSERT INTO books (isbn, title, categoryid, author, price) VALUES ('$isbn', '$title', '$categoryid', '$author', '$price')";
        if ($conn->query($sql) === TRUE) {
            echo "Data buku berhasil ditambahkan.";
        } else {
            echo "Error: " . $sql . "<br>" . $db->error;
        }
    }

    // Menampilkan data buku (Read)
    $sql = "SELECT books.isbn, books.title, categories.name AS category, books.author, books.price FROM books INNER JOIN categories ON books.categoryid = categories.categoryid";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ISBN</th><th>Title</th><th>Category</th><th>Author</th><th>Price</th><th>Action</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['isbn'] . "</td>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td>" . $row['author'] . "</td>";
            echo "<td>" . $row['price'] . "</td>";
            echo "<td>";

            if (isset($_GET['edit']) && $_GET['edit'] == $row['isbn']) {
                echo "<form method='post' action='admin.php'>";
                echo "<input type='hidden' name='isbn' value='" . $row['isbn'] . "'>";
                echo "<input type='text' name='new_isbn' value='" . $row['isbn'] . "'>";
                echo "<input type='text' name='new_title' value='" . $row['title'] . "'>";
                echo "<input type='text' name='new_category' value='" . $row['category'] . "'>";
                echo "<input type='text' name='new_author' value='" . $row['author'] . "'>";
                echo "<input type='text' name='new_price' value='" . $row['price'] . "'>";
                echo "<input type='submit' name='update' value='Save'>";
                echo "</form>";
            } else {
                echo "<a href='admin.php?edit=" . $row['isbn'] . "'>Edit</a>";
            }

            echo " | <a href='admin.php?delete=" . $row['isbn'] . "'>Delete</a>";
            echo "</td>";

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Tidak ada data buku.";
    }

    // Logika untuk mengambil data yang diubah dan menyimpannya ke dalam database
    if (isset($_POST['update'])) {
        $isbn = $_POST['isbn'];
        $new_isbn = $_POST['new_isbn'];
        $new_title = $_POST['new_title'];
        $new_category = $_POST['new_category'];
        $new_author = $_POST['new_author'];
        $new_price = $_POST['new_price'];

        // Buat query UPDATE untuk menyimpan perubahan ke dalam database
        $update_sql = "UPDATE books SET isbn='$new_isbn', title='$new_title', categoryid='$new_category', author='$new_author', price='$new_price' WHERE isbn='$isbn'";
        if ($conn->query($update_sql) === TRUE) {
            // Tampilkan notifikasi
            echo "<script>showNotification('Buku berhasil diperbarui.');</script>";
        } else {
            echo "Error: " . $update_sql . "<br>" . $db->error;
        }
    }

    $db->close();
    ?>

    <button id="tambahButton">Tambah Buku</button>

    <form method="post" action="admin.php" style="display: none;" id="formTambah">
        <!-- Isi formulir input di sini -->
        ISBN: <input type="text" name="isbn"><br>
        Title: <input type="text" name="title"><br>
        Category ID: <input type="text" name="categoryid"><br>
        Author: <input type="text" name="author"><br>
        Price: <input type="text" name="price"><br>
        <input type="submit" name="create" value="Create">
    </form>
</body>

</html>