<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>详情 - 芝岸</title>
    <script src="./static/scripts/vue.js"></script>
    <script src="./static/scripts/axios.min.js"></script>
    <script src="./static/scripts/jquery-3.5.1.min.js"></script>
    <link rel="shortcut icon" href="./admin/public/favicon.ico" type="image/x-icon" />
    <script src="./admin/public/scripts/element.js"></script>
    <link rel="stylesheet" href="./admin/public/styles/element.css">
    <link rel="stylesheet" href="./static/styles/style.css">
    <style>
        .app {
            width: 100%;
            height: 100%;
            min-width: 1000px;
            position: absolute;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-image: url('./static/images/background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            z-index: 1;
        }

        .page {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            background-color: rgba(0, 0, 0, 0.4);
            filter: brightness(2);
            z-index: 0;
        }

        .contentBox {
            flex: 1;
            width: 100%;
            height: 100%;
            color: white;
            z-index: 2;
            height: 100%;
            overflow-y: hidden;
            display: flex;
            justify-content: center;
        }

        .content {
            width: 1000px;
            /* flex: 1; */
            /* display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            word-wrap: break-word; */
            color: white;
            height: 100%;
            overflow-y: auto;
            padding: 0 20px 20px 20px;
            background-color: rgba(0, 0, 0, 0.1);
            margin: 0 5px 20px 50px;
        }

        .content .image {
            width: 100%;
            height: 500px;
            background-size: 100% 100%;
            background-repeat: no-repeat;
            image-rendering: pixelated;
        }

        .content img {
            width: 100%;
            height: 500px;
            background-size: 100% 100%;
            background-repeat: no-repeat;
            image-rendering: pixelated;
        }

        .rightContent {
            width: 400px;
            margin: 0px 50px 0 5px;
            background-color: rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            height: 900px;
        }

        header ul li i[type='<?php echo $_GET['tabid']; ?>'] {
            width: 100%;
        }

        *::-webkit-scrollbar {
            width: 6px;
            cursor: pointer;
        }

        *::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.4);
        }

        *::-webkit-scrollbar-track {
            background-color: transparent;
        }

        *::-webkit-scrollbar-button {
            display: none;
            width: 0px;
            height: 0px;
            background-color: #2878FF;
            cursor: pointer;
        }

        .el-card {
            background-color: transparent;
            color: white;
            border: none;
            box-shadow: 0;
        }

        .el-card .el-card__header {
            border-bottom: 1px solid grey;
        }

        .discussBox {
            width: 100%;
            height: 600px;
            overflow-y: auto;
            flex: 1;
        }

        .el-card.is-always-shadow,
        .el-card.is-hover-shadow:focus,
        .el-card.is-hover-shadow:hover {
            box-shadow: none;
        }

        .text.item {
            padding: 10px 10px 10px 10px;
            background-color: rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }


        .text.item:hover {
            background-color: rgba(0, 0, 0, 0.2);
        }

        .text.item .title {
            display: flex;
            align-items: center;
        }

        .text.item .title * {
            margin-right: 10px;
        }

        .text.item .discussContent {
            margin: 10px 0 10px 50px;
        }

        .el-textarea {
            width: 100%;
            border: none;
        }

        .el-textarea .el-textarea__inner {
            background-color: rgba(0, 0, 0, 0.3);
            border: 1px solid #434b5e;
            height: 160px;
            border-radius: 0;
            color: white;
        }

        .editDiscuss {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .editDiscuss .el-button {
            width: 50%;
            background-color: rgba(0, 0, 0, 0.2);
            border: none;
            margin: 10px auto 0 auto;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="app">
        <div class="page"></div>
        <?php
        include './header.php';
        ?>
        <div class="contentBox">
            <div class="content">
                <?php
                include "./data/data.php";
                $myconn = new MyConnect();
                if (!isset($_GET['wid']) || $_GET['wid'] == '' || !preg_match("/^\d*$/", $_GET['wid'])) {
                    $page_error = true;
                    echo '<el-empty description=""></el-empty><div class="nodata">读取文章失败</div>';
                } else {
                    $where = array(array('key' => 'id', 'operation' => '=', 'value' => $_GET['wid']));
                    $result = $myconn->inquire("blog_list", array('*'), $where);
                    if ($result['code'] == 200) {
                        foreach ($result['data'] as $key => $value) {
                            // 查询评论信息
                            $discuss_where = array(array('key' => 'blog_id', 'operation' => '=', 'value' => $value['id']));
                            $discuss_count =  $myconn->inquire('discuss', array('count(*)'), $discuss_where);
                            // 查询用户信息
                            $userinfo_where = array(array('key' => 'id', 'operation' => '=', 'value' => $value['user_id']));
                            $userinfo =  $myconn->inquire('users', array('*'), $userinfo_where);
                            // 查询分类信息
                            $blogtype_where = array(array('key' => 'id', 'operation' => '=', 'value' => $value['class_id']));
                            $blogtype =  $myconn->inquire('blog_type', array('*'), $blogtype_where);
                            // print_r($blogtype);
                            // 标题
                            echo '<h1 style="text-align:center;margin-top:50px">' . $value['title'] . '</h1>';
                            // 文章信息
                            echo '<div class="bottom" style="margin-top:50px">';
                            echo '<i class="el-icon-s-custom" style="margin-right:20px">' . ($userinfo['code'] == 200 ? $userinfo['data'][0]['nickname'] : 0) . '</i>';
                            echo '<i class="el-icon-date" style="margin-right:20px">' . date('Y-m-d', $value['add_time'] / 1000) . '</i>';
                            echo '<i class="el-icon-chat-dot-round" style="margin-right:20px">' . ($discuss_count['code'] == 200 ? $discuss_count['data'][0]['count(*)'] : 0) . '</i>';
                            echo '<span style="margin-right:20px">获赞：' . $value['zan'] . '</span>';
                            echo '<span style="margin-right:20px">被踩：' . $value['cai'] . '</span>';
                            echo '<span style="margin-right:20px">文章编号：' . $value['id'] . '</span>';
                            echo '<span style="margin-right:20px">文章分类：' . ($blogtype['code'] == 200 ? $blogtype['data'][0]['class_name'] : 0) . '</span>';
                            echo '<hr style="margin-top:20px;margin-bottom:20px"/>';
                            // echo '<img src="' . $value['image'] . '" alt="">';
                            // 封面
                            echo '<div class="image" style="background-image: url(' . $value['image'] . ');"></div>';
                            echo '<span style="display:Block;text-align:center">( 图 · 封面 )</span>';
                            // 显示正文内容
                            echo '<div>' . $value['content'] . '</div>';
                            echo '</div>';
                        }
                    }
                }
                ?>
            </div>
            <div class="rightContent">
                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>文章评论</span>
                        <el-button style="float: right; padding: 3px 0" type="text">刷新</el-button>
                    </div>
                    <div class="discussBox">
                        <?php
                        $imgUrl;
                        if ($page_error == true) {
                            echo '<el-empty description=""></el-empty>';
                        } else {
                            $discusslist_where = array(array('key' => 'blog_id', 'operation' => '=', 'value' => $value['id']));
                            $discusslist =  $myconn->inquire('discuss', array('*'), $discusslist_where);
                            // print_r($discusslist);
                            if ($discusslist['code'] == 200) {
                                foreach ($discusslist['data'] as $key => $value) {
                                    $userinfo_where2 = array(array('key' => 'id', 'operation' => '=', 'value' => $value['user_id']));
                                    $userinfo2 =  $myconn->inquire('users', array('*'), $userinfo_where2);
                        ?>
                                    <div class="text item">
                                        <div class="title">
                                            <el-avatar src="<?php echo $userinfo2['data'][0]['image']; ?>"></el-avatar>
                                            <div>
                                                <?php
                                                // echo $value['user_id'];
                                                echo ($userinfo2['data'][0]['nickname']);
                                                ?>
                                                <br><span style="font-size: small;color:rgba(255,255,255,0.6)">
                                                    <?php echo date('Y-m-d', $value['add_time'] / 1000); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="discussContent">
                                            <?php echo $value['text']; ?>
                                        </div>
                                    </div>
                        <?php
                                }
                            } else if ($discusslist['code'] == 300) {
                                echo '<div class="text item" style="text-align: center;">暂无评论</div>';
                            }
                        }
                        ?>
                    </div>
                </el-card>
                <div class="editDiscuss">
                    <el-input type="textarea" v-model='discussContent'></el-input>
                    <el-button @click="onSubmit()">提交评论</el-button>
                </div>
            </div>
        </div>
    </div>
    <?php
    // 关闭数据库
    $myconn->close();
    ?>
    <script>
        new Vue({
            el: ".app",
            data: {
                discussContent: '',
                TableInfo: [],
                searchPageStatus: false,
                searchText: '',
            },
            methods: {
                onSubmit() {
                    if ((this.discussContent == '') || (this.discussContent.length < 1) || (this.discussContent.replaceAll(" ", "") == '')) {
                        this.$message({
                            type: 'warning',
                            message: `评论内容不能为空并且不能全为空格！`
                        });
                        return false;
                    }
                    // 增加评论
                    let data = new FormData();
                    data.append('blog_id', '<?php echo $_GET['wid']; ?>');
                    data.append('text', this.discussContent);
                    axios.post('./data/dataApi.php?mt=addDiscuss', data).then((result) => {
                        console.log(result.data);
                        switch (result.data.code) {
                            case 200:
                                this.$message({
                                    type: 'success',
                                    message: `评论成功！`
                                });
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000)
                                break;
                            case 100:
                                alert('您需要登录之后才能进行评论！');
                                window.location.href = './login.php?url=./details.php?tabid=<?php echo $_GET['tabid']; ?>&wid=<?php echo $_GET['wid']; ?>'
                                break;
                            case 101:
                                this.$message({
                                    type: 'warning',
                                    message: result.data.desc
                                });
                                break;
                            default:

                                break;
                        }
                    }).catch((err) => {
                        alert('发生了错误！原因：\n' + err)
                    });
                },
                showSearchPage() {
                    if (this.searchPageStatus) jQuery("div.searchPage").fadeOut();
                    else jQuery("div.searchPage").fadeIn();
                    this.searchPageStatus = !this.searchPageStatus;
                },
                SearchSubmit() {
                    if ((jQuery("input#searchInfo").val()).replaceAll(" ", "") != '') {
                        document.searchData.submit();
                    } else {
                        alert("输入不能为空！");
                        jQuery("input#searchInfo").val("")
                    }
                }
            },
            mounted() {
                // 请求导航栏目
                axios.post('./data/dataApi.php?mt=getTopLabel', null).then((result) => {
                    // console.log(result.data);
                    this.TableInfo = result.data.data;
                }).catch((err) => {
                    alert('发生了错误！原因：\n' + err)
                });
            }
        });
    </script>
</body>

</html>