<?php

class Util
{
    protected static $redirectHandler;

    protected static $exitHandler;
  
    public static function redirect($url)
    {
        if (static::$redirectHandler) {
            return call_user_func(static::$redirectHandler, $url);
        }

        header(sprintf('Location: %s', $url));

        if (static::$exitHandler) {
            return call_user_func(static::$exitHandler);
        }
        exit(1);
    }

    public static function setRedirectHandler($callback)
    {
        self::$redirectHandler = $callback;
    }

    public static function setExitHandler($callback)
    {
        self::$exitHandler = $callback;
    }

}
