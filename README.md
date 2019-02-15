# 红包生成器

生成固定红包与随机红包，随机红包金额依据截尾正态分布算法来生成
>Generate fixed red package and random red package, random red envelopes based on truncated normal distribution algorithm to generate

![图像画案例](http://zicai.fun/images/161e0e8515db4a43.gif)

### 生成随机红包(Random Red Package) ###

```php
require_once '../vendor/autoload.php';

use Hongbao\Hongbao;

/**
 * 生成随机红包
 */
$options = [
    'total_money' => 1000, // 总金额
    'total_number' => 1000, // 总红包数量
    'minimum_val' => 0.01, // 最小随机红包金额
    'maximum_val' => 20, // 最大随机红包金额
];

//通过try catch获取可能出现的参数设置错误信息
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
```


### 生成固定红包(fixed red package) ###
```php
require_once '../vendor/autoload.php';

use Hongbao\Hongbao;

/**
 * 生成固定红包
 */

$options = [
    'total_money' => 1000, // 总金额
    'total_number' => 1000, // 总红包数量
    'val' => 0.01, // 单个红包金额
];

//通过try catch获取可能出现的参数设置错误信息
try {
    $hongbao = Hongbao::getInstance()->fixedAmount($options);
    foreach ($hongbao as $result) {
        echo "<pre/>";
        print_r($result);
    }
} catch (\Exception $e) {
    $error = $e->getMessage();
    var_dump($error);
}
```
