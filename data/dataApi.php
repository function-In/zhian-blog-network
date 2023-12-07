<?php
header('Access-Control-Allow-Origin:*');

function getStatus($a, $b, $c)
{
    $result = array('code' => $a, 'desc' => $b);
    if (!empty($c)) $result['data'] = $c;
    return $result;
}

// 数据库连接增删改查实现方法
include './data.php';

// 用户管理访问接口方法
include './API_USER.php';
// 博客管理访问接口方法
include './API_BLOG.php';
// 博客分类访问接口方法
include './API_TYPE.php';
// 评论管理访问接口方法
include './API_DISCUSS.php';


// 前台页面 API
include './ZHIAN.php';

// 内定 API 接口 - 查询总数
function FindCount()
{
    $mycon = new MyConnect();
    $result = $mycon->inquire($_POST['table'], array('count(*)'), null);
    echo json_encode($result);
    $mycon->close();
}

// 登录 API 接口
// 201 已登录 200 登录成功 300 没有用户 202 密码错误
function login()
{
    session_start();
    if (isset($_SESSION['userinfo'])) {
        return json_encode(getStatus(201, '您已进行登录！', null));
    } else {
        $myconn = new MyConnect();
        // 用户名
        $username = $_POST['username'];
        // 用户密码
        $password = $_POST['password'];
        $where1 = array(
            array('key' => 'id', 'operation' => '=', 'value' => $username, 'next' => 'or'),
            array('key' => 'account', 'operation' => '=', 'value' => $username, 'next' => 'or'),
            array('key' => 'phone', 'operation' => '=', 'value' => $username),
        );
        // 查询
        $result = $myconn->inquire("users", null, $where1);
        // 判断登录
        switch ($result['code']) {
            case 200:
                if ($result['data'][0]['password'] == md5($password)) {
                    $_SESSION['userinfo'] = $result['data'][0];
                    echo json_encode(getStatus(200, '登录成功！', null));
                } else {
                    echo json_encode(getStatus(202, '登录失败！密码输入错误！', null));
                };
                $myconn->close();
                break;
            case 300:
                echo json_encode(getStatus(300, '登录失败！没有该用户！', null));
                $myconn->close();
                break;
            default:
                echo json_encode($result);
                $myconn->close();
                break;
        }
        $myconn->close();
    }
}

// 解析 GET method 请求类型
$g = $_GET;
if (isset($g['mt'])) {
    switch ($g['mt']) {
        case 'getTopLabel':
            getTopLabel();
            break;
        case 'addDiscuss':
            addDiscuss();
            break;
        case 'login':
            login();
            break;
        default:
            echo "Failed method!";
            break;
    }
}

// 解析 POST type 请求类型跳到指定接口方法
$p = $_POST;
if (isset($p['type'])) {
    switch ($p['type']) {

            // 查询记录总数
        case 'FindCount':
            FindCount();
            break;

            // 账户管理 API 接口
        case 'UserList':
            UserList();
            break;
        case 'UserAdd':
            UserAdd();
            break;
        case 'UserSelect':
            UserSelect();
            break;
        case 'UserUpdate':
            UserUpdate();
            break;
        case 'UserDelete':
            UserDelete();
            break;
        case 'UserSearch':
            UserSearch();
            break;


            // 博客管理 API 接口
        case 'BlogList':
            BlogList();
            break;
        case 'BlogDelete':
            BlogDelete();
            break;
        case 'BlogSearch':
            BlogSearch();
            break;



            // 博客分类 API 接口
        case 'BlogTypeList':
            BlogTypeList();
            break;
        case 'BlogTypeSearch':
            BlogTypeSearch();
            break;
        case 'BlogTypeUpdate':
            BlogTypeUpdate();
            break;
        case 'BlogTypeAdd':
            BlogTypeAdd();
            break;
        case 'BlogTypeDelete':
            BlogTypeDelete();
            break;


            // 评论管理 API 接口
        case 'getAllDiscuss':
            getAllDiscuss();
            break;
        case 'DiscussDelete':
            DiscussDelete();
            break;
    }
}
