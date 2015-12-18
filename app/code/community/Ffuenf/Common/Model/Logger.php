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

final class Ffuenf_Common_Model_Logger
{
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
    public static function _getConfig()
    {
        return Mage::getSingleton('ffuenf_common/config');
    }

    /**
     * Returns path for selected logs and creates missing folder if needed
     *
     * @return string|null
     */
    public static function getAbsoluteLogDirPath()
    {
        try {
            $io = new Varien_Io_File();
            $logDir = Mage::getBaseDir('log') . DS . self::FFUENF_LOG_DIR;
            $io->checkAndCreateFolder($logDir, 0755);
            return $logDir;
        } catch (Exception $e) {
            Mage::logException($e);
        }
        return null;
    }

    /**
     * Returns path for selected logs and creates missing folder if needed
     *
     * @param string $logType
     * @return string|null
     */
    public static function getLogFileName($logType = 'system')
    {
        switch ($logType) {
            case 'exception':
                return self::FFUENF_EXCEPTION_LOG_FILE;
            case 'system':
                return self::FFUENF_LOG_FILE;
            case 'profile':
                return self::FFUENF_PROFILE_LOG_FILE;
            default:
                return self::FFUENF_LOG_FILE;
        }
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
            $logDir = self::getAbsoluteLogDirPath();
            switch ($logType) {
                case 'exception':
                    return $logDir . DS . self::getLogFileName('exception');
                case 'system':
                    return $logDir . DS . self::getLogFileName('system');
                case 'profile':
                    return $logDir . DS . self::getLogFileName('profile');
                default:
                    return $logDir . DS . self::getLogFileName();
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
    public static function logSystem($logData)
    {
        if (self::_getConfig()->isLoggingActive()) {
            array_unshift($logData, Mage::getModel('core/date')->gmtTimestamp());
            self::_writeCsv(self::getAbsoluteLogFilePath('system'), self::getLogFileName('system'), $logData);
        }
    }

    /**
     * Logs performance data
     *
     * @param array $logData
     */
    public static function logProfile($logData)
    {
        if (self::_getConfig()->isLoggingActive()) {
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
            self::_writeCsv(self::getAbsoluteLogFilePath('profile'), self::getLogFileName('profile'), $profileData);
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
            $exceptionData = array(
                'timestamp' => Mage::getModel('core/date')->gmtTimestamp(),
                'exception_code' => $e->getCode(),
                'exception_message' => $e->getMessage(),
                'exception_trace' => $e->getTraceAsString()
            );
            self::_writeCsv(self::getAbsoluteLogFilePath('exception'), self::getLogFileName('exception'), $exceptionData);
        }
    }

    /**
     * Logs
     *
     * @param string $filePath
     * @param string $fileName
     * @param array $logData
     */
    protected function _writeCsv($filePath, $fileName, $logData)
    {
        $io = new Varien_Io_File();
        if ($io->fileExists($filePath, true)) {
            $io->open(array('path' => self::getAbsoluteLogDirPath()));
            $io->streamOpen($fileName, 'w+');
            $io->streamLock(true);
            $io->streamWriteCsv($logData, self::_getConfig()->getLogDelimiter(), self::_getConfig()->getLogEnclosure());
            $io->close();
            $io->streamUnlock();
        } else {
            Mage::log('FFUENF: unable to open ' . $filePath . ' for writing.');
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
    }

    public static function rotateLogfiles()
    {
        $logTypes = array('exception', 'system', 'profile');
        $maxFilesize = self::LOGFILE_ROTATION_SIZE * 1048576;
        foreach ($logTypes as $logType) {
            $io = new Varien_Io_File();
            $filepathdir = self::getAbsoluteLogDirPath();
            $filepath = self::getAbsoluteLogFilePath($logType);
            try {
                if (Mage::helper('ffuenf_common/file')->isBiggerThan($filepath, $maxFilesize)) {
                    $io->open(array('path' => $filepathdir));
                    $io->mv($filepath, $filepath . '.' . Mage::getModel('core/date')->date("Ymdhis"));
                    $io->close();
                }
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }
}
