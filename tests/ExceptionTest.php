<?php

use Hongbao\Hongbao;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{

    public static $params;

    public static function getParam($name, $default = null)
    {
        if (static::$params === null) {
            static::$params = require(__DIR__ . '/data/BaseConfig.php');
        }

        return isset(static::$params[$name]) ? static::$params[$name] : $default;
    }

    /**
     * 测试异常
     * @expectedException \Exception
     * @expectedExceptionMessage 单个红包金额必须大于等于0.01
     */
    public function testExceptionOnBase()
    {
        $options = self::getParam('exception',[]);
        $hbs = [];
        $money_left = 0.00;
        try {
            $hongbao = Hongbao::getInstance()->fixedAmount($options['baseConfig']);
            foreach ($hongbao as $result) {
                $hbs = array_merge($hbs, $result['data']);
                $money_left = $result['money_left'];
            }
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    
}
