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

        $this->server->set([
            'worker_num'      => 2,
            'task_worker_num' => 2,
        ]);

        $this->server->on("open", [$this, 'onOpen']);
        $this->server->on("message", [$this, 'onMessage']);
        $this->server->on("task", [$this, 'onTask']);
        $this->server->on("finish", [$this, 'onFinish']);
        $this->server->on("close", [$this, 'onClose']);

        $this->server->start();
    }

    public function onOpen($server, $request)
    {
        echo "server: handshake success with fd{$request->fd}\n";
        // 定时器
        if ($request->fd == 1) {
            // 每两秒执行
            // swoole_timer_tick设置一个间隔时钟定时器，与after定时器不同的是tick定时器会持续触发，直到调用Timer::clear清除
            swoole_timer_tick(2000, function () use ($timer_id) {
                echo "2s: timerId:{$timer_id}";
            });
        }
    }

    public function onMessage($server, $frame)
    {
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";

        // task,比较耗时的任务，异步
        $data = [
            'task' => 1,
            'fd'   => $frame->fd,
        ];
        $server->task($data);

        // 在指定的时间后执行函数。
        // int Swoole\Timer::after(int $after_time_ms, callable $callback_function, ...$params);
        // Swoole\Timer::after的函数风格别名是swoole_timer_after
        // Swoole\Timer::after函数是一个一次性定时器，执行完成后就会销毁。此函数与PHP标准库提供的sleep函数不同，after是非阻塞的。而sleep调用后会导致当前的进程进入阻塞，将无法处理新的请求。

        swoole_timer_after(5000, function () use ($server, $frame) {
            echo "5s-after\n";
            $server->push($frame->fd, "sever-time-after");
        });

        $server->push($frame->fd, "this is server" . date('Y-m-d H:i:s'));
    }

    public function onClose($server, $frame)
    {
        echo "client {$fd} closed\n";
    }
    /**
    在task_worker进程内被调用。worker进程可以使用swoole_server_task函数向task_worker进程投递新的任务。
    当前的Task进程在调用onTask回调函数时会将进程状态切换为忙碌，这时将不再接收新的Task，当onTask函数返回时会将进程状态切换为空闲然后继续接收新的Task。
    function onTask(swoole_server $serv, int $task_id, int $src_worker_id, mixed $data);
     * @Author Liumm
     * @Date   2019-12-30
     * @param  [type]     $data [description]
     * @return [type]           [description]
     */
    public function onTask($serv, $task_id, $src_worker_id, $data)
    {
        print_r($data);
        // 耗时场景 10s
        sleep(10);
        return "on task finish"; // 告诉worker
    }

    public function onFinish($serv, $taskId, $data)
    {
        echo "taskId:{$taskId}\n";
        echo "finish-data-sucess:{$data}\n";
    }
}

$obj = new Ws();
