<!-- <div class='header' style="display:flex;background-color: #0D162D;"> -->
<?php

$imgUrl = "https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png";
if (!isset($_SESSION['userinfo'])) {
    // if ($_SESSION['userinfo']['is_admin'] == 0) {
    //     echo "<script>alert('对不起!您的身份不是管理员!');location='../index.php'</script>";
    // }
    // } else {
    echo "<script>alert('请先进行登录！');location = '/login.php';</script>";
}
?>
<el-page-header title='' style="line-height: 60px;color:white" @back='onCancel()'>
    <template slot="content">
        <div style='color:white;margin-right:20px'>
            <?php echo $tabkey[1]; ?>
        </div>
    </template>
</el-page-header>
<el-breadcrumb separator-class="el-icon-arrow-right" style="height:60px;line-height:70px;margin-right:20px">
    <el-breadcrumb-item>
        <span style="color:grey"><?php echo $tabkey[2]; ?></span>
    </el-breadcrumb-item>
</el-breadcrumb>
<div class="rightPancel">
    <i class="el-icon-sunny" @click="switchMode()"></i>
    <i class="el-icon-bell" @click="openMessage()"></i>
    <i class="el-icon-search" @click="openSearch()"></i>
    <el-avatar src="<?php echo isset($_SESSION['userinfo']) ? ($_SESSION['userinfo']['image'] != '' ? $_SESSION['userinfo']['image'] : $imgUrl) : $imgUrl; ?>"></el-avatar>
    <el-dropdown trigger="click">
        <span class="el-dropdown-link" style="display:flex;color:white;align-items: center;">
            <div style="margin:0 10px 0 10px">
                <?php
                echo $_SESSION['userinfo']['nickname'];
                ?>
                <br><span style="font-size: small;">
                    <?php
                    echo $_SESSION['userinfo']['is_admin'] == 1 ? "管理员" : "普通用户";
                    ?>
                </span>
            </div>
            <i class="el-icon-arrow-down el-icon--right"></i>
        </span>
        <el-dropdown-menu slot="dropdown">
            <!-- <el-dropdown-item icon="el-icon-plus">个人中心</el-dropdown-item> -->
            <a href='./pages/logoff.php'>
                <el-dropdown-item icon="el-icon-circle-plus">退出登录</el-dropdown-item>
            </a>
        </el-dropdown-menu>
    </el-dropdown>
</div>

<!-- </div> -->