<?php

use Hongbao\Hongbao;
use PHPUnit\Framework\TestCase;

class TestCaseOnBaseRandom extends TestCase
{

    public static $params;

    public $total_money;//总金额

    public function __construct()
    {
        parent::__construct();
    }

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
        $options = self::getParam('baseRandomConfig',[]);
        $money_left = "0.00";
        $hbs = [];
        try {
            $hongbao = Hongbao::getInstance()->randomAmount($options);
            foreach ($hongbao as $result) {
                $hbs = array_merge($hbs, $result['data']);
                $money_left = $result['money_left'];
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
        $total_money = 0;
        $options = self::getParam('baseRandomConfig',[]);
        foreach ($data['data'] as $hb) {
            $total_money = bcadd($hb, $total_money, 2);
        }
        $this->assertEquals($options['total_money'], $total_money);
    }


    
}
