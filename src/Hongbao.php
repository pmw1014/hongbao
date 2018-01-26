<?php

namespace Hongbao;

use Hongbao\Contracts\HongbaoContract;


/**
 * 红包红包生成器
 */
class Hongbao implements HongbaoContract
{

    // 总个数
    public $total_number = 0;

    // 总金额
    public $total_money = 0;

    // 最小金额
    public $minimun_val = 0;

    // 最大金额
    public $maximum_val = 0;

    // 生成方式
    // 1固定金额 2随机金额
    public $create_way = 0;

    // 剩余金额
    public $left_money = 0;

    function __construct(array $options = [])
    {
        echo 'hello';
    }

    // 验证输入参数
    public function validate(){
    }

    // 接受参数
    public function setOptions(){

    }

    // 验证数据有效性
    public function checkData(){

    }

    // 发红包
    public function create(){

    }

}
