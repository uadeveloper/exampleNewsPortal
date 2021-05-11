<?php

namespace SiteCore\Components\Session;

/**
 * Class Session
 * @package SiteCore\Components\Session
 */
class Session
{

    public function __construct()
    {
        if($_COOKIE[session_name()]) {
            $this->start();
        }
    }

    /**
     * @return bool
     */
    public function isStarted() : bool {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function start() : void {
        session_start();
    }

    /**
     * @param string $key
     * @return mixed
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
    public function __isset(string $key): bool
    {
        return $this->has($key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        if(!$this->isStarted()) {
            return false;
        }

        return array_key_exists($key, $_SESSION);
    }

    /**
     * @param string $key
     * @param $value
     */
    public function __set(string $key, $value) : void
    {
        $this->set($key, $value);
    }

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value) : void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __unset(string $key): bool
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

    public function destroy() : void {
        session_destroy();
    }

}