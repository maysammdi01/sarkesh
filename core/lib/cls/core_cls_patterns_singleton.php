<?php
/*
 * this class is use for sigleton design patterns
 * you can use this class in your design with inherte from this class
 * for create an object from your class use this pattern  $object = your_class::singleton();
 */
namespace core\cls\patterns;

trait Singleton
{
    protected static $instance;
    final public static function singleton()
    {
        return isset(static::$instance)
            ? static::$instance
            : static::$instance = new static;
    }
    final private function __construct() {
        $this->init();
    }
    protected function init() {}
    final private function __wakeup() {}
    final private function __clone() {}    
}
?>
