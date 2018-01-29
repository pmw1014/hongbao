<?php

namespace Hongbao\Handlers;

use Hongbao\Contracts\HongbaoContract;
use Hongbao\Library\Gid;

/**
 * 固定红包生成器
 */
class HongbaoHandler implements HongbaoContract
{

    public $gid = 0;

    // 总个数
    public $total_number = 0;

    // 总金额
    public $total_money = 0;

    // 单个红包金额
    public $val = 0;

    // 生成方式
    // 1固定金额 2随机金额
    public $create_way = 0;

    // 剩余金额
    public $left_money = 0;

    // 红包
    public $data = [];

    protected static $create_way_type = 1;

    public function __construct( array $options = [] )
    {
        $this->setOptions($options)->validate()->checkData();
        $this->gid = new Gid();
    }

    // 验证输入参数
    public function validate()
    {
        if ( ! is_int($this->total_number) || (int)$this->total_number < 1) {
            throw new \Exception("红包总数必须是大于等于1的正整数");
        }
        if ( ! is_numeric($this->total_number) || (int)$this->total_money < 1) {
            throw new \Exception("红包总金额必须大于等于0.01");
        }
        if ( ! is_numeric($this->val) || (int)$this->val > 0.01) {
            throw new \Exception("红包金额区间最小值必须大于等于0.01");
        }
        if ( ! is_numeric($this->create_way) || (int)$this->create_way !== (int)self::$create_way_type ) {
            throw new \Exception("红包生成类型值不正确");
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
        $limit = 5000; // 每页红包数量
        $page_count = ceil( $this->total_number / $limit ); // 总页数
        $row_count = $this->total_number; // 总条数
        $this->left_money = $this->total_money; // 剩余金额

        while ( $current_page <= $page_count ) {
            $this->data = [];
            $row_num = ($row_count - $limit) > 0 ? $limit : $row_count; // 当前页生成记录条数
            $this->left_money = $this->left_money - ($row_num * $this->val); // 当前剩余金额
            $row_count -= $row_num; // 更新剩余记录数

            while ($row_num > 0) {
                $this->data[] = [
                    'hb_id' => $this->getId(),
                    'money' => $this->val,
                ];
                $row_num--;
            }
            $stop = yield [ 'data' => $this->data, 'left_money' => $this->left_money];
            if ($stop === true) {
                return;
            }

            $current_page++;
        }

    }

    private function getId()
    {
        return $this->gid->getId();
    }
}
