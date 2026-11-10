<?php

namespace mannydmorales\System;

/**
 * OS class. This class is used to manage operating system interactions
 * 
 * @package mannydmorales\System
 * @file OS.php
 * @author Manny Morales <mannydmorales@gmail.com>
 * @copyright Manny D. Morales
 */
class OS {

    // OS Types
    public const OS_TYPE_WINDOWS    = "WIN";
    public const OS_TYPE_LINUX      = "LIN";
    public const OS_TYPE_MAC        = "DAR";

    // OS Type Names
    public const OS_TYPE            = array(
        self::OS_TYPE_WINDOWS           => "Windows",
        self::OS_TYPE_LINUX             => "Linux",
        self::OS_TYPE_MAC               => "Mac"
    );

    /**
     * Check if the current OS is Windows
     * @return bool 
     */
    public static function isWindows() : bool {
        return strtoupper(substr(PHP_OS, 0, 3)) === self::OS_TYPE_WINDOWS;
    }

    /**
     * Check if the current OS is Windows
     * @return bool 
     */
    public static function isMac() : bool {
        return strtoupper(substr(PHP_OS, 0, 3)) === self::OS_TYPE_MAC;
    }

    /**
     * Check if the current OS is Windows
     * @return bool 
     */
    public static function isLinux() : bool {
        return strtoupper(substr(PHP_OS, 0, 3)) === self::OS_TYPE_LINUX;
    }

    /**
     * Check if the current OS is Unix (Mac or Linux)
     * @return bool 
     */
    public static function isUnix() : bool {
        return self::isMac() || self::isLinux();
    }

    /**
     * Get the current OS type
     * @return string|false Will return false if the OS type is not Windows, Mac, or Linux
     */
    public static function getOSType() : string|false {
        if(self::isWindows()) {
            return self::OS_TYPE[self::OS_TYPE_WINDOWS];
        } elseif(self::isMac()) {
            return self::OS_TYPE[self::OS_TYPE_MAC];
        } elseif(self::isLinux()) {
            return self::OS_TYPE[self::OS_TYPE_LINUX];
        }
        return false;
    }

}