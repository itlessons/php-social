<?php

namespace Social\Util;


class StorageTempData
{
    public function load($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function save($key, $data)
    {
        $_SESSION[$key] = $data;
    }

    public function delete($key)
    {
        if (isset($_SESSION[$key]))
            unset($_SESSION[$key]);
    }
} 