<?php

$params = [
    'baseConfig' => [//固定红包
        'total_money' => 10000, // 总金额
        'total_number' => 1009, // 总数量
        'val' => 0.01, // 单个金额
        'limit'=>100, //每次生成100个金额
    ],
    'baseRandomConfig' => [//随机红包
        'total_money' => isset($_POST['total_money']) ? (float)$_POST['total_money'] : 2000, // 总金额
        'total_number' => isset($_POST['total_number']) ? (int)$_POST['total_number'] : 1009, // 总数量
        'minimum_val' => isset($_POST['minimum_val']) ? (float)$_POST['minimum_val'] : 0.01, // 最小随机金额
        'maximum_val' => isset($_POST['maximum_val']) ? (float)$_POST['maximum_val'] : 20, // 最大随机金额
        'limit'=>100, //每次生成100个金额
    ],
];

return $params;