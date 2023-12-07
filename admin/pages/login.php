<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录</title>
    <script src="./static/scripts/vue.js"></script>
    <script src="./static/scripts/axios.min.js"></script>
    <script src="./static/scripts/jquery-3.5.1.min.js"></script>
    <link rel="shortcut icon" href="./admin/public/favicon.ico" type="image/x-icon" />
    <script src="./admin/public/scripts/element.js"></script>
    <link rel="stylesheet" href="./admin/public/styles/element.css">
    <style>
        * {
            padding: 0;
            margin: 0;
            user-select: none;
            transition: all .3s ease;
        }


        .app {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-image: url('../../static/images/background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            z-index: 1;
        }

        .box {
            width: 80%;
            min-width: 380px;
            max-width: 900px;
            background-color: white;
            text-align: center;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
            background-color: rgba(0, 0, 0, 0.5);
            padding-top: 50px;
            color: white;
            border-radius: 5px;

        }

        .box .title {
            margin: 20px 0 20px 0;
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

        input {
            color: white;
            background-color: rgba(0, 0, 0, 0.2);
            border: none;
            outline: none;
        }

        input:hover,
        input:focus {
            color: aqua;
            background-color: rgba(255, 255, 255, 0.3);
        }

        input[type=submit] {
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
        }

        a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <?php
    session_start();
    $toLocation = ($_GET['url'] != '') ? ($_GET['url'] . '&wid=' . $_GET['wid']) : '';
    // echo md5('root');
    if (isset($_SESSION['userinfo'])) {
        echo "<script>alert('您已进行登录！');location = '../index.php';</script>";
    }
    ?>
    <div class="app" id="app">
        <div class="box">
            <h1>芝岸</h1>
            <div class="title">请输入用户名称:</div>
            <span>（用户编号，用户账户，手机号码）</span>
            <div class="content">
                <input type="text" id="username" name="username" value="" v-model="username" />
            </div>
            <div class="title">请输入用户密码:</div>
            <div class="content">
                <input type="password" id="password" name="password" value="" v-model="password" />
            </div>
            <div class="content">
                <input type="submit" id="submit" name="submit" @click="login()" />
            </div>
            <div class="content" style="padding-bottom:20px;">
                <a href="../../index.php">返回首页</a>
                <a href="../../register.php">注册账户</a>
            </div>
        </div>
    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                username: '',
                password: ''
            },
            methods: {
                login() {
                    if (this.username.replaceAll(' ', '') == '' || this.password.replaceAll(' ', '') == '') {
                        alert('用户名称或用户密码输入为空！');
                    } else {
                        let data = new FormData();
                        data.append('username', this.username);
                        data.append('password', this.password);
                        axios.post('./data/dataApi.php?mt=login', data).then((result) => {
                            // console.log(result.data);
                            switch (result.data.code) {
                                case 200:
                                    alert('登录成功！');
                                    window.location.href = '<?php echo $toLocation != '' ? $toLocation : './index.php'; ?>';
                                    break;
                                case 201:
                                    alert('登陆失败！您已进行登录，不可再次登录！');
                                    window.location.href = '<?php echo $toLocation != '' ? $toLocation : './index.php'; ?>';
                                    break;
                                case 202:
                                    alert("密码输入错误！");
                                    break;
                                default:
                                    alert('发生了一些错误！原因\n' + result.data.desc);
                                    break;
                            }
                        }).catch((err) => {
                            alert('发生了错误！原因：\n' + err)
                        });
                    }
                }
            }
        });
    </script>
</body>

</html>