<?php

namespace Hongbao\Handlers;

use Hongbao\Contracts\HongbaoContract;

/**
 * 固定红包生成器
 */
class RandomHongbaoHandler implements HongbaoContract
{
	const EPSILON = 0.01;

	const TOW_PI = 2.0*3.14159265358979323846;

    public static $generate = false;

    // 总个数
    public $total_number = 0;

    // 总金额
    public $total_money = 0;

    // 单个红包最小金额
    public $minimum_val = 0;

    // 单个红包最大金额
    public $maximum_val = 0;

    // 每页生成红包数
    public $limit = 5000;

    // 当前页记录数
    public $page_row_num = 0;

    // 剩余总条数
    public $left_row_count = 0;

    // 剩余金额
    public $money_left = 0;

    // 剩余金额平均值
    public $money_left_avg = 0;

    public function __construct( array $options = [] )
    {
        $this->setOptions($options)->validate()->checkData();
    }

    // 验证输入参数
    public function validate()
    {
        if ( ! is_int($this->total_number) || (int)$this->total_number < 1) {
            throw new \Exception("输入的红包总数必须是大于等于1的正整数");
        }
        if ( (float)$this->total_money < 0.01) {
            throw new \Exception("输入的红包总金额必须大于等于0.01");
        }
        if ( (float)$this->minimum_val < 0.01) {
            throw new \Exception("输入的单个红包金额最小值必须大于等于0.01");
        }
        if ( (float)$this->maximum_val < 0.01) {
            throw new \Exception("输入的单个红包金额最大值必须大于等于0.01");
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
        if ( $this->maximum_val == $this->minimum_val ) {
            throw new \Exception("设置的红包金额最大值与最小值相等，不可使用随机生成");
        }
        if ( ($this->total_money / $this->total_number) < $this->minimum_val ) {
            throw new \Exception("设置的红包个数与总金额不满足单个红包最小金额{$this->minimum_val} 元的要求");
        }
        if ( $this->minimum_val > $this->maximum_val ) {
            throw new \Exception("设置的红包金额最小值不能大于最大值");
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
        $mu = 0;//实时剩余金额均值
		$sigma = 0;//均值修正指数
		$noise_value = 0;//当前红包金额
        $this->money_left_avg = $this->money_left - $this->total_number * $this->minimum_val;//实时剩余金额平均值
        while ($this->page_row_num > 0) {
            $mu = $this->money_left_avg / $this->left_row_count;
			$sigma = $mu / 2;
			$noise_value = $this->gaussNoise($mu, $sigma);
			//截尾处理
			$noise_value = $noise_value < 0 ? 0 : $noise_value;
			$noise_value = $noise_value > $this->money_left_avg ? $this->money_left_avg : $noise_value;
			$noise_value = $noise_value > ($this->maximum_val - $this->minimum_val) ? ($this->maximum_val - $this->minimum_val) : $noise_value;

            $val = $noise_value + $this->minimum_val;
            $val = $val > 0 ? $val : $this->minimum_val;
            $this->money_left_avg -= $val;
			$data[] = $val;
            $this->money_left -= $val; // 当前剩余金额

            $this->page_row_num--;
            $this->left_row_count--; // 更新剩余记录数
        }
        return $data;
    }

    function gaussNoise($mu, $sigma)
	{
		static $rand0;
		static $rand1;

		if (self::$generate)
		{
			self::$generate = false;
			return sprintf("%.2f", $rand1 * $sigma + $mu);
		}

		$u1 = 0;
		$u2 = 0;
		do
		{
			$u1 = mt_rand() * (1.0 / mt_getrandmax());
			$u2 = mt_rand() * (1.0 / mt_getrandmax());
		} while ($u1 <= self::EPSILON);

		$rand0 = sqrt(-2.0 * log($u1)) * cos(self::TOW_PI * $u2);
		$rand1 = sqrt(-2.0 * log($u1)) * sin(self::TOW_PI * $u2);
		self::$generate = true;

		return sprintf("%.2f", $rand0 * $sigma + $mu);
	}
}
