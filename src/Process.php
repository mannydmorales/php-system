<?php

namespace mannydmorales\System;

/**
 * Process class. This class is used to manage processes
 * 
 * @package mannydmorales\System
 * @file Process.php
 * @author Manny Morales <mannydmorales@gmail.com>
 * @copyright Manny D. Morales
 */
class Process {

    /**
     * Check if a process is running
     *
     * @param  int $pid PID of the process to check
     * @return bool
     */
    public static function isRunning(int $pid) : bool {
        if($pid !== false || $pid !== 0) {
            if(OS::isUnix()) {
                // Unix-based systems (Linux, Mac)
                exec('ps -p ' . escapeshellarg($pid), $op);
                if(!isset($op[1])) {
                    return false;
                }
                return true;
            } else {
                // Windows
                exec('tasklist /FI "PID eq ' . escapeshellarg($pid) . '"', $op);
                if(!isset($op[1])) {
                    return false;
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Kill a running process
     * 
     * @param int $pid The PID of the process to kill
     * @return bool
     */
    public static function kill(int $pid) : bool {
        if(self::isRunning($pid)) {
            if(OS::isUnix()) {
                exec('kill '.escapeshellarg($pid));
            } else {
                exec('taskkill /PID '.escapeshellarg($pid).' /F');
            }
            if(!self::isRunning($pid)) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * Start a background process 
     *
     * @param  string $exe execution command (with arguments)
     * @param  string $log path to log file for standard output. Default is /dev/null (no output)
     * @param  string $error path to log file for standard error output. Default is /dev/null (no output)
     * @return int PID of the process
     */
    public static function start(string $exe, string $log = '/dev/null', string $error = '/dev/null') : int {
        if(OS::isUnix()) {
            // Unix-based systems (Linux, Mac)
            exec('nohup '.$exe.' > '.$log.' 2> '.$error.' < /dev/null & echo $!', $op);
            return (int) $op[0];
        } else {
            // Windows
            exec('start /B '.$exe.' > '.$log.' 2> '.$error, $op);
            return 0; // Windows does not provide PID easily. Return 0 as a placeholder
        }
    }

    /**
     * Get the name of a process by its PID
     * 
     * @param int $pid The PID of the process
     * @return string|false The name of the process. False if the process is not running, or name cannot be determined
     */
    public static function getProcessName(int $pid) : string|false {
        try {
            $name = '';
            if(self::isRunning($pid)) {
                if(OS::isUnix()) {
                    // Unix-based systems (Linux, Mac)
                    exec('ps -p '.escapeshellarg($pid).' -o comm=', $op);
                    if(isset($op[0])) {
                        $name = trim($op[0]);
                    }
                } else {
                    // Windows
                    exec('tasklist /FI "PID eq ' . escapeshellarg($pid) . '" /FO CSV /NH', $op);
                    if(isset($op[0])) {
                        $parts = str_getcsv($op[0]);
                        if(isset($parts[0])) {
                            $name = trim($parts[0], '"');
                        }
                    }
                }
            }
            return $name !== '' ? $name : false;
        } catch(\Exception $e) {
            return false;
        }
    }

}