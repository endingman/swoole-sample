<?php

/**
 * summary
 */
class Ws
{
    /**
     * summary
     */
    const HOST = "0.0.0.0";
    const PORT = "8812";

    public $server = null;

    public function __construct()
    {
        $this->server = new Swoole\WebSocket\Server(self::HOST, self::PORT);
        $this->server->on("open", [$this, 'onOpen']);
        $this->server->on("message", [$this, 'onMessage']);
        $this->server->on("close", [$this, 'onClose']);

        $this->server->start();
    }

    public function onOpen($server, $request)
    {
        echo "server: handshake success with fd{$request->fd}\n";
    }

    public function onMessage($server, $frame)
    {
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        $server->push($frame->fd, "this is server" . date('Y-m-d H:i:s'));
    }

    public function onClose($server, $frame)
    {
        echo "client {$fd} closed\n";
    }
}

$obj = new Ws();
