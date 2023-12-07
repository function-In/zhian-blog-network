<?php
// 获取全部用户
function UserList()
{
    $mycon = new MyConnect();
    $result = $mycon->inquire('users', array("*"), null);
    echo json_encode($result);
    $mycon->close();
}
// 增加用户
function UserAdd()
{
    $data = array();
    $p = json_decode($_POST['data'], true);
    foreach ($p as $key => $value)
        if ($key == 'password') $data[$key] = md5($value);
        else $data[$key] = $value;
    $data['reg_time'] = time() * 1000;
    // print_r($data);
    $mycon = new MyConnect();
    $result = $mycon->increase("users", $data);
    echo json_encode($result);
    $mycon->close();
}
// 选择特定用户
function UserSelect()
{
    $p = json_decode($_POST['data']);
    $data = array(
        array(
            'key' => 'id',
            'operation' => '=',
            'value' => $p->id,
            'next' => 'and'
        ),
        array(
            'key' => 'account',
            'value' => $p->account,
            'operation' => '='
        ),
    );
    // print_r($data);
    $mycon = new MyConnect();
    $result = $mycon->inquire('users', array("*"), $data);
    echo json_encode($result);
    $mycon->close();
}
// 更新特定用户
function UserUpdate()
{
    $p1 = json_decode($_POST['data1'], true);
    $data1 = array();
    foreach ($p1 as $key => $value) {
        if ($key == 'password' && !empty($value) && $value != ' ') $data1[$key] = md5($value);
        else  if ($key == 'password' && (empty($value) || $value == ' ')) continue;
        else $data1[$key] = $value;
    }
    $p2 = json_decode($_POST['data2']);
    $data2 = array(
        array(
            'key' => 'id',
            'operation' => '=',
            'value' => $p2->id,
            'next' => 'and'
        ),
        array(
            'key' => 'account',
            'value' => $p2->account,
            'operation' => '='
        ),
    );
    $mycon = new MyConnect();
    $result = $mycon->update("users", $data1, $data2);
    echo json_encode($result);
    $mycon->close();
}
// 删除用户
function UserDelete()
{
    $p1 = json_decode($_POST['data']);
    $data = array(
        array(
            'key' => 'id',
            'operation' => '=',
            'value' => $p1->id,
            'next' => 'and'
        ),
        array(
            'key' => 'account',
            'value' => $p1->account,
            'operation' => '='
        ),
    );
    // print_r($data);
    $mycon = new MyConnect();
    $result = $mycon->delete("users", $data);
    // print_r($result);
    echo json_encode($result);
    $mycon->close();
}
// 搜索用户
function UserSearch()
{
    $p =  $_POST['data'];
    $data = array(
        array(
            'key' => 'id',
            'operation' => 'like',
            'value' => "%$p%",
            'next' => 'or'
        ), array(
            'key' => 'account',
            'operation' => 'like',
            'value' => "%$p%",
            'next' => 'or'
        ), array(
            'key' => 'nickname',
            'operation' => 'like',
            'value' => "%$p%",
            'next' => 'or'
        ), array(
            'key' => 'phone',
            'operation' => 'like',
            'value' => "%$p%",
            'next' => 'or'
        ), array(
            'key' => 'email',
            'operation' => 'like',
            'value' => "%$p%",
            'next' => 'or'
        ), array(
            'key' => 'info',
            'operation' => 'like',
            'value' => "%$p%",
            'next' => 'or'
        ), array(
            'key' => 'mydesc',
            'operation' => 'like',
            'value' => "%$p%"
        ),
    );
    $mycon = new MyConnect();
    $result = $mycon->inquire("users", array('*'), $data);
    echo json_encode($result);
    $mycon->close();
}
