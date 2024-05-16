<?php
class RoleModel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new RoleModel();
        }

        return self::$instance;
    }
}
