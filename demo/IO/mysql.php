<?php
// +----------------------------------------------------------------------+
// | @describe  视频教程的swoole跟使用的swoole不一样
//              文档的样例不能运行的。。。。！！！！
//              要使用go协程函数。。。。。！！！！
// +----------------------------------------------------------------------+
// | Copyright (c) 2015-2019 CN,  All rights reserved.
// +----------------------------------------------------------------------+
// | @Authors: The PHP Dev LiuManMan, Web, <liumansky@126.com>.
// | @Script:
// | @date     2020-01-02 15:28:24
// +----------------------------------------------------------------------+

//Example:
// $swoole_mysql = new Swoole\Coroutine\MySQL();

// $swoole_mysql->connect([
//     'host'     => '127.0.0.1',
//     'port'     => 3306,
//     'user'     => 'homestead',
//     'password' => 'secret',
//     'database' => 'homestead',
// ]);

/**
 * query(string $sql, double $timeout = -1)
 *$sql：SQL语句
 *$timeout：超时时间，$timeout如果小于或等于0，表示永不超时。在规定的时间内MySQL服务器未能返回数据，底层将返回false，设置错误码为110，并切断连接
 *返回值：超时/出错返回false，否则以数组形式返回查询结果
 * @var [type]
 */
// $res = $swoole_mysql->query('select sleep(1)');

$db = new Swoole\Coroutine\MySQL();

$config = [
    'host'     => '127.0.0.1',
    'port'     => 3306,
    'user'     => 'homestead',
    'password' => 'secret',
    'database' => 'larabbs',
];

$sql = 'select * from users where id = 1';

go(function () use ($db, $config, $sql) {
    $connect = $db->connect($config);
    if (!$connect) {
        echo "连接失败：{$db->connect_error}\n";
    } else {
        echo "连接成\n";
    }
    $res = $db->query($sql);

    if ($res == false) {
        echo "执行失败：{$db->error}\n";
    } elseif ($res == true) {
        echo "更新成功\n";
    } else {
        var_dump($result) . PHP_EOL;
    }
});
