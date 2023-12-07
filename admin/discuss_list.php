<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>评论列表 - 芝岸后台系统</title>
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
        .content {
            padding: 20px;
            background-color: #0D162D;
        }

        .el-table {
            border: 1px solid #434b5e;
            max-height: 800px;
        }

        .el-table--border::after,
        .el-table--group::after,
        .el-table::before {
            display: none;
        }

        .el-table td.el-table__cell,
        .el-table th.el-table__cell.is-leaf {
            background-color: #0D162D;
            border: 1px solid #434b5e;
        }

        .el-table--enable-row-hover .el-table__body tr:hover>td.el-table__cell {
            background-color: #182548;
        }

        /* 弹出框 */
        .el-message-box {
            background-color: #0D162D;
            border: none;
        }

        .el-message-box .el-message-box__title {
            color: grey !important;
        }

        .el-message-box .el-button {
            background-color: #00264A !important;
            border: none;
            color: grey !important;
        }

        .el-message-box .el-button:hover {
            color: white !important;
        }

        .el-dialog {
            background-color: #0D162D;
        }

        .el-dialog .el-dialog__title {
            color: grey;
        }

        .el-table__empty-block {
            background-color: #0D162D;
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
                $tabkey = [9, '评论列表管理', '展示所有文章的评论（留言）'];
                include "./component/Left.php";
                ?>
            </el-aside>
            <el-container>
                <el-header>
                    <?php include "./component/Header.php"; ?>
                </el-header>
                <el-main>
                    <div class="content">
                        <div style="display: flex;">
                            <div>
                                <a href=""><el-button type="primary" size="small">刷新数据</el-button></a>
                                <!-- <a href="./user_add.php"><el-button type="primary" size="small">增加用户</el-button></a> -->
                            </div>
                            <div style="flex:1;margin-left:10px;display:flex;justify-content:flex-end;">
                                <input type="text" class="searchInput" placeholder="搜索..." v-model="searchText">
                                <el-button type="primary" size="small" @click='Search()'>搜索</el-button>
                            </div>
                        </div>
                        <table>
                            <tr class="header">
                                <td>评论编号</td>
                                <td>评论用户</td>
                                <td>所属文章</td>
                                <td>获赞</td>
                                <td>被踩</td>
                                <td>评论时间</td>
                                <td>评论内容</td>
                                <td>操作</td>
                            </tr>
                            <tr v-for='item in tableData'>
                                <td>{{item.id}}</td>
                                <td>{{item.user_id}}</td>
                                <td>{{item.blog_id}}</td>
                                <td>{{item.zan}}</td>
                                <td>{{item.cai}}</td>
                                <td>{{item.add_time}}</td>
                                <td>{{item.text}}</td>
                                <td>
                                    <!-- <el-button type="text" size="small" @click="DiscussView(item)">查看文章</el-button> -->
                                    <el-button type="text" size="small" @click="DiscussDele(item)">删除评论</el-button>
                                </td>   
                            </tr>
                            <tr v-if='tableData.length==0'>
                                <td style="text-align: center;">暂无数据</td>
                            </tr>
                        </table>
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
                tableData: [],
                searchText: ''
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
                Search() {
                    alert(this.searchText);
                },
                DiscussView(row) {
                    console.log(row);
                },
                DiscussDele(row) {
                    this.$confirm('将会删除该评论,是否继续?', '警告', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        let data = new FormData();
                        data.append('type', 'DiscussDelete');
                        data.append('data', row.id);
                        axios.post('../data/dataApi.php', data).then((result) => {
                            switch (result.data.code) {
                                case 200:
                                    this.$message({
                                        type: 'success',
                                        message: '删除成功!'
                                    });
                                    break;
                                default:
                                    alert('发生了错误！原因：\n' + result.data.desc);
                                    break;
                            }
                        }).catch((err) => {
                            alert('发生了错误！原因：\n' + err);
                        });
                    }).catch(() => {
                        this.$message({
                            type: 'info',
                            message: '已取消删除'
                        });
                    });
                }
            },
            mounted() {
                let data = new FormData();
                data.append('type', 'getAllDiscuss');
                axios.post('../data/dataApi.php', data).then((result) => {
                    // console.log(result.data);
                    switch (result.data.code) {
                        case 200:
                            this.tableData = result.data.data;
                            break;
                        default:
                            alert('发生了错误！原因：\n' + result.data.desc);
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