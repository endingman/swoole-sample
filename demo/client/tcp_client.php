<?php

// 创建客户端
// Client提供了TCP/UDP socket的客户端的封装代码，使用时仅需 new Swoole\Client 即可
$client = new Swoole\Client(SWOOLE_SOCK_TCP);
if (!$client->connect('127.0.0.1', 9501, -1)) {
    exit("connect failed. Error: {$client->errCode}\n");
}
// 发送数据
$client->send("hello world\n");

// 接收数据
echo $client->recv();

// 关闭客户端
$client->close();
