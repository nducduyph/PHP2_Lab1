<?php
require_once "connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_tour = $_POST['name_tour'];
    $mota = $_POST['mota'];
    $price = $_POST['price'];
    $number_date =  $_POST['number_date'];
    $category = $_POST['category'];
    $intro =  $_POST['intro'];
    $anh = $_FILES['anh']['name'];
    // validate
    if ($name_tour == '') {
        $errors['ten'] = "tên không để rỗng";
    }
    if ($price == '' || $price < 0) {
        $errors['soluong'] = "price không để rỗng và là số lớn hơn không";
    }
    if ($number_date == '' || $number_date < 0) {
        $errors['number_date'] = "phải nhập number_date là số dương và không để trống";
    }
    if ($_FILES['anh']['size'] < 0) {
        $errors['anh1'] = "hãy chọn ảnh";
    }
    if ($_FILES['anh']['size'] > 2000000) {
        $errors['anh2'] = "kích thước ảnh  ko quá 2mb";
    }
    $img = ['jpg', 'png'];
    $ext = pathinfo($_FILES['anh']['name'], PATHINFO_EXTENSION);
    if (!in_array($ext, $img)) {
        $errors['anh3'] = "ảnh không đúng định dạng";
    }
    if (!array_filter($errors)) {
        $sql = "INSERT INTO tours(name_tour, image,intro,description, number_date,price,category_id) 
        value('$name_tour','$anh','$intro','$mota','$number_date','$price','$category')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        move_uploaded_file($_FILES['anh']['tmp_name'], './img/' . $_FILES['anh']['name']);
        header("location: show.php?massage=Thêm dữ liệu thành công");
        die;
    }
}
$sql = "SELECT * FROM categories";
$stmt = $conn->prepare($sql);
$stmt->execute();
$loai = $stmt->fetchAll(PDO::FETCH_ASSOC);
// //loại
// $sql = "SELECT * FROM loai_xe";
// $stmt = $conn->prepare($sql);
// $stmt->execute();
// $loai = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        form {
            padding-left: 100px;
            width: 500px;
            border: 1px solid black;
        }

        input {
            width: 250px;
        }

        form div {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="them.php" method="post" enctype="multipart/form-data">
            <div class="Name_tour">
                <input type="text" name="name_tour" placeholder="tên tour" value="<?= $name_tour ?? '' ?>">
                <span style="color: red;"><?= $errors['ten'] ?? '' ?></span>
            </div>
            <div class="anh">
                <input type="file" name="anh" value="<?= $anh ?? '' ?>">
                <span style="color: red;"><?= $errors['anh1'] ?? '' ?></span>
                <span style="color: red;"><?= $errors['anh2'] ?? '' ?></span>
                <span style="color: red;"><?= $errors['anh3'] ?? '' ?></span>
            </div>
            <div class="mota">
                <textarea name="mota" id="" cols="30" rows="10">
               <?= $mota ?? '' ?>
               </textarea>
            </div>
            <div class="price">
                <input type="number" name="price" placeholder="price" value="<?= $price ?? '' ?>">
                <span style="color: red;"><?= $errors['price'] ?? '' ?></span>
            </div>
            <div class="number_date">
                <input type="number" name="number_date" placeholder="number_date" value="<?= $number_date ?? '' ?>">
                <span style="color: red;"><?= $errors['number_date'] ?? '' ?></span>
            </div>
            <div class="intro">
                <input type="text" name="intro" placeholder="intro" value="<?= $intro ?? '' ?>">
                <span style="color: red;"><?= $errors['intro'] ?? '' ?></span>
            </div>
            <div class="category">
                <select name="category" id="">
                    <?php foreach ($loai as $ml) : ?>
                        <option value="<?= $ml['id']  ?>">
                            <?= $ml['name'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="btn">
                <button type="submit">Thêm</button>
            </div>
        </form>
    </div>
</body>

</html>