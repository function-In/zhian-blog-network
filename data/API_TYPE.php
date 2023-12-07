<?

// 获取文章分类
function BlogTypeList()
{
    $mycon = new MyConnect();
    $result = $mycon->inquire('blog_type', array("*"), null);
    echo json_encode($result);
    $mycon->close();
}
// 获取指定分类信息
function BlogTypeSearch()
{
    $p =  $_POST['data'];
    $data = array(
        array(
            'key' => 'id',
            'operation' => 'like',
            'value' => "%$p%",
            'next' => 'or'
        ), array(
            'key' => 'class_name',
            'operation' => 'like',
            'value' => "%$p%",
            'next' => 'or'
        ), array(
            'key' => 'sort',
            'operation' => 'like',
            'value' => "%$p%",
            'next' => 'or'
        ), array(
            'key' => 'status',
            'operation' => 'like',
            'value' => "%$p%",
        )
    );
    $mycon = new MyConnect();
    $result = $mycon->inquire("blog_type", array('*'), $data);
    echo json_encode($result);
    $mycon->close();
}
// 更新指定分类信息
function BlogTypeUpdate()
{
    $data = json_decode($_POST['data1'], true);
    $p2 = json_decode($_POST['data2']);
    $where = array(
        array(
            'key' => 'id',
            'operation' => '=',
            'value' => $p2,
        )
    );
    $mycon = new MyConnect();
    $result = $mycon->update("blog_type", $data, $where);
    echo json_encode($result);
    $mycon->close();
}
// 分类添加
function BlogTypeAdd()
{
    $data = array();
    $p = json_decode($_POST['data'], true);
    foreach ($p as $key => $value) $data[$key] = $value;
    // print_r($data);
    $mycon = new MyConnect();
    $result = $mycon->increase("blog_type", $data);
    echo json_encode($result);
    $mycon->close();
}
// 删除分类
function BlogTypeDelete()
{
    $data = $_POST['data'];
    $where = array(
        array(
            'key' => 'id',
            'operation' => '=',
            'value' => $data
        )
    );
    // print_r($data);
    $mycon = new MyConnect();
    $result = $mycon->delete("blog_type", $where);
    // print_r($result);
    echo json_encode($result);
    $mycon->close();
}
