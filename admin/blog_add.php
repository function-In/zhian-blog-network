<!DOCTYPE html>
<html lang="zh-CN">


<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>添加博客 - 芝岸后台系统</title>
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
            /* margin: 20px; */
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
            height: 300px;
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

        .el-button:focus,
        .el-button:hover {
            background-color: #80808034;
        }

        .desc .el-textarea__inner {
            height: 80px;
        }


        /* 封面框 */

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

        .submit {
            /* display: block; */
            padding: 5px 15px 5px 15px;
            margin: 0 5px 0 5px;
            font-size: medium;
            color: grey;
            margin-bottom: 10px;
            background-color: #182548;
            border: none;
            transition: all .3s ease;
            cursor: pointer;
        }

        .submit:focus,
        .submit:hover {
            background-color: #80808034;
            transition: all .3s ease;
        }

        select {
            display: block;
            width: 300px;
            height: 35px;
            border: 1px solid #434b5e;
            background-color: #0D162D;
            color: grey;

        }
    </style>
</head>

<body>
    <?php
    session_start();
    ?>
    <div id="app" class="app">
        <el-container>
            <el-aside width="1">
                <?php
                $tabkey = [4, '博客增加', '新增一个博客'];
                include "./component/Left.php";
                ?>
            </el-aside>
            <el-container>
                <el-header><?php include "./component/Header.php"; ?></el-header>
                <el-main>
                    <div class="content">
                        <el-form :model="table" :rules="rules" ref="table" label-width="100px">
                            <form action="./pages/blog_add.php" method="post" name="formData" id="formData" target="_blank" enctype="multipart/form-data">
                                <!-- 隐藏数据 -->
                                <input type="text" name="user" value="<?php echo $_SESSION['userinfo']['id'] ?>" style="display: none;" />
                                <input type="text" name="type" :value="table.type" style="display: none;" />
                                <!-- 封面 -->
                                <div class="addPancel">
                                    <el-form-item label="文章封面：">
                                        <label for="file" class="file">
                                            <el-image v-if='table.image' style="width: 100%; height: 100%;border-radius:10px" :src="CreateImage()" fit="cover"></el-image>
                                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                            <input id="file" type="file" name="file" accept="image/*," style="display: none;" v-model="table.image">
                                        </label>
                                    </el-form-item>
                                </div>
                                <!-- 标题 -->
                                <div class="addPancel">
                                    <el-form-item label="文章标题：" prop="title">
                                        <el-input v-model="table.title" name="title" />
                                    </el-form-item>
                                </div>
                                <!-- 分类 -->
                                <div class="addPancel">
                                    <el-form-item label="文章分类：" prop="type">
                                        <el-select v-model="table.type" placeholder="请选择分类">
                                            <el-option v-for="item,index in options" :label="item.class_name" :value="item.id" />
                                        </el-select>
                                    </el-form-item>
                                </div>
                                <!-- 简介 -->
                                <div class="addPancel">
                                    <el-form-item label="文章简介：" prop="desc" style="width: 80%;">
                                        <el-input type="textarea" v-model="table.desc" name="desc" class="desc" />
                                    </el-form-item>
                                </div>
                                <!-- 内容 -->
                                <div class="addPancel" style="width: 100%;">
                                    <el-form-item label="文章内容：" style="width: 80%;" prop="content">
                                        <el-input type="textarea" v-model="table.content" name="content" />
                                    </el-form-item>
                                </div>
                                <!-- 提交 & 重置 -->
                                <el-form-item>
                                    <el-button type="button" @click="onSubmit('table')">添加博客</el-button>
                                    <el-button type="button" @click="reset()">
                                        重置信息
                                        <input type="reset" class="submit" value="重置信息" id="reset" style="display: none;" />
                                    </el-button>
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
            data: {
                isCollapse: false,
                table: {
                    image: null,
                    title: '',
                    type: '',
                    desc: '',
                    content: ''
                },
                options: [],
                rules: {
                    title: [{
                        required: true,
                        trigger: 'blur',
                        validator: (a, b, c) => {
                            if (!b) c(new Error("文章不能为空"));
                            else if (b.indexOf(" ") > -1) c(new Error('不能输入空格'));
                            else c();
                        }
                    }],
                    type: [{
                        required: true,
                        trigger: 'change',
                        validator: (a, b, c) => {
                            if (!b) c(new Error('未选择分类'));
                            else c();
                        }
                    }],
                    desc: [{
                        required: true,
                        trigger: 'change',
                        validator: (a, b, c) => {
                            if (!b) c(new Error("简介不能为空"));
                            else c();
                        }
                    }],
                    content: [{
                        required: true,
                        trigger: 'change',
                        validator: (a, b, c) => {
                            if (!b) c(new Error("文章不能为空"));
                            else c();
                        }
                    }],
                },
            },
            methods: {
                reset() {
                    let x = document.getElementById("reset")
                    x.click()
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
                        if (this.validFileType(file))
                            return URL.createObjectURL(file);
                    return '';
                },
                switchMode() {
                    this.$message({
                        type: 'error',
                        message: `【白天/黑夜模式】功能暂未实现`
                    });
                },
                openMessage() {
                    this.$message({
                        type: 'warning',
                        message: `【消息通知】功能暂未实现`
                    });
                },
                openSearch() {
                    this.$message({
                        type: 'warning',
                        message: `【消息通知】功能暂未实现`
                    });
                },
                onSubmit(table) {
                    this.$refs[table].validate((b) => {
                        if (b) {
                            document.formData.submit();
                            window.history.back();
                        }
                    });
                }
            },
            mounted() {
                let data = new FormData();
                data.append('type', 'BlogTypeList');
                axios.post('../data/dataApi.php', data).then((result) => {
                    // console.log(result.data);
                    switch (result.data.code) {
                        case 200:
                            this.options = [];
                            result.data.data.forEach(e => {
                                this.options.push(e);
                            });
                            break;
                        default:
                            alert('发生了错误！原因：\n' + result.data.desc)
                            break;
                    }
                }).catch((err) => {
                    alert('发生了错误！原因：\n' + err)
                });
            },
        });
    </script>
</body>

</html>