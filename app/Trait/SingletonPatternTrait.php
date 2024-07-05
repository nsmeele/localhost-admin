<?php

namespace Trait;

trait SingletonPatternTrait
{
    protected static $instance;

    /**
     * Protected constructor to prevent creating a new instance of the
     * Singleton via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Prevent instance from being cloned.
     */
    protected function __clone()
    {
    }

    /**
     * Prevent instance from being unserialized.
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    public static function getInstance(): static
    {
        if (static::$instance instanceof static) {
            return static::$instance;
        }

        static::$instance = new static();
        return static::$instance;
    }
}
