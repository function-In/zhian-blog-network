<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>列表 - 芝岸</title>
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
            min-width: 800px;
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

        .content {
            width: 1000px;
            flex: 1;
            /* display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            word-wrap: break-word; */
            color: white;
            z-index: 2;
            height: 100%;
            overflow-y: scroll;
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
    </style>
</head>

<body>
    <div class="app">
        <div class="page"></div>
        <?php
        include './header.php';
        ?>
        <div class="content">
            <ul>
                <?php
                include "./data/data.php";
                $myconn = new MyConnect();
                // if (!isset($_GET['tabid']) || $_GET['tabid'] == '' || !preg_match("/^\d*$/", $_GET['tabid'])) {
                //     echo '<div class="nodata">分类信息错误</div>';
                // } else {
                //     $where = array(array('key' => 'class_id', 'operation' => '=', 'value' => $_GET['tabid']));
                //     $result = $myconn->inquire("blog_list", array('*'), $where);
                //     if ($result['code'] == 200) {
                //         foreach ($result['data'] as $key => $value) {
                //             // 查询评论数
                //             $discuss_where = array(array('key' => 'blog_id', 'operation' => '=', 'value' => $value['id']));
                //             $discuss_count =  $myconn->inquire('discuss', array('count(*)'), $discuss_where);
                //             // 查询用户信息
                //             $userinfo_where = array(array('key' => 'id', 'operation' => '=', 'value' => $value['user_id']));
                //             $userinfo =  $myconn->inquire('users', array('*'), $userinfo_where);
                //             // print_r($userinfo);
                //             echo '<a href="details.php?tabid=' . $_GET['tabid'] . '&wid=' . $value['id'] . '">';
                //             echo '<li>';
                //             echo '<img src="' . ($value['image']) . '" alt="">';
                //             echo '<div class="right">';
                //             echo '<div class="top"><h2>' . $value['title'] . '</h2></div>';
                //             echo '<div class="center">' . $value['desc'] . '</div>';
                //             echo '<div class="bottom">';
                //             echo '<i class="el-icon-s-custom" style="margin-right:10px">' . ($userinfo['code'] == 200 ? $userinfo['data'][0]['nickname'] : 0) . '</i>';
                //             echo '<i class="el-icon-date" style="margin-right:10px">' . date('Y-m-d', $value['add_time'] / 1000) . '</i>';
                //             echo '<i class="el-icon-chat-dot-round" style="margin-right:10px">' . ($discuss_count['code'] == 200 ? $discuss_count['data'][0]['count(*)'] : 0) . '</i>';
                //             echo '</div>';
                //             echo '</div>';
                //             echo '</li>';
                //             echo '</a>';
                //         }
                //     }
                // }

                // 搜索博客信息
                $p11 =  $_POST['q'];
                $data = array(
                    array('key' => 'id', 'operation' => 'like', 'value' => "%$p11%", 'next' => 'or'),
                    array('key' => 'title', 'operation' => 'like', 'value' => "%$p11%", 'next' => 'or'),
                    array('key' => 'desc', 'operation' => 'like', 'value' => "%$p11%", 'next' => 'or'),
                    array('key' => 'content', 'operation' => 'like', 'value' => "%$p11%", 'next' => 'or'),
                    array('key' => 'user_id', 'operation' => 'like', 'value' => "%$p11%", 'next' => 'or'),
                    array('key' => 'status', 'operation' => 'like', 'value' => "%$p11%", 'next' => 'or'),
                    array('key' => 'class_id', 'operation' => 'like', 'value' => "%$p11%")
                );
                $result = $myconn->inquire("blog_list", array('*'), $data);
                foreach ($result['data'] as $key => $value) {
                    $where1 = array(array('key' => 'id', 'operation' => '=', 'value' => $result['data'][$key]['user_id']));
                    $userInfo = $myconn->inquire('users', array('*'), $where1);
                    $result['data'][$key]['user_id'] = $userInfo['data'][0]['nickname'];
                    $result['data'][$key]['add_time'] = $result['data'][$key]['add_time'];
                    $result['data'][$key]['status'] = $result['data'][$key]['status'] == 0 ? "异常" : "正常";
                    $where2 = array(array('key' => 'id', 'operation' => '=', 'value' => $result['data'][$key]['class_id']));
                    $blogtype = $myconn->inquire('blog_type', array('*'), $where2);
                    $result['data'][$key]['class_id'] = $blogtype['data'][0]['class_name'];
                }

                if ($result['code'] == 200) {
                    foreach ($result['data'] as $key => $value) {
                        // 查询评论数
                        $discuss_where = array(array('key' => 'blog_id', 'operation' => '=', 'value' => $value['id']));
                        $discuss_count =  $myconn->inquire('discuss', array('count(*)'), $discuss_where);
                        // 查询用户信息
                        $userinfo_where = array(array('key' => 'id', 'operation' => '=', 'value' => $value['user_id']));
                        $userinfo =  $myconn->inquire('users', array('*'), $userinfo_where);
                        // print_r($userinfo);
                        echo '<a href="details.php?tabid=' . $_GET['tabid'] . '&wid=' . $value['id'] . '">';
                        echo '<li>';
                        echo '<img src="' . ($value['image']) . '" alt="">';
                        echo '<div class="right">';
                        echo '<div class="top"><h2>' . (str_replace($p11, '<font color=red>' . $p11 . '</font>', $value['title'])) . '</h2></div>';
                        echo '<div class="center">' . $value['desc'] . '</div>';
                        echo '<div class="bottom">';
                        echo '<i class="el-icon-s-custom" style="margin-right:10px">' . ($userinfo['code'] == 200 ? $userinfo['data'][0]['nickname'] : 0) . '</i>';
                        echo '<i class="el-icon-date" style="margin-right:10px">' . date('Y-m-d', $value['add_time'] / 1000) . '</i>';
                        echo '<i class="el-icon-chat-dot-round" style="margin-right:10px">' . ($discuss_count['code'] == 200 ? $discuss_count['data'][0]['count(*)'] : 0) . '</i>';
                        echo '</div>';
                        echo '</div>';
                        echo '</li>';
                        echo '</a>';
                    }
                }
                ?>
            </ul>
        </div>
    </div>
    <script>
        new Vue({
            el: ".app",
            data: {
                TableInfo: [],
                searchPageStatus: false,
                searchText: '',
            },
            methods: {
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
                    console.log(result.data);
                    this.TableInfo = result.data.data;
                }).catch((err) => {
                    alert('发生了错误！原因：\n' + err)
                });
            }
        });
    </script>
</body>

</html>