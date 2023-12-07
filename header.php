<header>
    <a href="/">
        <h2>芝岸</h2>
    </a>
    <ul>
        <a href="./index.php">
            <li>首页<i></i></li>
        </a>
        <a v-for="item,index in TableInfo" :href="'./list.php?tabid='+item.id">
            <li>
                {{item.class_name}}
                <i :type='index'></i>
            </li>
        </a>
        <div class="rightPancel">
            <i class="el-icon-search" @click="showSearchPage()"></i>
        </div>
        <el-dropdown trigger="click">
            <span class="el-dropdown-link" style="display:flex;color:white;align-items: center;">
                <?php
                $imgUrl = "https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png";
                ?>
                <el-avatar src="<?php echo isset($_SESSION['userinfo']) ? ($_SESSION['userinfo']['image'] != '' ? $_SESSION['userinfo']['image'] : $imgUrl) : $imgUrl; ?>"></el-avatar>
                <!-- 
                        <div style="margin:0 10px 0 10px">
                            xx<br>
                            <span style="font-size: small;">xx</span>
                        </div>
                     -->
                <i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
                <?php
                if (!isset($_SESSION['userinfo'])) {
                    echo "<a href='./login.php'><el-dropdown-item icon='el-icon-user'>登录账户</el-dropdown-item></a>";
                    echo "<a href='./register.php'><el-dropdown-item icon='el-icon-user'>注册账户</el-dropdown-item></a>";
                } else {
                    echo "<a href='./admin/'><el-dropdown-item icon='el-icon-user'>进入后台</el-dropdown-item></a>";
                    echo "<a href='./logoff.php'><el-dropdown-item icon='el-icon-user'>退出登录</el-dropdown-item></a>";
                }
                ?>

            </el-dropdown-menu>
        </el-dropdown>
    </ul>
</header>
<div class="searchPage">
    <a @click="showSearchPage()" class="close">关闭</a>
    <div class="searchBox">
        <form action="./search.php" method="post" name="searchData" id="searchData" @submit.prevent="SearchSubmit()">
            <input type="hidden" name="kwtype" value="0" />
            <div class="editbox">
                <input id="searchInfo" name="q" type="text" placeholder="请输入..." v-model='searchText' />
                <button class="enter">搜索</button>
            </div>
            <select name="searchtype" class="search-option">
                <option value="titlekeyword">智能模糊</option>
                <option value="title">检索标题</option>
            </select>
        </form>
    </div>
</div>
