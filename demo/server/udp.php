<?php

//创建Server对象，监听 127.0.0.1:9502端口，类型为SWOOLE_SOCK_UDP
$serv = new swoole_server("127.0.0.1", 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

//监听数据接收事件
//UDP服务器与TCP服务器不同，UDP没有连接的概念。
//启动Server后，客户端无需Connect，直接可以向Server监听的9502端口发送数据包。
//对应的事件为onPacket。
$serv->on('Packet', function ($serv, $data, $clientInfo) {
    $serv->sendto($clientInfo['address'], $clientInfo['port'], "Server " . $data);
    // var_dump($clientInfo);
    var_dump($data);
});

//启动服务器
$serv->start();
