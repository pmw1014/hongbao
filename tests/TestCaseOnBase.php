<?php

use Hongbao\Hongbao;
use PHPUnit\Framework\TestCase;

class TestCaseOnBase extends TestCase
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
     * 测试生成数量是否正确
     */
    public function testCheckCount()
    {
        $options = self::getParam('baseConfig',[]);
        $hbs = [];
        $money_left = 0.00;
        try {
            $hongbao = Hongbao::getInstance()->fixedAmount($options);
            foreach ($hongbao as $result) {
                $hbs = array_merge($hbs, $result['data']);
                $money_left = $result['left_money'];
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }
        $this->assertCount($options['total_number'], $hbs);
        
        return [
            'data' => $hbs,
            'money_left'=> $money_left
        ];
    }

    /**
     * 测试验证总金额是否一致
     * 
     * @depends testCheckCount
     */
    public function testCheckTotal(array $data)
    {
        $total = $data['money_left'];
        $options = self::getParam('baseConfig',[]);
        foreach ($data['data'] as $hb) {
            $total = bcadd($hb, $total, 2);
        }
        $this->assertEquals($options['total_money'], $total);
    }


    
}
