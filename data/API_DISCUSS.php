<?php


function getAllDiscuss()
{
    $mycon = new MyConnect();
    $result = $mycon->inquire('discuss', array("*"), null);
    foreach ($result['data'] as $key => $value) {

        $where1 = array(array('key' => 'id', 'operation' => '=', 'value' => $result['data'][$key]['user_id']));
        $userInfo = $mycon->inquire('users', array('*'), $where1);
        $result['data'][$key]['user_id'] = $userInfo['data'][0]['nickname'];

        $result['data'][$key]['add_time'] = date('Y-m-d H:i:s', $result['data'][$key]['add_time'] / 1000);

        // $result['data'][$key]['status'] = $result['data'][$key]['status'] == 0 ? "异常" : "正常";

        // $where2 = array(array('key' => 'id', 'operation' => '=', 'value' => $result['data'][$key]['class_id']));

        // $blogtype = $mycon->inquire('blog_type', array('*'), $where2);

        // $result['data'][$key]['class_id'] = $blogtype['data'][0]['class_name'];
    }
    echo json_encode($result);
    $mycon->close();
}


function DiscussDelete()
{
    $where = array(array('key' => 'id', 'operation' => '=', 'value' => $_POST['data']));
    $mycon = new MyConnect();
    $result = $mycon->delete('discuss', $where);
    // print_r($where);
    echo json_encode($result);
    $mycon->close();
}

function addDiscuss()
{
    session_start();
    if (!isset($_SESSION['userinfo'])) {
        echo json_encode(getStatus(100, '请先进行登录！', null));
    } else {
        if (!isset($_COOKIE['timeVerify'])) {
            $data = array('user_id' => $_SESSION['userinfo']['id'], 'blog_id' => $_POST['blog_id'], 'text' => $_POST['text'], 'add_time' => time() * 1000);
            $mycon = new MyConnect();
            $result = $mycon->increase("discuss", $data);
            echo json_encode($result);
            if ($result['code'] == 200) setcookie('timeVerify', time() + 180, time() + 180);
            $mycon->close();
        } else {
            echo json_encode(getStatus(101, '评论过于频繁，请' . ((int)$_COOKIE['timeVerify'] - time()) . '秒后重试！', null));
        }
    }
}
