<?php
require_once "ketnoi.php";
$sql = "SELECT * FROM tours ORDER BY id_tour DESC ";
$stmt = $conn->prepare($sql);
$stmt->execute();
$tour = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>hiển thị sản phẩm</h1>
    <table border="1" style="border: 1px solid black;">
        <tr>
            <th>Tên tours</th>
            <th>categories id</th>
            <th>ảnh</th>
            <th>mô tả</th>
            <th>number_date </th>
            <th>price</th>
            <th>intro</th>
            <th> <a href="them.php">Thêm</a></th>
        </tr>
        <?php foreach ($tour as $t) : ?>
            <tr>
                <td><?= $t['name_tour'] ?></td>
                <td><?= $t['category_id'] ?></td>
                <td><img src="./img/<?= $t['image'] ?>" width="120" alt=""></td>
                <td><?= $t['description'] ?></td>
                <td><?= $t['number_date'] ?></td>
                <td><?= $t['price'] ?></td>
                <td><?= $t['intro'] ?></td>
                <td>
                    <a href="sua.php?ma=<?= $t['id_tour'] ?>">sửa</a>
                    <a onclick="return confirm('bạn có muốn xóa không')" href="xoa.php?ma=<?= $t['id_tour'] ?>">xóa</a>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</body>

</html>