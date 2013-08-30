<?php

use Sinergia\Package\App;

class AppTest extends PHPUnit_Framework_Testcase
{
    public function testApp()
    {
        $app = new App();
        $msg = $app->run();
        $this->assertEquals('Hello App', $msg);
    }

    public function testFoo()
    {
        $this->markTestIncomplete("can't check if header was sent");
    }
}
