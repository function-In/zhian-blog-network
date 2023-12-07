<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>用户编辑 - 芝岸后台系统</title>
    <link rel="shortcut icon" href="./public/favicon.ico" type="image/x-icon" />
    <script src="./public/scripts/vue.js"></script>
    <script src="./public/scripts/axios.min.js"></script>
    <script src="./public/scripts/jquery-3.5.1.min.js"></script>
    <!-- Element-Ui -->
    <link rel="stylesheet" href="./public/styles/element.css">
    <script src="./public/scripts/element.js"></script>
    <!-- myStyle -->
    <link rel="stylesheet" href="./public/styles/style.css">
    <style>
        .el-form {
            background-color: #0D162D;
            margin: 20px;
            padding: 20px 0 20px 20px;
            box-shadow: 0 0 5px #0D162D;
        }

        .el-form .addPancel {
            display: flex;
            /* margin: 5px 0 5px 0; */
        }

        .el-form .el-form-item {
            width: auto;
        }

        .el-form .el-form-item .el-input__inner {
            background-color: #0D162D;
            border: 1px solid #434b5e;
            color: grey;
            height: 35px;
            border-radius: 0;
        }

        .el-picker-panel {
            background-color: #00264A;
            border: none;
        }

        .el-popper[x-placement^=bottom] .popper__arrow,
        .el-popper[x-placement^=bottom] .popper__arrow::after {
            border-bottom-color: #00264A;
        }

        .el-input-number .el-input-number__decrease,
        .el-input-number .el-input-number__increase {
            background-color: #0D162D;
            border: none;
        }

        .el-radio__input.is-checked .el-radio__inner {
            border: none;
        }

        .el-textarea {
            width: 70%;
            border: none;
        }

        .el-textarea .el-textarea__inner {
            background-color: #0D162D;
            border: 1px solid #434b5e;
            height: 100px;
            border-radius: 0;
        }

        .el-picker-panel__icon-btn {
            margin: 0 5px 0 5px;
            color: grey;
        }

        .el-button {
            color: grey;
            margin-bottom: 10px;
            background-color: #182548;
            border: none;
        }

        .el-button:hover {
            background-color: #80808034;
        }

        .el-page-header__left {
            display: inline;

        }

        .el-page-header__left i {
            padding: 10px;
        }

        .el-page-header__left i:hover {
            border-radius: 50%;
            background-color: #2877ff64;
            transition: all .3s ease;
        }

        .file {
            display: block;
            width: 100px;
            height: 100px;
            border: 2px dashed #434b5e;
            border-radius: 10px;
            position: relative;
        }


        .file i {
            width: 100%;
            height: 100%;
            line-height: 100px;
            text-align: center;
            font-size: x-large;
            color: grey;
        }

        .viso {
            display: none;
        }
    </style>
</head>

