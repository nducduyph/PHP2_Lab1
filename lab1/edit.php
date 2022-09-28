<?php
require_once "connect.php";

$erorrs = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ma_tour = $_POST['ma_tour'];
    $name_tour = $_POST['ten_tour'];
    $mota = $_POST['mota'];
    $price = $_POST['price'];
    $number_date =  $_POST['number_date'];
    $category = $_POST['category'];
    $intro =  $_POST['intro'];
    $anh = $_FILES['anh']['name'];
    // validate
    if ($name_tour == '') {
        $erorrs['ten'] = "tên không để rỗng";
    }
    if ($price == '' || $price <= 0) {
        $erorrs['soluong'] = "số lượng không để rỗng và là số lớn hơn 0";
    }
    if ($number_date == '' || $number_date <= 0) {
        $errors['number_date'] = "phải nhập number_date là số dương lớn hơn 0 và không để trống";
    }
    if ($_FILES['anh']['size'] < 0) {
        $errors['anh1'] = "hãy chọn ảnh";
    }
    if ($_FILES['anh']['size'] > 2000000) {
        $errors['anh2'] = "kích thước ảnh  ko quá 2mb";
    }
    $img = ['jpg', 'png'];
    $ext = pathinfo($_FILES['anh']['name'], PATHINFO_EXTENSION);
    if ($_FILES['anh']['size'] > 0) {
        if (!in_array($ext, $img)) {
            $erorrs['anh3'] = "ảnh không đúng định dạng";
        }
        $anh = $_FILES['anh']['name'];
    }

    if (!array_filter($erorrs)) {
        $sql = "UPDATE tours SET id_tour ='$ma_tour',name_tour='$name_tour',image='$anh',intro='$intro',description='$mota',number_date='$number_date',price='$price',category_id='$category' WHERE id_tour = $ma_tour";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        move_uploaded_file($_FILES['anh']['tmp_name'], './img/' . $anh);
        header("location: show.php?massage=sửa dữ liệu thành công");
        die;
    }
}

//loại tour
$sql = "SELECT * FROM categories";
$stmt = $conn->prepare($sql);
$stmt->execute();
$loai = $stmt->fetchAll(PDO::FETCH_ASSOC);
//lấy ra mã xe muốn sửa
$ma = $_GET['ma'];
$sql = "SELECT * FROM tours where id_tour =$ma";
$stmt = $conn->prepare($sql);
$stmt->execute();
$tour = $stmt->fetch(PDO::FETCH_ASSOC);
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
            text-align: center;
            width: 500px;
            border: 1px solid black;
        }

        p {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="ma_tour">
                <input type="hidden" name="ma_tour" value="<?= $tour['id_tour'] ?>">
            </div>
            <div class="ten_tour">
                <p>sửa tên tour</p>
                <input type="text" name="ten_tour" value="<?= $tour['name_tour'] ?>">
                <span style="color: red;"><?= $erorrs['ten'] ?? '' ?></span>
            </div>
            <div class="anh">
                <p>sửa ảnh</p>
                <input type="file" name="anh" value="<?= $tour['image'] ?>">
                <span style="color: red;"><?= $errors['anh1'] ?? '' ?></span>
                <span style="color: red;"><?= $errors['anh2'] ?? '' ?></span>
                <span style="color: red;"><?= $errors['anh3'] ?? '' ?></span>
                <br>
                <img src="./img/<?= $tour['image'] ?>" width="120" alt="">
                <input type="hidden" name="anh" value="<?= $tour['image'] ?>">
            </div>
            <div class="mota">
                <p>sửa mô tả</p>
                <textarea name="mota" id="" cols="30" rows="10">
               <?= $tour['description'] ?>
               </textarea>
            </div>
            <div class="price">
                <p>Sửa price</p>
                <input type="number" name="price" placeholder="price" value="<?= $tour['price'] ?>">
                <span style="color: red;"><?= $erorrs['price'] ?? '' ?></span>
            </div>
            <div class="number_date">
                <p>Sửa number_date</p>
                <input type="number" name="number_date" value="<?= $tour['number_date'] ?>">
                <span style="color: red;"><?= $erorrs['number_date'] ?? '' ?></span>
            </div>
            <div class="intro">
                <p>sửa intro</p>
                <input type="text" name="intro" value="<?= $tour['intro'] ?>">
                <span style="color: red;"><?= $erorrs['intro'] ?? '' ?></span>
            </div>
            <div class="ma_loai">
                <p>sửa loại</p>
                <select name="category" id="">
                    <?php foreach ($loai as $ml) : ?>
                        <?php if ($ml['id'] == $tour['category_id']) : ?>
                            <option selected value="<?= $ml['id']  ?>">
                                <?= $ml['name'] ?>
                            </option>
                        <?php else : ?>
                            <option value="<?= $ml['id']  ?>">
                                <?= $ml['name'] ?>
                            </option>
                        <?php endif ?>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="btn">
                <button type="submit">sửa</button>
            </div>
        </form>
    </div>
</body>

</html>