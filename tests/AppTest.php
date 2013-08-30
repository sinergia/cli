<?php

use Sinergia\Cli\App;

class AppTest extends PHPUnit_Framework_Testcase
{
    public function testApp()
    {
        $app = new App();
    }

    public function testFoo()
    {
        $this->markTestIncomplete("can't check if header was sent");
    }
}
