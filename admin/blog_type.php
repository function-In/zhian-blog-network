<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>博客分类 - 芝岸后台系统</title>
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

        td input {
            background-color: transparent;
            width: 100%;
            height: 100%;
            line-height: 40px;
            border: none;
            outline: none;
            color: grey;
            padding: 0;
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
                $tabkey = [6, '博客分类', '展示博客相关的分类'];
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
                            <el-tab-pane label="博客分类列表">
                                <div style="display: flex;">
                                    <div>
                                        <a href=""><el-button type="primary" size="small">刷新数据</el-button></a>
                                        <el-button type="primary" size="small" @click="TypeAdd()">增加分类</el-button>
                                    </div>
                                    <div style="flex:1;margin-left:10px;display:flex;justify-content:flex-end;">
                                        <input type="text" class="searchInput" style="border: 1px solid grey;" placeholder="搜索..." v-model="searchText">
                                        <el-button type="primary" size="small" style="border: 1px solid grey;" @click='Search()'>搜索</el-button>
                                    </div>
                                </div>
                                <table>
                                    <tr class="header">
                                        <td>分类编号</td>
                                        <td>分类名称</td>
                                        <td>排序</td>
                                        <td>状态</td>
                                        <td>操作</td>
                                    </tr>
                                    <tr v-for='item in tableData'>
                                        <td>{{item.id}}</td>
                                        <td>{{item.class_name}}</td>
                                        <td>{{item.sort}}</td>
                                        <td>{{item.status}}</td>
                                        <td>
                                            <el-button type="text" size="small" @click="TypeEdit(item)">编辑</el-button>
                                            <el-button type="text" size="small" @click="TypeDele(item)">删除</el-button>
                                        </td>
                                    </tr>
                                    <tr v-if='tableData.length==0'>
                                        <td style="text-align: center;">暂无数据</td>
                                    </tr>
                                </table>
                            </el-tab-pane>
                            <!-- <el-tab-pane label="待审核">2
                            </el-tab-pane>
                            <el-tab-pane label="违规博客">3
                            </el-tab-pane> -->
                        </el-tabs>
                    </div>
                </el-main>
                <el-footer>
                    <?php include './component/Footer.php' ?>
                </el-footer>
            </el-container>
        </el-container>

        <el-dialog title="编辑信息" :visible.sync="editPancel">
            <table>
                <tr class="header">
                    <td>分类编号</td>
                    <td>分类名称</td>
                    <td>排序</td>
                    <td>状态</td>
                </tr>
                <tr>
                    <td><input type="number" v-model="MyEditInfo.id"></td>
                    <td><input type="text" v-model="MyEditInfo.class_name"></td>
                    <td><input type="number" v-model="MyEditInfo.sort"></td>
                    <td><input type="number" v-model="MyEditInfo.status"></td>
                </tr>
            </table>
            <div style="margin-top: 10px;">
                <el-button type="primary" size="small" @click="editFunction()">修改分类</el-button>
                <el-button type="danger" size="small" @click="editPancel=false">放弃</el-button>
            </div>
        </el-dialog>

        <el-dialog title="增加分类" :visible.sync="addPancel">
            <table>
                <tr class="header">
                    <td>分类编号</td>
                    <td>分类名称</td>
                    <td>排序</td>
                    <td>状态</td>
                </tr>
                <tr>
                    <!-- {{MyEditInfo}} -->
                    <td><input type="number" v-model="MyEditInfo.id"></td>
                    <td><input type="text" v-model="MyEditInfo.class_name"></td>
                    <td><input type="number" v-model="MyEditInfo.sort"></td>
                    <td><input type="number" v-model="MyEditInfo.status"></td>
                </tr>
            </table>
            <div style="margin-top: 10px;">
                <el-button type="primary" size="small" @click="addFunction()">新增分类</el-button>
                <el-button type="danger" size="small" @click="addPancel=false">放弃</el-button>
            </div>
        </el-dialog>
    </div>
    <script>
        new Vue({
            el: "#app",
            data: {
                searchText: '',
                isCollapse: false,
                tableData: [],
                addPancel: false,
                editPancel: false,
                EditInfo: {},
                MyEditInfo: {}
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
                TypeAdd() {
                    this.MyEditInfo = {};
                    this.EditInfo = {};
                    this.addPancel = true;
                },
                addFunction() {
                    let data = new FormData();
                    data.append('type', 'BlogTypeAdd');
                    data.append('data', JSON.stringify(this.MyEditInfo));
                    axios.post('../data/dataApi.php', data).then((result) => {
                        // console.log(result.data);
                        if (result.data.code != null) {
                            switch (result.data.code) {
                                case 200:
                                    this.$message({
                                        type: 'success',
                                        message: '添加一个分类修改成功!'
                                    });
                                    this.editPancel = false;
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                    break;
                                default:
                                    alert('发生了错误！原因：\n' + result.data.desc)
                                    break;
                            }
                        }
                    }).catch((err) => {
                        alert("发生了错误！原因：\n" + err);
                    });
                },
                TypeEdit(row) {
                    this.MyEditInfo = {
                        id: row.id,
                        class_name: row.class_name,
                        sort: row.sort,
                        status: row.status
                    };
                    this.EditInfo = row;
                    this.editPancel = true;
                },
                editFunction() {
                    let data = new FormData();
                    data.append('type', 'BlogTypeUpdate');
                    data.append('data1', JSON.stringify(this.MyEditInfo));
                    data.append('data2', this.EditInfo.id)
                    axios.post('../data/dataApi.php', data).then((result) => {
                        // console.log(result.data);
                        if (result.data.code != null) {
                            switch (result.data.code) {
                                case 200:
                                    this.$message({
                                        type: 'success',
                                        message: '分类信息修改成功!'
                                    });
                                    this.editPancel = false;
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                    break;
                                default:
                                    alert('发生了错误！原因：\n' + result.data.desc)
                                    break;
                            }
                        }
                    }).catch((err) => {
                        alert("发生了错误！原因：\n" + err);
                    });
                },
                TypeDele(row) {
                    this.$confirm('将会删除该账户,是否继续?', '警告', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        let data = new FormData();
                        data.append('type', 'BlogTypeDelete');
                        data.append('data', row.id)
                        axios.post('../data/dataApi.php', data).then((result) => {
                            // console.log(result.data);
                            switch (result.data.code) {
                                case 200:
                                    this.$message({
                                        type: 'success',
                                        message: '删除成功!'
                                    });
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
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
                },
                Search() {
                    if (this.searchText != '' && this.searchText.indexOf(' ') == -1) {
                        let data = new FormData();
                        data.append('type', 'BlogTypeSearch');
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
                                        message: '搜索成功！已搜索到 ' + count + ' 条分类信息'
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
                            message: `【分类搜索】不能为空和存在空格！`
                        });
                    }
                },
            },
            mounted() {
                let data = new FormData();
                data.append('type', 'BlogTypeList');
                axios.post('../data/dataApi.php', data).then((result) => {
                    switch (result.data.code) {
                        case 200:
                            this.tableData = [];
                            result.data.data.forEach(e => {
                                this.tableData.push(e);
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