<?php
/*
if (!file_exists('init.php')) {
    die('init file not exists');
}

require_once 'init.php';

$x = $db->prepare('SELECT * FROM `settings`');
$x->execute();

$fetch = $x->fetchAll();

$settings = [];

foreach ($fetch AS $key => $value) {
    $settings[$value->key] = $value->val;
}


if (isset($_SESSION['success'])) {
    echo $_SESSION['success'];
    unset($_SESSION['success']);
}
*/


require_once 'db.php';

$x = $db->prepare('SELECT * FROM `settings`');
$x->execute();

$fetch = $x->fetchAll();

$settings = [];

foreach ($fetch AS $key => $value) {
    $settings[$value->key] = $value->val;
}

?>

<h2>Settings</h2>
<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
    <label>Main Image</label><input type="file" name="mainImage"><?php echo isset($settings['main-image']) ? '<img src="'.$settings["main-image"].'" style="width:60px;height:60px;margin-left:-55px">' : null; ?><br><br>
    <label>color font</label><input type="text" name="fontColor" placeholder="Color Font ex 0,0,0" value="<?php echo isset($settings['color-font']) ? $settings['color-font'] : null; ?>"><br><br>
    <label>path font</label><input type="text" name="fontPath" placeholder="Path Font" value="<?php echo isset($settings['path-font']) ? $settings['path-font'] : null; ?>"><br><br>
    <label>uploaded folder</label><input type="text" name="uploadedPath" placeholder="Uploaded Folder" value="<?php echo isset($settings['uploaded-folder']) ? $settings['uploaded-folder'] : null; ?>"><br><br>
    <label>font size</label><input type="text" name="fontSize" placeholder="font size ex 30" value="<?php echo isset($settings['font-size']) ? $settings['font-size'] : null; ?>"><br><br>
    <label>angle</label><input type="text" name="angle" placeholder="angle ex 0" value="<?php echo isset($settings['angle']) ? $settings['angle'] : null; ?>"><br><br>
    <label>left</label><input type="text" name="x" placeholder="x ex 800" value="<?php echo isset($settings['x']) ? $settings['x'] : null; ?>"><br><br>
    <label>top</label><input type="text" name="y" placeholder="y ex 500" value="<?php echo isset($settings['y']) ? $settings['y'] : null; ?>"><br><br>
    <input type="submit" value="save">
</form>


<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die();
}

$image = $_FILES['mainImage'];
$post['color-font'] = htmlentities($_POST['fontColor']);
$post['path-font'] = htmlentities($_POST['fontPath']);
$post['uploaded-folder'] = htmlentities($_POST['uploadedPath']);
$post['font-size'] = htmlentities($_POST['fontSize']);
$post['angle'] = htmlentities($_POST['angle']);
$post['x'] = htmlentities($_POST['x']);
$post['y'] = htmlentities($_POST['y']);

if ($image) {

    if ($image['size'] > 0)  {
        $dir = 'images/';
        $imageName = $image['name'];
        $imageType = $image['type'];
        $imageTmpName = $image['tmp_name'];
        $imageError = $image['error'];
        $imageSize = $image['size'];
        $ext_type = array('gif','jpg','jpe','jpeg','png');
        $ext_image = strtolower(end(explode('/' , $imageType)));

        if (!in_array($ext_image , $ext_type)) {
            die('sorry! allowed gif,jpg,jpe,jpeg,png only');
        }

        if ($imageError == 1) {
            die('error in uploaded image');
        }

        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777)) {
                die('Failed to create image folder...');
            }
        }

        $fullPathImage = $dir . md5(time().mt_rand()) . '.'.$ext_image;

        //remove old image
        if (isset($settings['main-image']) && is_file($settings['main-image'])) {
            unlink($settings['main-image']);
        }

        if (!move_uploaded_file($imageTmpName , $fullPathImage)) {
            die('error in uploaded file');
        }

    }else {

        if (!$settings['main-image']) {
            die('image is required');
        }

        $fullPathImage = $settings['main-image'];
    }

    $db->exec('TRUNCATE TABLE `settings`');

    $query = $db->prepare('INSERT INTO `settings` SET `key` = ?, `val` = ?');
    $query->bindValue(1 , 'main-image');
    $query->bindValue(2 , $fullPathImage);

    if (!($query->execute())) {
        die('error is execute code');
    }

    $query = $db->prepare('INSERT INTO `settings` SET `key` = ?, `val` = ?');

    foreach ($post AS $key => $value) {

        $query->bindValue(1, $key);
        $query->bindValue(2, $value);

        $query->execute();
    }

    $_SESSION['success'] = '<div style="color: #fff; background-color: darkgreen; font-size: 30px; text-align: center;padding: 20px">Success Edit</div>';
    header('Location: '.$_SERVER['PHP_SELF']);

}else {

    die('<span style="color: red; font-size: 40px">Oops! image is required</span>');
}









?>