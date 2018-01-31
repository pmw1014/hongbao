<?php

require_once '../vendor/autoload.php';

use Hongbao\Hongbao;

/**
 * 生成随机红包
 */

$m1 = memory_get_usage();
$t1 = microtime(true);
$options = [
    'total_money' => 1000, // 总金额
    'total_number' => 1000, // 总红包数量
    'minimum_val' => 0.01, // 最小随机红包金额
    'maximum_val' => 20, // 最大随机红包金额
];

try {
    $hongbao = Hongbao::getInstance()->randomAmount($options);
    foreach ($hongbao as $result) {
        echo "<pre/>";
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
