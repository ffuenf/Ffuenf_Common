<?php
/**
 * Ffuenf_Common extension.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category   Ffuenf
 *
 * @author     Achim Rosenhagen <a.rosenhagen@ffuenf.de>
 * @copyright  Copyright (c) 2015 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

final class Ffuenf_Common_Model_Logger {

    const FFUENF_LOG_DIR            = 'ffuenf';
    const FFUENF_LOG_FILE           = 'system.log';
    const FFUENF_PROFILE_LOG_FILE   = 'profile.log';
    const FFUENF_EXCEPTION_LOG_FILE = 'exception.log';
    const LOGFILE_ROTATION_SIZE     = 8;

    /**
     * Returns config model instance
     *
     * @return Ffuenf_Common_Model_Config
     */
    private static function _getConfig() {
        return Mage::getSingleton('ffuenf_common/config');
    }

    /**
     * Returns path for selected logs and creates missing folder if needed
     *
     * @param string $logType
     * @return string|null
     */
    public static function getAbsoluteLogFilePath($logType)
    {
        try {
            $logDir = Mage::getBaseDir('log') . DS . self::FFUENF_LOG_DIR;
            if (!is_dir($logDir)) {
                mkdir($logDir, 0777, true);
            }
            switch ($logType) {
                case 'exception':
                    return $logDir . DS . self::FFUENF_EXCEPTION_LOG_FILE;
                case 'system':
                    return $logDir . DS . self::FFUENF_LOG_FILE;
                case 'profile':
                    return $logDir . DS . self::FFUENF_PROFILE_LOG_FILE;
                default:
                    return $logDir . DS . self::FFUENF_LOG_FILE;
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
        return null;
    }

    /**
     * Logs
     *
     * @param array $logData
     */
    public static function logSystem($logData) {
        if (self::_getConfig()->isLoggingActive()) {
            array_unshift($logData, Mage::getModel('core/date')->gmtTimestamp());
            if (($fileHandle = fopen(self::getAbsoluteLogFilePath('system'), 'a')) !== false) {
                fputcsv($fileHandle, $logData, self::_getConfig()->getLogDelimiter(), self::_getConfig()->getLogEnclosure());
                fclose($fileHandle);
            } else {
                Mage::log('FFUENF: unable to open ' . self::getAbsoluteLogFilePath('system') . ' for writing.');
            }
        }
    }

    /**
     * Logs performance data
     *
     * @param array $logData
     */
    public static function logProfile($logData) {
        if (self::_getConfig()->isLoggingActive()) {
            if (($fileHandle = fopen(self::getAbsoluteLogFilePath('profile'), 'a')) !== false) {
                $message = (array_key_exists('message', $logData) ? $logData['message'] : '');
                $profileData = array(
                    'timestamp' => Mage::getModel('core/date')->gmtTimestamp(),
                    'class' => $logData['class'],
                    'type' => $logData['type'],
                    'items' => $logData['items'],
                    'page' => $logData['page'],
                    'start' => date('H:i:s', $logData['start']['time']),
                    'stop' => date('H:i:s', $logData['stop']['time']),
                    'duration' => date('H:i:s', ($logData['stop']['time'] - $logData['start']['time'])),
                    'memory' => Mage::helper('ffuenf_common')->convert($logData['stop']['memory'] - $logData['start']['memory']),
                    'message' => $message
                );
                fputcsv($fileHandle, $profileData, self::_getConfig()->getLogDelimiter(), self::_getConfig()->getLogEnclosure());
                fclose($fileHandle);
            } else {
                Mage::log('FFUENF: unable to open ' . self::getAbsoluteLogFilePath('profile') . ' for writing.');
            }
        }
    }

    /**
     * Logs an exception thrown
     *
     * @param Exception $e
     */
    public static function logException(Exception $e)
    {
        if (self::_getConfig()->isLoggingActive()) {
            if (($fileHandle = fopen(self::getAbsoluteLogFilePath('exception'), 'a')) !== false) {
                $exceptionData = array(
                    'timestamp' => Mage::getModel('core/date')->gmtTimestamp(),
                    'exception_code' => $e->getCode(),
                    'exception_message' => $e->getMessage(),
                    'exception_trace' => $e->getTraceAsString()
                );
                fputcsv($fileHandle, $exceptionData, self::_getConfig()->getLogDelimiter(), self::_getConfig()->getLogEnclosure());
                fclose($fileHandle);
            } else {
                Mage::log('FFUENF: unable to open ' . self::getAbsoluteLogFilePath('exception') . ' for writing.');
            }
        }
    }

    /**
     * @param string $logType
     */
    public static function getColumnMapping($logType)
    {
        switch ($logType) {
            case 'exception':
                return array('timestamp', 'exception_code', 'exception_message', 'exception_trace');
            case 'system':
                return array('timestamp', 'extension', 'type', 'message');
            case 'profile':
                return array('timestamp', 'class', 'type', 'items', 'page', 'start', 'stop', 'duration', 'memory', 'message');
            default:
                return array('timestamp', 'extension', 'type', 'message');
        }
        return null;
    }

    public static function rotateLogfiles()
    {
        $logTypes = array('exception', 'system');
        $maxFilesize = self::LOGFILE_ROTATION_SIZE * 1048576;
        foreach ($logTypes as $logType) {
            $filepath = self::getAbsoluteLogFilePath($logType);
            if (file_exists($filepath) && filesize($filepath) > $maxFilesize) {
                rename($filepath, $filepath . '.' . Mage::getModel('core/date')->date("Ymdhis"));
            }
        }
    }
}
