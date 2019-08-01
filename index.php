<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>
<body>
<form method="post">
    <input type="text" name="name" placeholder="Tên người dùng"/><br>
    <input type="text" name="email" placeholder="Email"/><br>
    <input type="text" name="phone" placeholder="Số điện thoại"/><br>
    <button type="submit">Đăng ký</button>
</form>
<?php
function delData($filename, $index){
    $jsondata = file_get_contents($filename);
    $arr_data = json_decode($jsondata, true);
    array_splice($arr_data, $index, 1);
    $jsondata = json_encode($arr_data);
    file_put_contents($filename, $jsondata);
}

function readDataJSON($filename)
{
    $jsondata = file_get_contents($filename);
    $arr_data = json_decode($jsondata, true);
    return $arr_data;
}

function saveDataJSON($filename, $name, $email, $phone)
{
    $contact = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone
    ];
    $arr_data = readDataJSON($filename);
    array_push($arr_data, $contact);
    $jsondata = json_encode($arr_data);
    file_put_contents($filename, $jsondata);
    echo 'Đăng ký thành công.';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $error = false;
    if (empty($name)) {
        echo "Tên người dùng không được bỏ trống" . "<br>";
        $error = true;
    }
    if (empty($email)) {
        echo "Email không được bỏ trống" . "<br>";
        $error = true;
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Email không hợp lệ" . "<br>";
            $error = true;
        }
    }
    if (empty($phone)) {
        echo "Số điện thoại không được bỏ trống" . "<br>";
        $error = true;
    }
    if (!$error){
        saveDataJSON('user.json', $name, $email, $phone);
    }
}
?>
<?php
$data = readDataJSON('user.json');
?>

<br>
<table border="1" cellspacing="0">
    <tr>
        <th>Stt</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
    </tr>
    <?php foreach ($data as $index=>$value): ?>
        <tr>
            <td><?= $index + 1; ?></td>
            <td><?= $value['name']; ?></td>
            <td><?= $value['email']; ?></td>
            <td><?= $value['phone']; ?></td>
            <td><form method="get"><input type="submit" name="delData<?= $index?>" value="Xóa" /></form> </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
if(isset($_GET['delData'.$index]))
{
    delData('user.json', $index);
}
?>
</body>
</html>