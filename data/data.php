<?php
error_reporting(0);
// 设置页面编码
header("Content-Type: text/html");
// 定义类
class MyConnect
{
    // 数据库名称
    private $localhost = "localhost";
    // 数据库连接用户名
    private $username = "root";
    // 数据库连接密码
    private $password = "root";
    // 选择的数据库
    private $selectdb = "zhian";
    // 数据表前缀
    // private $tablePrefix = "blog_";
    // 连接数据库
    private $connect;
    // 定义返回数据
    private function getStatus($a, $b, $c)
    {
        $result = array('code' => $a, 'desc' => $b);
        if (!empty($c))
            $result['data'] = $c;
        return $result;
    }
    // 构造器 
    function __construct()
    {
        $this->connect = new mysqli($this->localhost, $this->username, $this->password, $this->selectdb);
    }
    // 关闭数据库
    function close()
    {
        $this->connect->close();
    }
    /* 
     * 添加数据类函数：
     * 参数：
     * （ 
     * $table => 数据表 ,
     * $data => 需要添加的数据【数组】
     * ）
     * 执行之后返回的值：
     * 信息数组：
     * (
     * code => 状态码 [0数据库连接失败，1表名错误，2数据项配置错误，3数据添加失败，200数据添加成功],
     * desc => 返回的状态信息描述
     * data => 存在数据时将数据返回
     * )
     * */
    function increase($table, $data)
    {
        if ($this->connect->connect_error)
            return $this->getStatus(0, '数据库连接失败！请联系管理员！', null);
        // 判断数据库表名
        if (empty($table) || $table == '')
            return $this->getStatus(1, "表名未设置或者不正确！", null);
        // 解析需要修改的字段和值
        $_key = "";
        $_val = "";
        if (!is_array($data) || empty($data) || $data == '') {
            return $this->getStatus(2, "数据添加项未设置或者不正确！", null);
        }
        foreach ($data as $key => $value) {
            $_key = $_key . '`' . $key . '`,';
            $_val = $_val . "'" . $value . "'" . ',';
        }
        // 生成sql语句
        $sql = "insert into $table(" . substr_replace($_key, '', -1) . ") values (" . substr_replace($_val, "", -1) . ")";
        // echo $sql;
        // die();
        // 执行sql语句
        $status = $this->connect->query($sql);
        // 判断状态
        if ($status === true)
            return $this->getStatus(200, "数据添加成功!", null);
        else
            return $this->getStatus(3, $this->connect->error, null);
    }
    /* 
     * 删除数据类函数：
     * 参数：
     * （ 
     * $table => 数据表 ,
     * $data => 查找相关条件的字段和值【数组】
     * ）
     * 执行之后返回的值：
     * 信息数组：
     * (
     * code => 状态码 [0数据库连接失败，1表名错误，2查询条件错误，3数据删除失败，200数据删除成功],
     * desc => 返回的状态信息描述
     * data => 存在数据时将数据返回
     * )
     * */
    function delete($table, $data)
    {
        if ($this->connect->connect_error)
            return $this->getStatus(0, '数据库连接失败！请联系管理员！', null);
        // 判断数据库表名
        if (empty($table) || $table == '')
            return $this->getStatus(1, "表名未设置或者不正确！", null);
        // 解析需要修改时的查询条件
        $d1 = '';
        if (!is_array($data) || empty($data) || $data == '') {
            return $this->getStatus(2, "查询条件未设置或者不正确！", null);
        }
        foreach ($data as $value) {
            $d1 = $d1 . '`' . $value['key'] . "` " . $value['operation'] . " '" . $value['value'] . "' " . (empty($value['next']) ? '' : $value['next'] . " ");
        }
        // 生成 sql 语句
        $sql = "delete from $table where $d1";
        // echo $sql;
        // die();
        // 执行 sql 语句
        $result = $this->connect->query($sql);
        // 判断是否删除成功
        if ($result === true)
            return $this->getStatus(200, "数据删除成功！", null);
        else
            return $this->getStatus(3, $this->connect->error, null);
    }
    /* 
     * 修改数据类函数：
     * 参数：
     * （ 
     * $table => 数据表 ,
     * $data1 => 修改的字段名和值【数组】,
     * $data2 => 查找相关条件的字段和值【数组】
     * ）
     * 执行之后返回的值：
     * 信息数组：
     * (
     * code => 状态码 [0数据库连接失败，1表名错误，2修改配置错误，3查询条件错误，4数据更新失败，200数据更新成功],
     * desc => 返回的状态信息描述
     * data => 存在数据时将数据返回
     * )
     * */
    function update($table, $data1, $data2)
    {
        if ($this->connect->connect_error)
            return $this->getStatus(0, '数据库连接失败！请联系管理员！', null);
        $d1 = '';
        $d2 = '';
        // 判断数据库表名
        if (empty($table) || $table == '')
            return $this->getStatus(1, "表名未设置或者不正确！", null);
        // 解析需要修改的字段和值 -- $data1
        if (!is_array($data1) || empty($data1) || $data1 == '')
            return $this->getStatus(2, "修改值未设置或者不正确！", null);
        foreach ($data1 as $key => $value)
            $d1 = $d1 . '`' . $key . "`='" . $value . "',";
        // 解析需要修改时的查询条件 -- $data2
        if (!is_array($data2) || empty($data2) || $data2 == '') {
            return $this->getStatus(3, "查询条件未设置或者不正确！", null);
        }
        foreach ($data2 as $value) {
            $d2 = $d2 . '`' . $value['key'] . "` " . $value['operation'] . " '" . $value['value'] . "' " . (empty($value['next']) ? '' : $value['next'] . " ");
        }
        // 生成 sql 语句
        $sql = "update $table set " . substr_replace($d1, '', -1) . " where $d2";
        // echo $sql;
        // die();
        // 执行 sql 语句
        $SQLData = $this->connect->query($sql);
        // 判断状态信息
        if ($SQLData === true)
            return $this->getStatus(200, "数据修改成功！", null);
        else
            return $this->getStatus(4, $this->connect->error, null);
    }
    /* 
     * 查询数据类函数：
     * 参数：
     * （ 
     * $table => 数据表 ,
     * $data1 => 所需的字段名信息或者 SQL 函数,
     * $data2 => 查找相关条件的字段和值
     * ）
     * 执行之后返回的值：
     * 信息数组：
     * (
     * code => 状态码 [0数据库连接失败，1表名错误，2所需信息错误，3查询条件错误，4数据查询失败，200数据查询成功,300查询成功但是没有数据],
     * desc => 返回的状态信息描述
     * data => 存在数据时将数据返回
     * )
     * */
    function inquire($table, $data1, $data2)
    {
        // 判断数据库连接状态
        if ($this->connect->connect_error)
            return $this->getStatus(0, '数据库连接失败！请联系管理员！', null);
        // 判断数据库表名
        if (empty($table) || $table == '')
            return $this->getStatus(1, "表名未设置或者不正确！", null);
        // 判断所需字段名信息或者 SQL 函数
        if ((!is_array($data1) && $data1 != ''))
            return $this->getStatus(2, '查找所需信息必须数组传输！', null);
        $d1 = '';
        if (!empty($data1) && is_array($data1))
            foreach ($data1 as $value)
                $d1 = $d1 . $value . ',';
        // 解析查询条件
        $d2 = '';
        if (!empty($data2) && is_array($data2)) {
            foreach ($data2 as $value)
                $d2 = $d2 . '`' . $value['key'] . "` " . $value['operation'] . " '" . $value['value'] . "' " . (empty($value['next']) ? '' : $value['next'] . " ");
        }
        // 生成sql语句
        $sql = "select " . (!empty($d1) ? substr_replace($d1, '', -1) : "*") . " from $table " . (!empty($d2) ? "where " . $d2 : "");
        // echo $sql;
        // 执行查询
        $result = $this->connect->query($sql);
        // 定义新数组
        $list = array();
        // 判断数量
        if ($result->num_rows > 0)
            while ($row = $result->fetch_assoc())
                $list[count($list)] = $row;
        else
            return $this->getStatus(300, "查询不到任何数据！", $list);
        // 返回数组
        return $this->getStatus(200, "数据查询成功！", $list);
    }
}
