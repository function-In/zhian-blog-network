<!DOCTYPE html>
<html lang="zh-CN">


<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>后台概览 - 芝岸后台系统</title>
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
        .el-button {
            background-color: transparent;
            /* padding: 10px 10px 10px 10px !important; */
            /* margin: 0 !important; */
        }

        /* .el-button:hover { */
        /* background-color: transparent !important; */
        /* } */

        .el-carousel__item {
            border-radius: 4px;
            background-color: #182548;
        }

        /* .el-carousel__item h3 {
            color: grey;
            font-size: 18px;
            opacity: 0.75;
            line-height: 300px;
            margin: 0;
        } */

        /* .el-carousel__item:nth-child(2n) {
            background-color: #99a9bf;
        }

        .el-carousel__item:nth-child(2n+1) {
            background-color: #d3dce6;
        } */

        .like {
            cursor: pointer;
            font-size: 25px;
            display: inline-block;
        }

        .HeadBox {
            background-color: #182548;
            padding: 30px;
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
                $tabkey = [0, '后台概览', '后台数据信息统计及概况'];
                include "./component/Left.php";
                ?>
            </el-aside>
            <el-container>
                <el-header>
                    <?php include "./component/Header.php"; ?>
                </el-header>
                <el-main>
                    <div class="content">

                        <el-carousel indicator-position="outside">
                            <el-carousel-item v-for="item in 4" :key="item">
                                <h3>{{ item }}</h3>
                            </el-carousel-item>
                        </el-carousel>

                        <!-- 数字三个分割符号 group-separator="," -->
                        <!-- 小数点精度设置：:precision="1" -->
                        <!-- 间距 :span="10" -->

                        <div class="HeadBox">
                            <el-row style="display: flex;">
                                <el-col>
                                    <el-statistic :value="23" title="博客总数"></el-statistic>
                                </el-col>
                                <el-col>
                                    <el-statistic :value="22" title="评论总数"></el-statistic>
                                </el-col>
                                <el-col>
                                    <el-statistic :value="222" title="用户总数"></el-statistic>
                                </el-col>
                            </el-row>
                        </div>
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
                carousel: []
            },
            methods: {
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
                }
            }
        });
    </script>
</body>

</html>