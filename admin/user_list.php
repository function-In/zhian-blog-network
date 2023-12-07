<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>用户管理 - 芝岸后台系统</title>
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
                $tabkey = [7, '用户列表', '展示所有用户账户信息'];
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
                                <a href="./user_add.php"><el-button type="primary" size="small">增加用户</el-button></a>
                            </div>
                            <div style="flex:1;margin-left:10px;display:flex;justify-content:flex-end;">
                                <input type="text" class="searchInput" placeholder="搜索..." v-model="searchText">
                                <el-button type="primary" size="small" @click='Search()'>搜索</el-button>
                            </div>
                        </div>
                        <table>
                            <tr class="header">
                                <td>用户编号</td>
                                <td>用户账户</td>
                                <td>用户昵称</td>
                                <td>用户手机</td>
                                <td>用户邮箱</td>
                                <td>用户身份</td>
                                <td>注册时间</td>
                                <td>帐号状态</td>
                                <td>操作</td>
                            </tr>
                            <tr v-for='item in tableData'>
                                <td>{{item.a}}</td>
                                <td>{{item.b}}</td>
                                <td>{{item.c}}</td>
                                <td>{{item.d}}</td>
                                <td>{{item.e}}</td>
                                <td>{{item.f}}</td>
                                <td>{{item.g}}</td>
                                <td>{{item.h}}</td>
                                <td>
                                    <el-button type="text" size="small" @click="UserView(item)">查看</el-button>
                                    <el-button type="text" size="small" @click="UserEdit(item)">编辑</el-button>
                                    <el-button type="text" size="small" @click="UserDele(item)">删除</el-button>
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
        <el-dialog title="用户信息查看" :visible.sync="dialogTableVisible">
            <table v-for='item in UserInfo'>
                <tr class="header">
                    <td>用户头像</td>
                    <td>
                        <el-avatar shape="square" size="large" :src="item.image"></el-avatar>
                    </td>
                </tr>
                <tr class="header">
                    <td>用户编号</td>
                    <td>用户账户</td>
                    <td>用户昵称</td>
                    <td>用户性别</td>
                </tr>
                <tr>
                    <td>{{item.id}}</td>
                    <td>{{item.account}}</td>
                    <td>{{item.name}}</td>
                    <td>{{item.sex}}</td>
                </tr>
                <tr class="header">
                    <td>手机号码</td>
                    <td>用户邮箱</td>
                </tr>
                <tr>
                    <td>{{item.phone}}</td>
                    <td>{{item.email}}</td>
                </tr>
                <tr class="header">
                    <td>用户生日</td>
                    <td>用户身份</td>
                    <td>注册时间</td>
                    <td>帐号状态</td>
                </tr>
                <tr>
                    <td>{{item.birthday}}</td>
                    <td>{{item.admin}}</td>
                    <td>{{item.rtime}}</td>
                    <td>{{item.status}}</td>
                </tr>
                <tr class="header">
                    <td>封禁信息</td>
                </tr>
                <tr>
                    <td>{{item.info}}</td>
                </tr>
                <tr class="header">
                    <td>个人签名</td>
                </tr>
                <tr>
                    <td>{{item.desc}}</td>
                </tr>
                <tr class="header">
                    <td>操作</td>
                </tr>
                <tr>
                    <td>
                        <el-button type="text" size="small" @click="UserEdit({a:item.id,b:item.account})">编辑</el-button>
                        <el-button type="text" size="small" @click="UserDele({a:item.id,b:item.account})">删除</el-button>
                    </td>
                </tr>
            </table>
        </el-dialog>
    </div>
    <script>
        new Vue({
            el: "#app",
            data: {
                isCollapse: false,
                tableData: [],
                dialogTableVisible: false,
                UserInfo: [],
                searchText: '',
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
                    if (this.searchText != '' && this.searchText.indexOf(' ') == -1) {
                        let data = new FormData();
                        data.append('type', 'UserSearch');
                        data.append('data', this.searchText);
                        axios.post('../data/dataApi.php', data).then((result) => {
                            // console.log(result.data);
                            switch (result.data.code) {
                                case 200:
                                    this.tableData = [];
                                    let count = 0;
                                    result.data.data.forEach(e => {
                                        let user = {
                                            a: parseInt(e.id),
                                            b: e.account,
                                            c: e.nickname,
                                            d: e.phone,
                                            e: e.email,
                                            f: e.is_admin == 0 ? '普通用户' : '管理员',
                                            g: this.getDate(e.reg_time),
                                            h: e.status == 0 ? '正常' : e.status == 1 ? '冻结' : '封禁',
                                            sex: e.sex,
                                            birthday: e.birthday,
                                            info: e.info,
                                            desc: e.mydesc
                                        }
                                        this.tableData.push(user);
                                        count++;
                                    });
                                    this.$message({
                                        type: 'success',
                                        message: '搜索成功！已搜索到 ' + count + ' 条用户信息'
                                    });
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
                    } else {
                        this.$message({
                            type: 'warning',
                            message: `【用户搜索】不能为空和存在空格！`
                        });
                    }
                },
                UserView(row) {
                    this.UserInfo = [{
                        id: row.a,
                        account: row.b,
                        name: row.c,
                        sex: row.sex == 0 ? '不便透露' : row.sex == 1 ? '男' : '女',
                        phone: row.d,
                        email: row.e,
                        birthday: this.getDate(row.birthday),
                        admin: row.f,
                        rtime: row.g,
                        status: row.h,
                        info: row.info,
                        desc: row.desc == '' ? "该用户太懒没有设置签名~" : row.desc,
                        image: row.image
                    }];
                    this.dialogTableVisible = true;
                },
                UserEdit(row) {
                    window.location = './user_edit.php?a=' + row.a + "&b=" + row.b;
                },
                UserDele(row) {
                    this.$confirm('将会删除该账户,是否继续?', '警告', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        let data = new FormData();
                        data.append('type', 'UserDelete');
                        data.append('data', JSON.stringify({
                            id: row.a,
                            account: row.b
                        }))
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
                                    window.location.reload();
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
                data.append('type', 'UserList');
                axios.post('../data/dataApi.php', data).then((result) => {
                    switch (result.data.code) {
                        case 200:
                            this.tableData = [];
                            result.data.data.forEach(e => {
                                let user = {
                                    a: parseInt(e.id),
                                    b: e.account,
                                    c: e.nickname,
                                    d: e.phone,
                                    e: e.email,
                                    f: e.is_admin == 0 ? '普通用户' : '管理员',
                                    g: this.getDate(e.reg_time),
                                    h: e.status == 0 ? '正常' : e.status == 1 ? '冻结' : '封禁',
                                    sex: e.sex,
                                    birthday: e.birthday,
                                    info: e.info,
                                    desc: e.mydesc,
                                    image: e.image
                                }
                                this.tableData.push(user)
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