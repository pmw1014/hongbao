<?php

namespace Hongbao\Contracts;

/**
 * 生成红包
 */
interface HongbaoContract
{
    // 验证输入参数
    public function validate();
    // 接受参数
    public function setOptions();
    // 验证数据有效性
    public function checkData();
    // 发红包
    public function create();
}
