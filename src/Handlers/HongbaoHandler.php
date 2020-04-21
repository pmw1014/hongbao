<?php

namespace Hongbao\Handlers;

use Hongbao\Contracts\HongbaoContract;

/**
 * 固定红包生成器
 */
class HongbaoHandler implements HongbaoContract
{

    // 总个数
    public $total_number = 0;

    // 总金额
    public $total_money = 0;

    // 单个红包金额
    public $val = 0;

    // 每页生成红包数
    public $limit = 5000;

    // 当前页记录数
    public $page_row_num = 0;

    // 剩余总条数
    public $left_row_count = 0;

    // 剩余金额
    public $money_left = 0;

    public function __construct( array $options = [] )
    {
        $this->setOptions($options)->validate()->checkData();
    }

    // 验证输入参数
    public function validate()
    {
        if ( ! is_int($this->total_number) || (int)$this->total_number < 1) {
            throw new \Exception("红包总数必须是大于等于1的正整数");
        }
        if ( (float)$this->total_money < 0.01) {
            throw new \Exception("红包总金额必须大于等于0.01");
        }
        if ( (float)$this->val < 0.01) {
            throw new \Exception("单个红包金额必须大于等于0.01");
        }
        return $this;
    }

    // 接受参数
    public function setOptions(array $options = [])
    {
        if (array_filter($options)) {
            foreach ( $options as $key => $option ) {
                if ( ! is_numeric($option) ) {
                    throw new \Exception("{$key} 必须为数字");
                }
                if ( isset($this->$key) ) {
                    $this->$key = $option;
                }
            }
        }
        return $this;
    }

    // 验证数据有效性
    public function checkData()
    {
        if ( ($this->total_money / $this->total_number) < $this->val ) {
            throw new \Exception("设置的红包个数与总金额不满足单个红包金额{$this->val} 元的要求");
        }
        return $this;
    }

    /**
     * 分页发红包
     * @brief  [description]
     * @author zicai
     * @date   2018-01-29T15:19:36+080
     *
     * @return [array]
     */
    public function create()
    {
        $current_page = 1; // 当前页
        $page_count = ceil( $this->total_number / $this->limit ); // 总页数
        $this->left_row_count = $this->total_number; // 剩余总条数
        $this->money_left = $this->total_money; // 剩余金额

        while ( $current_page <= $page_count ) {
            $data = [];
            $this->page_row_num = ($this->left_row_count - $this->limit) > 0 ? $this->limit : $this->left_row_count; // 当前页生成记录条数
            $data = $this->hb();
            $stop = yield [ 'data' => $data, 'money_left' => $this->money_left];
            if ($stop === true) {
                return;
            }

            $current_page++;
        }

    }

    //生成红包
    public function hb()
    {
        $data = [];
        while ($this->page_row_num > 0) {
            $data[] = $this->val;
            $this->page_row_num--;
            $this->left_row_count--;
            $this->money_left = bcsub($this->money_left, $this->val, 2); // 当前剩余金额
        }
        return $data;
    }
}
