<?php

// +----------------------------------------------------------------------+
// | @describe swoole4.3.0以上
// +----------------------------------------------------------------------+
// | Copyright (c) 2015-2019 CN,  All rights reserved.
// +----------------------------------------------------------------------+
// | @Authors: The PHP Dev LiuManMan, Web, <liumansky@126.com>.
// | @Script:
// | @date     2020-01-02 14:39:33
// +----------------------------------------------------------------------+

use Swoole\Coroutine\System;
$filename = __DIR__ . "/2.txt";
$context  = "hello swoole 123!" . PHP_EOL;
go(function () use ($filename, $context) {
    /**
     * function Coroutine\System::writeFile(string $filename, string $fileContent, int $flags);
     * $filename为文件的名称，必须有可写权限，文件不存在会自动创建。打开文件失败会立即返回false
     * $fileContent为要写入到文件的内容，最大可写入4M
     * $flags为写入的选项，默认会清空当前文件内容，可以使用FILE_APPEND表示追加到文件末尾
     * @var [type]
     */
    $r = System::writeFile($filename, $context, FILE_APPEND);
    var_dump($r);
});
