<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>首页 - 芝岸</title>
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
            width: 50%;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            word-wrap: break-word;
            color: white;
            z-index: 2;
        }
    </style>
</head>

<body>
    <div class="app">
        <div class="page"></div>
        <?php
        include './header.php';
        ?>
        <!-- {{tips}} -->
        <div class="content">
            <!-- <h1 style="margin-bottom: 40px;">{{sec}}</h1> -->
            <h1 style="margin-bottom: 40px;">{{tips.hitokoto}}</h1>
            <el-divider>{{tips.from}}</el-divider>
        </div>
    </div>
    <script>
        new Vue({
            el: ".app",
            data: {
                tips: {},
                searchPageStatus: false,
                searchText: '',
                TableInfo: [],
                sec: 0
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
                // 请求随机一言
                axios.get('https://v1.hitokoto.cn/').then((result) => {
                    // console.log(result.data);
                    this.tips = result.data
                }).catch((err) => {
                    alert('发生了错误！原因：\n' + err)
                });
                // setInterval(() => {
                //     this.sec++;
                //     if (this.sec == 10) {
                //         // 请求随机一言
                //         axios.get('https://v1.hitokoto.cn/').then((result) => {
                //             // console.log(result.data);
                //             this.tips = result.data
                //         }).catch((err) => {
                //             alert('发生了错误！原因：\n' + err)
                //         });
                //         this.sec = 0;
                //     }
                // }, 1000);

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