<el-menu default-active="<?php echo $tabkey[0]; ?>" class="el-menu-vertical-demo" :collapse="isCollapse">
    <a href="./index.php">
        <el-menu-item style="text-align: center;height:60px">
            <i style="text-align: center;line-height: 60px;font-size: larger;font-style: italic;color: white;">
                {{isCollapse?'ZA':'(ZA)芝岸后台管理系统'}}
            </i>
            <span slot="title" v-if='isCollapse'>{{isCollapse?'ZA':'(ZA)芝岸后台管理系统'}}</span>
        </el-menu-item>
    </a>
    <!-- <a href='<?php echo $tabkey[0] == 0 ? 'javascript:void(0)' : './index.php'; ?>'>
        <el-menu-item index="0">
            <i class="el-icon-s-data"></i>
            <span slot="title">后台概览</span>
        </el-menu-item>
    </a>
    <br> -->
    <a href='/'>
        <el-menu-item>
            <i class="el-icon-back"></i>
            <span slot="title">回到首页</span>
        </el-menu-item>
    </a>
    <br>
    <a href='<?php echo $tabkey[0] == 3 ? 'javascript:void(0)' : './index.php'; ?>'>
        <el-menu-item index="3">    
            <i class="el-icon-document"></i>
            <span slot="title">博客列表</span>
        </el-menu-item>
    </a>

    <a href='<?php echo $tabkey[0] == 44 ? 'javascript:void(0)' : './blog_edit.php'; ?>' v-if='<?php echo $tabkey[0]; ?>==44'>
        <el-menu-item index="44">
            <i class="el-icon-document-add"></i>
            <span slot="title">编辑博客</span>
        </el-menu-item>
    </a>

    <a href='<?php echo $tabkey[0] == 4 ? 'javascript:void(0)' : './blog_add.php'; ?>'>
        <el-menu-item index="4">
            <i class="el-icon-document-add"></i>
            <span slot="title">添加博客</span>
        </el-menu-item>
    </a>

    <a href='<?php echo $tabkey[0] == 6 ? 'javascript:void(0)' : './blog_type.php'; ?>'>
        <el-menu-item index="6">
            <i class="el-icon-document-delete"></i>
            <span slot="title">博客分类</span>
        </el-menu-item>
    </a>
    <br>



    <a href='<?php echo $tabkey[0] == 7 ? 'javascript:void(0)' : './user_list.php'; ?>'>
        <el-menu-item index="7">
            <i class="el-icon-user"></i>
            <span slot="title">用户列表</span>
        </el-menu-item>
    </a>
    <a href='<?php echo $tabkey[0] == 8 ? 'javascript:void(0)' : './user_add.php'; ?>'>
        <el-menu-item index="8">
            <i class="el-icon-plus"></i>
            <span slot="title">添加用户</span>
        </el-menu-item>
    </a>
    <el-menu-item index="800" v-if='<?php echo $tabkey[0]; ?>==800'>
        <i class="el-icon-edit-outline"></i>
        <span slot="title">编辑用户</span>
    </el-menu-item>
    <br>



    <a href='<?php echo $tabkey[0] == 9 ? 'javascript:void(0)' : './discuss_list.php'; ?>'>
        <el-menu-item index="9">
            <i class="el-icon-tickets"></i>
            <span slot="title">评论管理</span>
        </el-menu-item>
    </a>
    <br>

    <el-menu-item @click="isCollapse=!isCollapse">
        <i :class=isCollapse?'el-icon-d-arrow-right':'el-icon-d-arrow-left'></i>
        <span slot="title">{{isCollapse?'展开面板':'收起面板'}}</span>
    </el-menu-item>
</el-menu>