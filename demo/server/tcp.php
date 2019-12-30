<?php

//创建Server对象，监听 127.0.0.1:9501端口
//Server是异步服务器，所以是通过监听事件的方式来编写程序的
$serv = new Swoole\Server("127.0.0.1", 9501);

$serv->set([
    'work_num'    => 8, //进程数，命令ps aft | grep tcp.php 查看进程
    'max_request' => 50,
]);

// 4种PHP回调函数风格
/**
匿名函数：
$server->on('Request', function ($req, $resp) use ($a, $b, $c) {
echo "hello world";
});
可使用use向匿名函数传递参数

类静态方法：
class A
{
static function test($req, $resp)
{
echo "hello world";
}
}
$server->on('Request', 'A::Test');
$server->on('Request', array('A', 'Test'));
对应的静态方法必须为public

函数：
function my_onRequest($req, $resp)
{
echo "hello world";
}
$server->on('Request', 'my_onRequest');


对象方法：
class A
{
function test($req, $resp)
{
echo "hello world";
}
}

$object = new A();
$server->on('Request', array($object, 'test'));
对应的方法必须为public
 */
// 这里使用的是匿名函数
// 监听连接进入事件
$serv->on('Connect', function ($serv, $fd) {
    /**$fd 客户端连接的唯一标示**/
    echo "Client: Connect" . '-' . $fd . "\n";
});

//监听数据接收事件
$serv->on('Receive', function ($serv, $fd, $from_id, $data) {
    $serv->send($fd, "Server: " . $data);
});

//监听连接关闭事件
$serv->on('Close', function ($serv, $fd) {
    echo "Client: Close.\n";
});

//启动服务器
$serv->start();
