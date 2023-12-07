<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增博客</title>
</head>

<body>
    <?php
    // 数据库文件
    include '../../data/data.php';
    // 实例化
    $mycon = new MyConnect();
    // 定义路径地址
    $baseurl = "../../uploads/images/blog/";
    $imageUrl = '';
    // 搭建数据结构
    $data = array(
        "title" => $_POST['title'],
        "desc" => $_POST['desc'],
        "content" => str_replace("'", '"', $_POST['content']),
        "class_id" => $_POST['type'],
    );
    // print_r($data);
    // die();
    // 判断文件是否存在
    if (!empty($_FILES) && $_FILES['file']['tmp_name'] != '') {
        // 生成文件路径
        $imageUrl = $baseurl . time() . '_' . rand(time(), 9999999999) . '.' . end(explode('.', $_FILES['file']['name']));
        // echo $imageUrl;
        move_uploaded_file($_FILES['file']['tmp_name'], $imageUrl);
        $data['image'] = $imageUrl;
    }
    $where = array(
        array(
            'key' => 'id',
            'operation' => '=',
            'value' => $_POST['wid']
        ),
    );
    $result = $mycon->update('blog_list', $data, $where);
    // 判断状态
    // print_r($result);
    if ($result['code'] == 200) echo "<script>alert('文章修改成功！');window.history.back();</script>";
    else echo '<script>alert("文章修改失败！原因:\\n' . $result['desc'] . '");window.history.back();</script>';
    // 关闭数据库
    $myconn->close();
    ?>
</body>

</html>