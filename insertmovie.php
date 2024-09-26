<?php
$mysqli = new mysqli("localhost", "root", "", "fspw1");
if ($mysqli->connect_errno) {
    die ("Failed to connect to MySQL: " . $mysqli->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Movie</title>
    <script type="text/javascript" src="jquery-3.7.1.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group input[type="date"] {
            padding: 6px;
        }
        .form-group input[type="number"] {
            width: calc(100% - 16px);
        }
        .form-group input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php
if(isset($_GET['hasil'])){
    if($_GET['hasil']){
        echo "<p>Data berhasil disimpan</p>";
    }
    else
        echo "<p>Data Gagal disimpan</p>";
}
?>
    <div class="form-container">
        <h2>Tambah Movie</h2>
        <form action="insertmovie_proses.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul">Judul</label>
                <input type="text" id="judul" name="judul" required>
            </div>

            <div class="form-group">
                <label for="rilis">Tanggal Rilis</label>
                <input type="date" id="rilis" name="rilis" required>
            </div>

            <div class="form-group">
                <label for="skor">Skor</label>
                <input type="number" id="skor" name="skor" step="0.1" min="0" max="10" required>
            </div>

            <div class="form-group">
                <label for="sinopsis">Sinopsis</label>
                <input type="text" id="sinopsis" name="sinopsis" required>
            </div>

            <div class="form-group">
                <label for="serial">Bagian dari Serial?</label>
                <select id="serial" name="serial" required>
                    <option value="0">Tidak</option>
                    <option value="1">Ya</option>
                </select>
            </div>

            <p>
                <label for="genre">Genre</label>
                <?php
                $stmt = $mysqli->prepare("Select * From genre");
                $stmt->execute();
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    echo "<br><input type='checkbox'
                        name='genre[]' id='".$row['nama']."' value='".$row['idgenre']."'>
                        <label for='".$row['nama']."'>".$row['nama'
                        ]."</label>";
                }
                ?>
            </p>
            <p>
                <label>Poster</label> 
                <div id="inputPoster">
                    <input type="file" name="poster[]">
                </div>
                
                <div>
                    <input type="button" id="btnTambahPoster" value="Tambah Poster">
                </div>

                <label>Pemain</label>
                <select id="selpemain">
                    <?php
                        $stmt = $mysqli->prepare("Select * From pemain");
                        $stmt->execute();
                        $res = $stmt->get_result();
                        while($row = $res->fetch_assoc()) {
                            echo "<option value='".$row['idpemain']."'>".$row['nama']."</option>";
                        }
                    ?>
                </select>

                <select id="selperan">
                    <option value="Utama">Utama</option>
                    <option value="Pembantu">Pembantu</option>
                    <option value="Cameo">Cameo</option>
                </select>
                
                <div>
                    <input type="button" id="btnTambahPemain" value="Tambah Pemain">
                </div>

                <div>
                    <table id="daftar_pemain">
                        <tr><th>Pemain</th> <th>Peran</th> <th>Aksi</th></tr>
                    </table>
                </div>
            </p>
            <div class="form-group">
                <input type="submit" value="Tambah Movie">
            </div>
        </form>
        <script type="text/javascript">
            $('body').on('click', '#btnTambahPemain', function() {
                var id_pemain = $('#selpemain').val();
                var nama_pemain = $('#selpemain option:selected').text();
                var peran = $('#selperan').val();
                var tambah = "<tr>";
                tambah+="<td>"+nama_pemain+"</td>";
                tambah+="<td>"+peran+"<input type='hidden' name='peran["+id_pemain+"]' value='"+peran+"'></td>";
                tambah+="<td><input type='button' value='hapus' class='btnHapusPemain'></td>";
                tambah+="</tr>";
                $('#daftar_pemain').append(tambah);
            });
            $('body').on('click', '.btnHapusPemain', function() {
                $(this).parent().parent().remove()
            });
            $('#btnTambahPoster').click(function(){
                var tambah = "<div><input type='file' name='poster[]'><input type='button' class='btnHapusPoster' value='Hapus'></div>";
                $('#inputPoster').append(tambah)
            });
            $('body').on('click', '.btnHapusPoster', function() {
                $(this).parent().remove()
            });
        </script>
    </div>
<button onclick="window.location.href='week1.php';">Movie</button>
</body>
</html>