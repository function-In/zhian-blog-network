<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            user-select: none;
            transition: all .3s ease;
        }

        body {
            position: relative;
            background-color: rgb(245, 245, 245);
        }

        .box {
            width: 80%;
            min-width: 380px;
            max-width: 900px;
            background-color: white;
            text-align: center;
            margin: 10pc auto 0 auto;
            box-shadow: 0 0 5px rgb(170, 168, 168);
            padding-top: 50px;
        }

        .box img {
            width: 100px;
            height: 100px;
            margin-top: 50px;
            border-radius: 50%;
        }

        .box .title {
            margin: 20px 0 10px 0;
        }

        .box .content {
            margin: 10px 0 10px 0;
        }

        .box .content input {
            width: 60%;
            height: 35px;
            line-height: 35px;
            text-indent: 10px;
        }

        .box .content input[type=submit] {
            width: 40%;
            height: 40px;
            margin-top: 20px;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>

    <?php
    session_start();
    // echo md5('root');
    if (isset($_SESSION['userinfo'])) {
        echo "<script>alert('您已进行登录！');location = '../index.php';</script>";
    } else {
        // 判断用户名和密码传输是否均不为空
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            // 引入连接数据库文件
            include "../../data/data.php";
            // 初始化连接数据库对象
            $myconn = new MyConnect();
            // 用户名
            $username = $_POST['username'];
            // 用户密码
            $password = $_POST['password'];
            // 查询条件-1
            // $d1 = "id,username,phone,reg_time,status,is_admin";
            // 查询条件-2 -----and password='" . md5($password) . "'
            // $d2 = "where username='$username'";
            $d2 = array(
                array(
                    'key' => 'account',
                    'operation' => '=',
                    'value' => $username
                )
            );
            // 调用函数查询
            $result = $myconn->inquire("users", null, $d2);
            // print_r($result);
            // die();
            // 判断登录
            switch ($result['code']) {
                case 200:
                    if ($result['data'][0]['password'] == md5($password)) {
                        switch ($result['data'][0]['status']) {
                            case 0:
                                $_SESSION['userinfo'] = $result['data'][0];
                                echo "<script>alert('登录成功！'); location = '../index.php';</script>";
                                break;
                            case 1:
                                echo "<script>alert('登录失败！账号因违规行为暂被冻结！原因：\\n" . $result['data'][0]['info'] . "');</script>";
                                break;
                            case 2:
                                echo "<script>alert('登录失败！账号因违规行为永久封禁！原因：\\n" . $result['data'][0]['info'] . "');</script>";
                                break;
                        }

                        // $_SESSION['userinfo'] = $result['data'][0];
                    } else {
                        echo "<script>alert('登录失败!密码错误!');</script>";
                    }
                    break;
                case 300:
                    $myconn->close();
                    echo "<script>alert('登录用户名不存在!')</script>";
                    break;
                default:
                    $myconn->close();
                    echo '<script>alert(\'登录失败！原因：\\n' . $result['desc'] . '\')</script>';
                    break;
            }
            // 关闭数据库
            $myconn->close();
        }
    }
    ?>
    <div class="box">
        <form action="./login.php" method="post">
            <!-- <img src="../../static/images/icon.png" alt="head-icon"> -->
            <h1>芝岸</h1>
            <div class="title">请输入用户名称:</div>
            <div class="content">
                <input type="text" id="username" name="username" value="<?php echo empty($_POST['username']) ? "" : $_POST['username'] ?>" />
            </div>
            <div class="title">请输入用户密码:</div>
            <div class="content">
                <input type="password" id="password" name="password" value="<?php echo empty($_POST['password']) ? "" : $_POST['password'] ?>" />
            </div>
            <div class="content">
                <input type="submit" id="submit" name="submit" />
            </div>
            <div class="content" style="padding-bottom:20px;">
                <a href="../../index.php">返回首页</a>
                <a href="../../register.php">注册账户</a>
            </div>
        </form>
    </div>
    <script>
        function byId(id) {
            return document.getElementById(id);
        }
        byId("submit").onclick = function() {
            let username = byId("username").value;
            let password = byId("password").value;
            if (username.length == 0 || password.length == 0) {
                alert("用户名或密码不能为空!");
                return false;
            } else if (username.indexOf(" ") >= 0 || password.indexOf(" ") >= 0) {
                alert("用户名或密码不能存在空格!");
                return false
            }
            return true;
        }
    </script>
</body>

</html>