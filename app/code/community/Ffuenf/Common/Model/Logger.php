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
 * @copyright  Copyright (c) 2016 ffuenf (http://www.ffuenf.de)
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
     * Check if a helper method on a given extension exists
     *
     * @return bool
     */
    public function checkExtensionHelperMethod($extensionNameLower, $method)
    {
        try {
            $helper = Mage::helper($extensionNameLower);
            return (is_callable(array($helper, $method), true, $method) && method_exists(Mage::helper($extensionNameLower), $method));
        } catch (Exception $e) {
            self::logException($e);
        }
    }

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
            $io->setAllowCreateFolders(true);
            $logDir = Mage::getBaseDir('log') . DS . self::FFUENF_LOG_DIR;
            $io->checkAndCreateFolder($logDir, 0755);
            $io->close();
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
     * @return string
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
        if (!self::_getConfig()->isLogActive()) {
            return;
        }
        $origin = Mage::app()->getRequest()->getControllerModule();
        $logData['class']   = isset($logData['class']) ? $logData['class'] : $origin;
        $extensionNameLower = strtolower($logData['class']);
        if ($this->checkExtensionHelperMethod($extensionNameLower, 'isLogActive') && !Mage::helper($extensionNameLower)->isLogActive()) {
            return;
        }
        array_unshift($logData, Mage::getModel('core/date')->gmtTimestamp());
        $logData['class'] = isset($logData['class']) ? $logData['class'] : $logData['class'];
        $logData['origin'] = $origin;
        self::_writeCsv(self::getLogFileName('system'), $logData);
    }

    /**
     * Logs performance data
     *
     * @param array $logData
     */
    public static function logProfile($logData)
    {
        if (!self::_getConfig()->isLogProfileActive()) {
            return;
        }
        $message = (array_key_exists('message', $logData) ? $logData['message'] : '');
        $logData['class'] = isset($logData['class']) ? $logData['class'] : Mage::app()->getRequest()->getControllerModule();
        $extensionNameLower = strtolower($logData['class']);
        if ($this->checkExtensionHelperMethod($extensionNameLower, 'isLogProfileActive') && !Mage::helper($extensionNameLower)->isLogProfileActive()) {
            return;
        }
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
        self::_writeCsv(self::getLogFileName('profile'), $profileData);
    }

    /**
     * Logs an exception thrown
     *
     * @param Exception $e
     */
    public static function logException(Exception $e)
    {
        if (!self::_getConfig()->isLogExceptionActive()) {
            return;
        }
        $extension = Mage::app()->getRequest()->getControllerModule();
        $extensionNameLower = strtolower($extension);
        if ($this->checkExtensionHelperMethod($extensionNameLower, 'isLogExceptionActive') && !Mage::helper($extensionNameLower)->isLogExceptionActive()) {
            return;
        }
        $exceptionData = array(
            'timestamp' => Mage::getModel('core/date')->gmtTimestamp(),
            'class' => $extension,
            'exception_code' => $e->getCode(),
            'exception_message' => $e->getMessage(),
            'exception_trace' => $e->getTraceAsString()
        );
        self::_writeCsv(self::getLogFileName('exception'), $exceptionData);
    }

    /**
     * Logs
     *
     * @param string $fileName
     * @param array $logData
     */
    protected static function _writeCsv($fileName, $logData)
    {
        try {
            $io = new Varien_Io_File();
            $io->open(array('path' => self::getAbsoluteLogDirPath()));
            $io->streamOpen($fileName, 'r');
            $data = array();
            while ($existingData = $io->streamReadCsv(self::_getConfig()->getLogDelimiter(), self::_getConfig()->getLogEnclosure())) {
                $data[] = $existingData;
            }
            $io->streamClose();
            $data[] = $logData;
            $io->streamOpen($fileName, 'w+');
            $io->streamLock(true);
            foreach ($data as $dataLine) {
                $io->streamWriteCsv($dataLine, self::_getConfig()->getLogDelimiter(), self::_getConfig()->getLogEnclosure());
            }
            $io->close();
            $io->streamUnlock();
        } catch (Exception $e) {
            Mage::logException($e);
            return;
        }
    }

    /**
     * @param string $logType
     */
    public static function getColumnMapping($logType)
    {
        switch ($logType) {
            case 'exception':
                return array('timestamp', 'class', 'exception_code', 'exception_message', 'exception_trace');
            case 'system':
                return array('timestamp', 'class', 'level', 'message', 'details', 'origin');
            case 'profile':
                return array('timestamp', 'class', 'type', 'items', 'page', 'start', 'stop', 'duration', 'memory', 'message');
            default:
                return array('timestamp', 'class', 'level', 'message', 'details', 'origin');
        }
    }

    /**
     * @param int $levelId
     */
    public function getLogLevelHtml($levelId)
    {
        switch ($levelId) {
            case Zend_Log::EMERG:
                # Emergency: system is unusable
                $title       = 'Emergency';
                $description = 'system is unusable';
                $cssClass    = 'grid-severity-critical';
                break;
            case Zend_Log::ALERT:
                # Alert: action must be taken immediately
                $title       = 'Alert';
                $description = 'action must be taken immediately';
                $cssClass    = 'grid-severity-critical';
                break;
            case Zend_Log::CRIT:
                # Critical: critical conditions
                $title       = 'Critical';
                $description = 'critical conditions';
                $cssClass    = 'grid-severity-critical';
                break;
            case Zend_Log::ERR:
                # Error: error conditions
                $title       = 'Error';
                $description = 'error conditions';
                $cssClass    = 'grid-severity-major';
                break;
            case Zend_Log::WARN:
                # Warning: warning conditions
                $title       = 'Warning';
                $description = 'warning conditions';
                $cssClass    = 'grid-severity-minor';
                break;
            case Zend_Log::NOTICE:
                # Notice: normal but significant condition
                $title       = 'Notice';
                $description = 'normal but significant condition';
                $cssClass    = 'grid-severity-notice';
                break;
            case Zend_Log::INFO:
                # Informational: informational messages
                $title       = 'Informational';
                $description = 'informational messages';
                $cssClass    = 'grid-severity-notice';
                break;
            case Zend_Log::DEBUG:
                # Debug: debug messages
                $title       = 'Debug';
                $description = 'debug messages';
                $cssClass    = 'grid-severity-notice';
                break;
            default:
                $title       = 'None';
                $description = 'no specific log type';
                $cssClass = 'grid-severity-notice';
                break;
        }
        $html = '<span class="' . $cssClass . '" title="' . $description . '"><span>' . $title . '</span></span>';
        return $html;
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
