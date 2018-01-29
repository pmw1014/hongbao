<?php

namespace Hongbao\Contracts\Handlers;

use Hongbao\Contracts\HongbaoContract;
use Hongbao\Hongbao;

/**
 * 随机红包
 */
class RandomHongbaoHandler implements HongbaoContract
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

    public function __construct( array $options = [] )
    {
        $this->setOptions($options);
        $this->validate($options);
    }

    // 验证输入参数
    public function validate( array $options = [] ){
        var_dump($this);
        return $this;
    }

    // 接受参数
    public function setOptions(array $options = []){
        if (array_filter($options)) {
            foreach ( $options as $key => $option ) {
                if ( isset($this->$key) ) {
                    $this->$key = $option;
                }
            }
        }
        return $this;
    }

    // 验证数据有效性
    public function checkData(){

    }

    // 发红包
    public function create(){
        $res = [];
        return $res;
    }
}
