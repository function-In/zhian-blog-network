<?php

// 获取所有博客列表
function BlogList()
{
    $mycon = new MyConnect();
    $result = $mycon->inquire('blog_list', array("*"), null);
    // print_r($result);
    // 查询并设置信息
    foreach ($result['data'] as $key => $value) {
        $where1 = array(array('key' => 'id', 'operation' => '=', 'value' => $result['data'][$key]['user_id']));
        $userInfo = $mycon->inquire('users', array('*'), $where1);
        $result['data'][$key]['user_id'] = $userInfo['data'][0]['nickname'];
        $result['data'][$key]['add_time'] = date('Y-m-d H:i:s', $result['data'][$key]['add_time'] / 1000);
        $result['data'][$key]['status'] = $result['data'][$key]['status'] == 0 ? "异常" : "正常";
        $where2 = array(array('key' => 'id', 'operation' => '=', 'value' => $result['data'][$key]['class_id']));
        $blogtype = $mycon->inquire('blog_type', array('*'), $where2);
        $result['data'][$key]['class_id'] = $blogtype['data'][0]['class_name'];
    }
    // print_r($result);
    echo json_encode($result);
    $mycon->close();
}
// 添加博客 API 指向文件 ZHIAN/admin/pages/blog_add.php
// 修改博客 API 指向文件 ZHIAN/admin/pages/blog_update.php

function BlogDelete()
{
    $where = array(array('key' => 'id', 'operation' => '=', 'value' => $_POST['data']));
    $mycon = new MyConnect();
    $result = $mycon->delete("blog_list", $where);
    // print_r($result);
    echo json_encode($result);
    $mycon->close();
}

// 模糊搜索博客
function BlogSearch()
{

    $p =  $_POST['data'];
    $data = array(
        array(
            'key' => 'id',
            'operation' => 'like',
            'value' => "%$p%",
            'next' => 'or'
        ), array(
            'key' => 'title',
            'operation' => 'like',
            'value' => "%$p%",
            'next' => 'or'
        ),
        array(
            'key' => 'desc',
            'operation' => 'like',
            'value' => "%$p%",
            'next' => 'or'
        ),
        array(
            'key' => 'content',
            'operation' => 'like',
            'value' => "%$p%",
            'next' => 'or'
        ),
        array(
            'key' => 'user_id',
            'operation' => 'like',
            'value' => "%$p%",
            'next' => 'or'
        ),
        array(
            'key' => 'status',
            'operation' => 'like',
            'value' => "%$p%",
            'next' => 'or'
        ),
        array(
            'key' => 'class_id',
            'operation' => 'like',
            'value' => "%$p%"
        )
    );
    $mycon = new MyConnect();
    $result = $mycon->inquire("blog_list", array('*'), $data);
    foreach ($result['data'] as $key => $value) {
        $where1 = array(array('key' => 'id', 'operation' => '=', 'value' => $result['data'][$key]['user_id']));
        $userInfo = $mycon->inquire('users', array('*'), $where1);
        $result['data'][$key]['user_id'] = $userInfo['data'][0]['nickname'];
        $result['data'][$key]['add_time'] = date('Y-m-d H:i:s', $result['data'][$key]['add_time'] / 1000);
        $result['data'][$key]['status'] = $result['data'][$key]['status'] == 0 ? "异常" : "正常";
        $where2 = array(array('key' => 'id', 'operation' => '=', 'value' => $result['data'][$key]['class_id']));
        $blogtype = $mycon->inquire('blog_type', array('*'), $where2);
        $result['data'][$key]['class_id'] = $blogtype['data'][0]['class_name'];
    }
    echo json_encode($result);
    $mycon->close();
}
