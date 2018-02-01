<?php

require_once '../autoload.php';

use Hongbao\Hongbao;

/**
 * 生成固定红包
 */

$m1 = memory_get_usage();
$t1 = microtime(true);
$options = [
    'total_money' => 1000, // 总金额
    'total_number' => 1000, // 总红包数量
    'val' => 0.01, // 单个红包金额
    'limit'=>100,
];

try {
    $hongbao = Hongbao::getInstance()->fixedAmount($options);
    echo "<pre/>";
    foreach ($hongbao as $result) {
        print_r($result);
    }
} catch (\Exception $e) {
    $error = $e->getMessage();
    var_dump($error);
}
echo "Down\n";
echo "耗时：" . (microtime(true)-$t1);
echo "\n";
echo "消耗内存：" . round((memory_get_usage()-$m1)/1024/1024,2)."MB\n";
