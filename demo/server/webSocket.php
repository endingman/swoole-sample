
<?php

$server = new Swoole\WebSocket\Server("0.0.0.0", 8812);

//$server->set([]);可以设置一些配置
$server->on('open', function (Swoole\WebSocket\Server $server, $request) {
    echo "server: handshake success with fd{$request->fd}\n";
});

// 消息事件是必须的
$server->on('message', function (Swoole\WebSocket\Server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "this is server");
});

// 关闭
$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();