<body>
    <?php
    session_start();
    include "../data/data.php";
    if ($_GET['a'] == '' || $_GET['b'] == '') {
        echo "<script>alert('非法操作！');window.histroy.back();</script>";
    } else {
        $mycon = new MyConnect();
        $where = array(
            array('key' => 'id', 'operation' => '=', 'value' => $_GET['a'], 'next' => 'and'),
            array('key' => 'account', 'operation' => '=', 'value' => $_GET['b'])
        );
        $result = $mycon->inquire('users', array('*'), $where);
        if ($result['code'] != 200) echo "<script>alert('发生了错误！原因：\\n" . $result['desc'] . "');window.history.back();</script>";
        $result = $result['data'][0];
        // $content = $result['content'];
        // $content = str_replace("\r", "", str_replace("\n", "", str_replace("\"", "'", $content)));
        // $content = htmlentities($content);
        // print_r($result);
        // die();
        $mycon->close();
    }
    ?>
    <div id="app" class="app">
        <el-container>
            <el-aside width='1'>
                <?php
                $tabkey = [800, '编辑用户信息', '对一个用户账户信息进行编辑'];
                include "./component/Left.php";
                if (!isset($_GET['a']) || empty($_GET['a']) || !isset($_GET['b']) || empty($_GET['b'])) {
                    echo "<script>alert('非法操作！');location='./user_list.php';</script>";
                }
                ?>
            </el-aside>
            <el-container>
                <el-header>
                    <?php include "./component/Header.php"; ?>
                </el-header>
                <el-main>
                    <div class="content">
                        <el-form :model="table" :rules="rules" ref="table" label-width="100px">
                            <form action="./pages/user_update.php" method="post" name="formData" id="formData" target="_blank" enctype="multipart/form-data">
                                <div class="viso">
                                    <input type="text" name="id" value="<?php echo $result['id']; ?>" />
                                    <input type="text" name="account" value="<?php echo $result['account']; ?>" />
                                    <input type="text" name="nickname" :value="table.nickname" />
                                    <input type="text" name="phone" :value="table.phone" />
                                    <input type="text" name="email" :value="table.email" />
                                    <input type="text" name="sex" :value="table.sex" />
                                    <input type="text" name="birthday" :value="table.birthday" />
                                    <input type="text" name="admin" :value="table.identity" />
                                    <input type="text" name="status" :value="table.status" />
                                    <input type="text" name="info" :value="table.info" />
                                    <input type="text" name="desc" :value="table.desc" />
                                    <input type="text" name="pwd" :value="table.pwd1" />
                                    <!-- {{table.status}} -->
                                </div>
                                <div class="addPancel">
                                    <el-form-item label="文章封面：">
                                        <label for="file" class="file">
                                            <el-image v-if='table.image' style="width: 100%; height: 100%;border-radius:10px" :src="CreateImage()" fit="cover"></el-image>
                                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                            <input id="file" type="file" name="file" accept="image/*," style="display: none;" v-model="table.image">
                                        </label>
                                    </el-form-item>
                                    <el-form-item label="原封面：">
                                        <el-image style="width: 200px; height: 200px;border-radius:10px" src="<?php echo $result['image']; ?>" fit="cover"></el-image>
                                    </el-form-item>
                                </div>
                                <div class="addPancel">
                                    <el-form-item label="用户账号：" prop="account" title='账号注册后不可编辑'>
                                        <el-input v-model="table.account" disabled />
                                    </el-form-item>
                                    <el-form-item label="用户昵称：" prop="nickname">
                                        <el-input v-model="table.nickname" />
                                    </el-form-item>
                                </div>

                                <div class="addPancel">
                                    <el-form-item label="注册手机：" prop="phone">
                                        <el-input v-model.number="table.phone" />
                                    </el-form-item>
                                    <el-form-item label="绑定邮箱：" prop="email">
                                        <el-input v-model.email="table.email" />
                                    </el-form-item>
                                </div>


                                <div class="addPancel">
                                    <el-form-item label="用户性别：">
                                        <el-radio-group v-model="table.sex">
                                            <el-radio label="0">不便透露</el-radio>
                                            <el-radio label="1">男</el-radio>
                                            <el-radio label="2">女</el-radio>
                                        </el-radio-group>
                                    </el-form-item>
                                </div>

                                <div class="addPancel">
                                    <el-form-item label="出生日期：">
                                        <input type="date" name="birthday" id="" v-model="table.birthday">
                                        <!-- <el-date-picker type="date" placeholder="选择日期" v-model="table.birthday" /> -->
                                    </el-form-item>
                                </div>

                                <div class="addPancel">
                                    <el-form-item label="用户身份：">
                                        <el-radio-group v-model="table.identity">
                                            <el-radio label="0">普通用户</el-radio>
                                            <el-radio label="1">管理成员</el-radio>
                                        </el-radio-group>
                                    </el-form-item>
                                </div>

                                <div class="addPancel">
                                    <el-form-item label="账号状态：">
                                        <el-radio-group v-model="table.status">
                                            <el-radio label="0">正常</el-radio>
                                            <el-radio label="1">冻结</el-radio>
                                            <el-radio label="2">封禁</el-radio>
                                        </el-radio-group>
                                    </el-form-item>
                                </div>

                                <div class="addPancel" v-if="table.status==1||table.status==2">
                                    <el-form-item label="异常信息 ：" style="width: 50%;">
                                        <el-input v-model="table.info" />
                                    </el-form-item>
                                </div>

                                <div class="addPancel" style="width: 100%;">
                                    <el-form-item label="个人签名：" style="width: 80%;">
                                        <el-input type="textarea" v-model="table.desc" />
                                    </el-form-item>
                                </div>
                                <div class="addPancel">
                                    <el-form-item label="密码提示：">
                                        <span style="color: grey;">
                                            留空密码框，则不进行更改密码
                                        </span>
                                    </el-form-item>
                                </div>
                                <div class="addPancel">
                                    <el-form-item label="注册密码：" prop="pwd1">
                                        <el-input v-model="table.pwd1" show-password></el-input>
                                    </el-form-item>
                                    <el-form-item label="确认密码：" prop="pwd2">
                                        <el-input v-model="table.pwd2" show-password></el-input>
                                    </el-form-item>
                                </div>

                                <el-form-item>
                                    <el-button type="cannel" @click="onSubmit('table')">修改用户信息</el-button>
                                    <el-button type="cannel" @click="onCancel()">取消修改</el-button>
                                </el-form-item>
                            </form>
                        </el-form>
                    </div>
                </el-main>
                <el-footer>
                    <?php include './component/Footer.php' ?>
                </el-footer>
            </el-container>
        </el-container>
    </div>
    <script>
        new Vue({
            el: "#app",
            data() {
                return {
                    isCollapse: false,
                    table: {
                        image: null,
                        account: '<?php echo $result['account']; ?>',
                        nickname: '<?php echo $result['nickname']; ?>',
                        phone: <?php echo $result['phone']; ?>,
                        email: '<?php echo $result['email']; ?>',
                        sex: '<?php echo $result['sex']; ?>',
                        birthday: <?php echo $result['birthday']; ?>,
                        identity: '<?php echo $result['is_admin']; ?>',
                        status: '<?php echo $result['status']; ?>',
                        info: '<?php echo $result['info']; ?>',
                        desc: '<?php echo $result['desc']; ?>',
                        pwd1: '',
                        pwd2: ''
                    },
                    rules: {
                        account: [{
                            required: true,
                            trigger: 'blur',
                            validator: (a, b, c) => {
                                var reg = new RegExp("[\\u4E00-\\u9FFF]+", "g");
                                if (!b) c(new Error("账号不能为空"));
                                else if (reg.test(b)) c(new Error('不能输入中文'));
                                else if (b.indexOf(" ") > -1) c(new Error('不能输入空格'));
                                else c();
                            }
                        }],
                        nickname: [{
                            required: true,
                            type: 'string',
                            message: '昵称不能为空',
                            trigger: 'blur'
                        }],
                        phone: [{
                            required: true,
                            trigger: 'blur',
                            validator: (a, b, c) => {
                                var reg1 = /^[1][3-9][0-9]{9}$/
                                if (!Number.isInteger(b)) c(new Error('您只能输入数字'));
                                else if (!reg1.test(b)) c(new Error('号码格式不正确'));
                                else c();
                            }
                        }],
                        email: [{
                            required: true,
                            trigger: 'blur',
                            validator: (a, b, c) => {
                                let reg = /^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/
                                var reg2 = new RegExp("[\\u4E00-\\u9FFF]+", "g");
                                if (!b) c(new Error("邮箱不能为空"));
                                else if (reg2.test(b)) c(new Error('不能输入中文'));
                                else if (b.indexOf(" ") > -1) c(new Error('不能输入空格'));
                                else if (!reg.test(b)) c(new Error('邮箱格式不正确'));
                                else c();
                            }
                        }],
                        birthday: [{
                            type: 'date',
                            required: true,
                            message: '请选择日期',
                            trigger: 'change'
                        }],
                        pwd1: [{
                            trigger: 'blur',
                            validator: (a, b, c) => {
                                var reg = new RegExp("[\\u4E00-\\u9FFF]+", "g");
                                if (b && reg.test(b)) c(new Error('不能输入中文'));
                                else if (b && b.length < 6) c(new Error('密码长度低于 5 位'));
                                else c();
                            }
                        }],
                        pwd2: [{
                            trigger: 'blur',
                            validator: (a, b, c) => {
                                if (b != this.table.pwd1) c(new Error('确认密码不一致'));
                                else c();
                            }
                        }]
                    },
                }

            },
            methods: {
                getDate(time) {
                    let date = new Date(parseInt(time));
                    let Y = date.getFullYear() + '-';
                    let M = (date.getMonth() + 1 < 10 ? "0" + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
                    let D = date.getDate() < 10 ? "0" + date.getDate() : date.getDate();
                    return Y + M + D;
                },
                switchMode() {
                    this.$message({
                        type: 'warning',
                        message: `【黑白模式】功能暂未实现`
                    });
                },
                openMessage() {
                    this.$message({
                        type: 'warning',
                        message: `【通知中心】功能暂未实现`
                    });
                },
                openSearch() {
                    this.$message({
                        type: 'warning',
                        message: `【搜索功能】功能暂未实现`
                    });
                },
                onCancel() {
                    window.history.back();
                },
                validFileType(file) {
                    const fileTypes = [
                        "image/apng",
                        "image/bmp",
                        "image/gif",
                        "image/jpeg",
                        "image/pjpeg",
                        "image/png",
                        "image/svg+xml",
                        "image/tiff",
                        "image/webp",
                        "image/x-icon"
                    ];
                    return fileTypes.includes(file.type);
                },
                CreateImage() {
                    let input = document.getElementById('file');
                    for (const file of input.files)
                        if (this.validFileType(file)) return URL.createObjectURL(file);
                    return '';
                },
                onSubmit(a) {
                    this.$refs[a].validate((b) => {
                        if (b) document.formData.submit();
                    });
                }

            }
        });
    </script>
</body>

</html>