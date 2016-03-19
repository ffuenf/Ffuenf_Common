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

class Ffuenf_Common_Model_Observer
{
    /**
     * Invokes Ffuenf_Common logfiles rotating
     *
     * @return Ffuenf_Common_Model_Observer
     */
    public function rotateLogfiles()
    {
        try {
            Ffuenf_Common_Model_Logger::rotateLogfiles();
        } catch (Exception $e) {
            Ffuenf_Common_Model_Logger::logException($e);
            throw $e;
        }
        return $this;
    }
}
