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

    // 生成方式
    // 1固定金额 2随机金额
    public $create_way = 0;

    public $limit = 0;

    // 当前页记录数
    public $page_row_num = 0;

    public function __construct( array $options = [] )
    {
        $this->setOptions($options)->validate()->checkData();
        $this->limit = 5000;
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
        $left_row_count = $this->total_number; // 剩余总条数
        $left_money = $this->total_money; // 剩余金额

        while ( $current_page <= $page_count ) {
            $data = [];
            $this->page_row_num = ($left_row_count - $this->limit) > 0 ? $this->limit : $left_row_count; // 当前页生成记录条数
            $left_money -= ($this->page_row_num * $this->val); // 当前剩余金额
            $left_row_count -= $this->page_row_num; // 更新剩余记录数
            $data = $this->hb();
            $stop = yield [ 'data' => $data, 'left_money' => $left_money];
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
        }
        return $data;
    }
}
