<?php
// +----------------------------------------------------------------------+
// | @describe  swoole4.3.0以上已经废弃异步IO-Async（注释掉的代码)
//              swoole4.3.0以上使用没注释的代码，文档在下Coroutine\System::readFile
// +----------------------------------------------------------------------+
// | Copyright (c) 2015-2019 CN,  All rights reserved.
// +----------------------------------------------------------------------+
// | @Authors: The PHP Dev LiuManMan, Web, <liumansky@126.com>.
// | @Script:
// | @date     2020-01-02 14:32:05
// +----------------------------------------------------------------------+

//文件所在的目录。如果用在被包括文件中，则返回被包括的文件所在的目录。它等价于 dirname(__FILE__)。
//除非是根目录，否则目录中名不包括末尾的斜杠。（PHP 5.3.0中新增）
// $result = Swoole\Async::readfile(__DIR__ . "/1.txt", function ($filename, $fileContent) {
//     echo "filename:" . $filename . PHP_EOL; // \n \r\n
//     echo "content:" . $fileContent . PHP_EOL;
// });

// var_dump($result);
// echo "start" . PHP_EOL;

use Swoole\Coroutine\System;

$filename = __DIR__ . "/1.txt";

go(function () use ($filename) {
    // $handle文件句柄，必须是fopen打开的文件类型stream资源
    // $length读取的长度，默认为0，表示读取文件的全部内容
    $r = System::readFile($filename);
    var_dump($r);
});
