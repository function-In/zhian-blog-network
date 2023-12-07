<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>博客列表 - 芝岸后台系统</title>
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
            padding: 0;
            background-color: #0D162D;
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

        .el-tabs--border-card {
            background-color: #182548;
            border: none;
        }

        .el-tabs--border-card>.el-tabs__header {
            background-color: #0D162D !important;
            border: none;
        }

        .el-tabs--border-card>.el-tabs__header .el-tabs__item.is-active {
            background-color: #182548;
            border: none;
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
                $tabkey = [3, '博客列表', '展示所有博客文章信息'];
                include "./component/Left.php";
                ?>
            </el-aside>
            <el-container>
                <el-header>
                    <?php include "./component/Header.php"; ?>
                </el-header>
                <el-main>
                    <div class="content">
                        <el-tabs type="border-card">
                            <el-tab-pane label="博客列表">
                                <div style="display: flex;">
                                    <div>
                                        <a href=""><el-button type="primary" size="small">刷新数据</el-button></a>
                                        <a href="./blog_add.php"><el-button type="primary" size="small">增加博客</el-button></a>
                                    </div>
                                    <div style="flex:1;margin-left:10px;display:flex;justify-content:flex-end;">
                                        <input type="text" class="searchInput" style="border: 1px solid grey;" placeholder="搜索..." v-model="searchText">
                                        <el-button type="primary" size="small" style="border: 1px solid grey;" @click='Search()'>搜索</el-button>
                                    </div>
                                </div>
                                <table>
                                    <tr class="header">
                                        <td>博客编号</td>
                                        <td>博客图标</td>
                                        <td>博客分类</td>
                                        <td>文章标题</td>
                                        <td>文章简介</td>
                                        <td>发布作者</td>
                                        <td>发布时间</td>
                                        <td>文章状态</td>
                                        <td>获赞</td>
                                        <td>被踩</td>
                                        <td>操作</td>
                                    </tr>
                                    <tr v-for='item in tableData'>
                                        <td>{{item.id}}</td>
                                        <td>
                                            <el-image style="width: 80px; height: 80px" :src="item.image" fit="fit" />
                                        </td>
                                        <td>{{item.class_id}}</td>
                                        <td>{{item.title}}</td>
                                        <td>{{item.desc}}</td>
                                        <td>{{item.user_id}}</td>
                                        <td>{{item.add_time}}</td>
                                        <td>{{item.status}}</td>
                                        <td>{{item.zan}}</td>
                                        <td>{{item.cai}}</td>
                                        <td>
                                            <el-button type="text" size="small" @click="BlogView(item)">查看</el-button>
                                            <el-button type="text" size="small" @click="BlogEdit(item)">编辑</el-button>
                                            <el-button type="text" size="small" @click="BlogDele(item)">删除</el-button>
                                        </td>
                                    </tr>
                                    <tr v-if='tableData.length==0'>
                                        <td style="text-align: center;">暂无数据</td>
                                    </tr>
                                </table>
                            </el-tab-pane>
                            <!-- <el-tab-pane label="未分类" title="未分类或该分类已删除">
                                <div style="display: flex;">
                                    <div>
                                        <a href=""><el-button type="primary" size="small">刷新数据</el-button></a>
                                        <a href="./blog_add.php"><el-button type="primary" size="small">增加博客</el-button></a>
                                    </div>
                                    <div style="flex:1;margin-left:10px;display:flex;justify-content:flex-end;">
                                        <input type="text" class="searchInput" style="border: 1px solid grey;" placeholder="搜索..." v-model="searchText">
                                        <el-button type="primary" size="small" style="border: 1px solid grey;" @click='Search()'>搜索</el-button>
                                    </div>
                                </div>
                            </el-tab-pane> -->
                        </el-tabs>
                    </div>
                </el-main>
                <el-footer>
                    <?php include './component/Footer.php' ?>
                </el-footer>
            </el-container>
        </el-container>

        <el-dialog title="" :visible.sync="dialogTableVisible">
            <h2 style="text-align: center;">{{BlogInfo.title}}</h2>
            <div style="border-bottom: 1px solid grey;padding-bottom:10px">
                作者：{{BlogInfo.author}}
                <br>
                分类：{{BlogInfo.type}}
                <br>
                发布日期：{{BlogInfo.time}}
                <span style="float: right;">获赞：{{BlogInfo.zan}} 被踩：{{BlogInfo.cai}}</span>
            </div>
            <div style="height:100%;padding: 10px;overflow:scroll;max-height: 500px;">
                {{BlogInfo.content}}
            </div>
        </el-dialog>
    </div>
    <script>
        new Vue({
            el: "#app",
            data: {
                isCollapse: false,
                searchText: '',
                tableData: [],
                BlogType: [],
                dialogTableVisible: false,
                BlogInfo: {}
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
                        message: `【全局搜索】功能暂未实现`
                    });
                },
                BlogView(row) {
                    this.BlogInfo = {
                        blog_id: row.id,
                        title: row.title,
                        author: row.user_id,
                        time: row.add_time,
                        zan: row.zan,
                        cai: row.cai,
                        content: row.content,
                        type: row.class_id
                    }
                    this.dialogTableVisible = true;
                },
                BlogEdit(row) {
                    window.location = './blog_edit.php?wid=' + row.id;
                },
                Search() {
                    if (this.searchText != '' && this.searchText.indexOf(' ') == -1) {
                        let data = new FormData();
                        data.append('type', 'BlogSearch');
                        data.append('data', this.searchText);
                        axios.post('../data/dataApi.php', data).then((result) => {
                            // console.log(result.data);
                            switch (result.data.code) {
                                case 200:
                                    this.tableData = [];
                                    let count = 0;
                                    result.data.data.forEach(e => {
                                        this.tableData.push(e);
                                        count++;
                                    });
                                    this.$message({
                                        type: 'success',
                                        message: '搜索成功！已搜索到 ' + count + ' 条用户信息'
                                    });
                                    break;
                                default:
                                    alert('发生了错误！原因：\n' + result.data.desc);
                                    window.location.reload();
                                    break;
                            }
                        }).catch((err) => {
                            alert('发生了错误！原因：\n' + err);
                        });
                    } else {
                        this.$message({
                            type: 'warning',
                            message: `【用户搜索】不能为空和存在空格！`
                        });
                    }
                },
                BlogDele(row) {
                    this.$confirm('将会删除该文章,是否继续?', '警告', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        let data = new FormData();
                        data.append('type', 'BlogDelete');
                        data.append('data', row.id);
                        axios.post('../data/dataApi.php', data).then((result) => {
                            // console.log(result.data);
                            switch (result.data.code) {
                                case 200:
                                    this.$message({
                                        type: 'success',
                                        message: '成功！已将文章删除!'
                                    });
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                    break;
                                default:
                                    alert('发生了错误！原因：\n' + result.data.desc);
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
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
                data.append('type', 'BlogList');
                axios.post('../data/dataApi.php', data).then((result) => {
                    // console.log(result.data);
                    switch (result.data.code) {
                        case 200:
                            this.tableData = result.data.data;
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