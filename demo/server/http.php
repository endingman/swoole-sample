<?php

/**
可以理解为简单的nginx服务
 */

use Swoole\Coroutine\System;
use Swoole\Http\Server;

$http = new Server("0.0.0.0", 9503);

/**
设置document_root并设置enable_static_handler为true后，底层收到Http请求会先判断document_root路径下是否存在此文件
如果存在会直接发送文件内容给客户端，不再触发onRequest回调。
 */
$http->set([
    'document_root'         => '/home/vagrant/code/swoole-project/demo/data', // v4.4.0以下版本, 此处必须为绝对路径
    'enable_static_handler' => true,
]);

// 监听 request。 参数：request 请求 、response响应
$http->on('request', function ($request, $response) {
    // 写入日志
    $context = [
        'date:'   => date('Y-m-d H:i:s'),
        'get:'    => $request->get,
        'post:'   => $request->post,
        'header:' => $request->header,
    ];

    $filename = __DIR__ . "/http.log";
    $context  = json_encode($context) . PHP_EOL;
    go(function () use ($filename, $context) {
        /**
         * function Coroutine\System::writeFile(string $filename, string $fileContent, int $flags);
         * $filename为文件的名称，必须有可写权限，文件不存在会自动创建。打开文件失败会立即返回false
         * $fileContent为要写入到文件的内容，最大可写入4M
         * $flags为写入的选项，默认会清空当前文件内容，可以使用FILE_APPEND表示追加到文件末尾
         * @var [type]
         */
        $r = System::writeFile($filename, $context, FILE_APPEND);
    });

    // 这里输出只能在运行的终端输出不能在浏览器输出
    print_r($request->get);
    // 要在浏览器输出，调用$response的end方法
    $response->end("<h1>Hello Swoole!</h1>");
});

// 开启服务
$http->start();
