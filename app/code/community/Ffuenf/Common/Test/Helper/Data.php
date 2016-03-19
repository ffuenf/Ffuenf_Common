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

/**
 * @see Ffuenf_Common_Helper_Data
 *
 * @loadSharedFixture shared
 */

class Ffuenf_Common_Test_Helper_Data extends EcomDev_PHPUnit_Test_Case
{
    /**
     * @var Ffuenf_Common_Helper_Data
     */
    protected $_helper;

    public function setUp()
    {
        $this->_helper = new Ffuenf_Common_Helper_Data();
    }

    public function tearDown()
    {
        $this->_helper = null;
    }

    /**
     * Tests whether extension is active.
     *
     * @test
     * @covers Ffuenf_Common_Helper_Data::isExtensionActive
     */
    public function testIsExtensionActive()
    {
        $this->assertTrue(
            $this->_helper->isExtensionActive(),
            'Extension is not active please check config'
        );
    }

    /**
     * Tests whether extension logging is active.
     *
     * @test
     * @covers Ffuenf_Common_Helper_Data::isLoggingActive
     */
    public function testIsLoggingActive()
    {
        $this->assertTrue(
            $this->_helper->isLoggingActive(),
            'Logging is not active please check config'
        );
    }

    /**
     * Tests whether extension profile logging is active.
     *
     * @test
     * @covers Ffuenf_Common_Helper_Data::isProfileLoggingActive
     */
    public function testIsProfileLoggingActive()
    {
        $this->assertTrue(
            $this->_helper->isProfileLoggingActive(),
            'Profile logging is not active please check config'
        );
    }

    /**
     * Tests whether extension exception logging is active.
     *
     * @test
     * @covers Ffuenf_Common_Helper_Data::isExceptionLoggingActive
     */
    public function testIsExceptionLoggingActive()
    {
        $this->assertTrue(
            $this->_helper->isExceptionLoggingActive(),
            'Exception logging is not active please check config'
        );
    }
}
