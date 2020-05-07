<?php

namespace Hongbao\Library\Helpers;

class MathHelper
{

    //小数点后精度
    const DECIMAL_PRECISION = 4;
    
    /**
     * 加法
     *
     * @param string $left_operand
     * @param string $right_operand
     * @return string
     */
    public static function mbcadd( string $left_operand , string $right_operand, int $scale = self::DECIMAL_PRECISION) :string
    {
        return bcadd($left_operand, $right_operand, $scale);
    }

        
    /**
     * 减法
     *
     * @param string $left_operand
     * @param string $right_operand
     * @return string
     */
    public static function mbcsub( string $left_operand , string $right_operand, int $scale = self::DECIMAL_PRECISION) :string
    {
        return bcsub($left_operand, $right_operand, $scale);
    }

        
    /**
     * 乘法
     *
     * @param string $left_operand
     * @param string $right_operand
     * @return string
     */
    public static function mbcmul( string $left_operand , string $right_operand, int $scale = self::DECIMAL_PRECISION) :string
    {
        return bcmul($left_operand, $right_operand, $scale);
    }

        
    /**
     * 除法
     *
     * @param string $left_operand
     * @param string $right_operand
     * @return string
     */
    public static function mbcdiv( string $left_operand , string $right_operand, int $scale = self::DECIMAL_PRECISION) :string
    {
        return bcdiv($left_operand, $right_operand, $scale);
    }

}
