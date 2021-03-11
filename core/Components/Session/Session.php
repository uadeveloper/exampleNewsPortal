<?php

namespace SiteCore\Components\Session;

/**
 * Class Session
 * @package SiteCore\Components\Session
 */
class Session
{

    public function __construct() {
        session_start();
    }

    /**
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        if ($this->has($key)) {
            return $_SESSION[$key];
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __isset(string $key)
    {
        return $this->has($key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * @param string $key
     * @param $value
     */
    public function __set(string $key, $value)
    {
        return $this->set($key, $value);
    }

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __unset(string $key)
    {
        return $this->delete($key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
            return true;
        }

        return false;
    }

}