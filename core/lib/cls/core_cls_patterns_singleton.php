<?php
/*
 * this class is use for sigleton design patterns
 * you can use this class in your design with inherte from this class
 * for create an object from your class use this pattern  $object = your_class::singleton();
 */

class Singleton {
    protected static $instance;
 
    private function __clone() { }
    private function __construct() { }
    private function __wakeup() { }
 
    final public static function singleton() {
        if ( !isset( static::$instance ) ) {
            static::$instance = new static();
        }
 
        return static::$instance;
    }
}
?>
