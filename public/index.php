<?php
// [ 应用入口文件 ]
namespace think;

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';
define('SITE_URL', 'http://127.0.0.1/Postbar');

// 执行应用并响应
Container::get('app')->run()->send();
