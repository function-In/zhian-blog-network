<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>退出登录</title>
</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION['userinfo'])) {
        session_destroy();
        echo '<script>alert(\'您已退出登录！\');location = \'/login.php\';</script>';
    } else {
        echo '<script>alert(\'非法操作！\');history.back()</script>';
    }
    ?>
</body>

</html>