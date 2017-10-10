<?php

session_start();

ini_set('session.use_only_cookies', 1);

?>

<html>
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
        <input type="text" name="username" placeholder="username">
        <input type="password" name="password" placeholder="password">
        <input type="submit" value="login">
    </form>
</html>



<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = htmlentities($_POST['username']);
    $password = htmlentities($_POST['password']);

    if (!preg_match("/^[a-zA-Z0-9-]*$/" , $username)) {
        die('username is invalid');
    }
    if (!preg_match("/^[a-zA-Z0-9-]*$/" , $password)) {
        die('password is invalid');
    }

    require_once 'db.php';

    $query = $db->prepare("SELECT * FROM `admins` WHERE username = ?");

    $query->bindParam(1 , $username);

    $query->execute();

    $admin = $query->fetch();

    if ($admin) {

        if (password_verify($password, $admin->password)) {

            $_SESSION['login'] = $admin->code;

            if (file_exists('admin.php')) {
                header('Location: admin.php');
                exit();
            }else {
                die('admin page not fount');
            }
        }
    }

    die('invalid login');

}

?>