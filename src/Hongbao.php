<?php

namespace Hongbao;

use Hongbao\Contracts\Handlers;


/**
 * 红包红包生成器
 */
class Hongbao
{
    // 自身实例
    protected static $instance = null;

    protected $handlers = [];

    public function __construct()
    {
        $this->handlers = $this->getHandlers();
    }

    /**
     * 获取handler对象
     * @brief  [description]
     * @author zicai
     * @date   2018-01-26T17:41:30+080
     *
     * @return [array]
     */
    protected function getHandlers()
    {
        return array(
            'random' => 'Hongbao\Contracts\Handlers\RandomHongbaoHandler', // 随机红包
        );
    }

    public function __call( string $name , array $args )
    {
        if (isset($this->handlers[$name])) {
            $handler = $this->handlers[$name];
            $handler = new $handler($args[0]);
            return $handler->create();
        }
        
        throw new \Exception("{$name} 不存在");
    }

    // 获取实例
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
