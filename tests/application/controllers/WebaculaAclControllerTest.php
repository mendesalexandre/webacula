<?php
/**
 * Copyright 2010 Yuri Timofeev tim4dev@gmail.com
 * @author Yuri Timofeev <tim4dev@gmail.com>
 * @package webacula
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 */
class WebaculaAclControllerTest extends ControllerTestCase
{
    const ZF_pattern = '/Exception:|Warning:|Notice:|Call Stack/'; // Zend Framework



    /**
     * Test login form
     * @group login-form
     */
    public function testLoginForm()
    {
        print "\n".__METHOD__.' ';
        $this->getRequest()
             ->setParams(array(
                 'login' => 'user3',
                 'pwd'   => '1',
                 'rememberme' => '1') )
             ->setMethod('POST');
        $this->dispatch('auth/login');
        $this->assertController('auth');
        $this->assertAction('login');
        $this->assertRedirectTo('/index/index');
        $this->assertNotQueryContentRegex('table', self::ZF_pattern); // Zend Framework
    }



    /**
     * Test login form
     * @group login-form
     * @group login-wrong
     */
    public function testWrongLogin()
    {
        print "\n".__METHOD__.' ';
        $this->getRequest()
             ->setParams(array(
                 'login' => 'hacker',
                 'pwd'   => '123' ) )
             ->setMethod('POST');
        $this->dispatch('auth/login');
        $this->assertController('auth');
        $this->assertAction('login');
        $this->assertNotQueryContentRegex('table', self::ZF_pattern); // Zend Framework
        // zend form validate and error decorator output
        $this->assertQueryContentContains('div', 'Username or password is incorrect');
        // get captcha
        echo ".";
        $this->dispatch('auth/login');
        echo ".";
        $this->dispatch('auth/login');
        echo ".";
        $this->dispatch('auth/login');
        echo ".";
        $this->assertQueryContentContains('td', 'Type the characters');
    }



    /**
     * @group menu-access-denied
     */
    public function testMenuAccessDenied()
    {
        print "\n".__METHOD__.' ';
        $this->_user3Login();
        //$this->dispatch('index/index');
        $this->dispatch('log/view-log-id');
        //echo $this->response->outputBody();  // for debug !!!
        $this->assertNotQueryContentRegex('table', self::ZF_pattern); // Zend Framework
        $this->assertQueryContentRegex('div', '/We.*bacula.* : Access denied/');
        // TODO доступно только Menu Client, Menu Storage
        // см также LogControllerTest
    }

}