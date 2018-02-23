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
 * @copyright  Copyright (c) 2018 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * @see Ffuenf_Common_Model_Config
 *
 * @loadSharedFixture config
 */

class Ffuenf_Common_Test_Model_Config extends EcomDev_PHPUnit_Test_Case_Config
{
    /**
     * @var Ffuenf_Common_Model_Config
     */
    protected $_model;

    public function setUp()
    {
        $this->_model = new Ffuenf_Common_Model_Config();
    }

    public function tearDown()
    {
        $this->_model = null;
    }

    /**
     * Tests whether extension model aliases are returning the correct class names
     *
     * @test
     */
    public function testModelAlias()
    {
        $this->assertModelAlias(
            'ffuenf_common/config',
            'Ffuenf_Common_Model_Config'
        );
    }

    /**
     * Tests whether extension logging is active.
     *
     * @test
     * @covers Ffuenf_Common_Model_Config::isLogActive
     */
    public function testIsLogActive()
    {
        $this->assertTrue(
            $this->_model->isLogActive(),
            'System logging is not active please check config'
        );
    }

    /**
     * Tests whether extension profile logging is active.
     *
     * @test
     * @covers Ffuenf_Common_Model_Config::isLogProfileActive
     */
    public function testIsLogProfileActive()
    {
        $this->assertTrue(
            $this->_model->isLogProfileActive(),
            'Profile logging is not active please check config'
        );
    }

    /**
     * Tests whether extension exception logging is active.
     *
     * @test
     * @covers Ffuenf_Common_Model_Config::isLogExceptionActive
     */
    public function testIsLogExceptionActive()
    {
        $this->assertTrue(
            $this->_model->isLogExceptionActive(),
            'Exception logging is not active please check config'
        );
    }
}
