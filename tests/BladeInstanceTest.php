<?php

namespace duncan3dc\Laravel;

use duncan3dc\Helpers\Env;

class BladeInstanceTest extends \PHPUnit_Framework_TestCase
{
    protected $blade;

    public function setUp()
    {
        Env::usePath("/tmp");
        $this->blade = new BladeInstance(__DIR__ . "/views");
    }

    public function testBasicMake()
    {
        $result = $this->blade->make("view1")->render();
        $this->assertSame(file_get_contents(__DIR__ . "/views/view1.blade.php"), $result);
    }


    public function testBasicRender()
    {
        $result = $this->blade->render("view1");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view1.blade.php"), $result);
    }


    public function testParametersMake()
    {
        $result = $this->blade->make("view2", ["title" => "Test Title"])->render();
        $this->assertSame(file_get_contents(__DIR__ . "/views/view2.html"), $result);
    }


    public function testParametersRender()
    {
        $result = $this->blade->render("view2", ["title" => "Test Title"]);
        $this->assertSame(file_get_contents(__DIR__ . "/views/view2.html"), $result);
    }


    public function testAltPath()
    {
        $this->blade->addPath(__DIR__ . "/views/alt");
        $result = $this->blade->render("view3");
        $this->assertSame(file_get_contents(__DIR__ . "/views/alt/view3.blade.php"), $result);
    }


    public function testNamespace()
    {
        $result = $this->blade->render("view4");
        $this->assertSame("duncan3dc\\Laravel", trim($result));
    }


    public function testUse()
    {
        $result = $this->blade->render("view5");
        $this->assertSame(Env::getMachineName(), trim($result));
    }


    public function testRawOutput()
    {
        $result = $this->blade->render("view6");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view6.html"), $result);
    }


    public function testEscapedOutput()
    {
        $result = $this->blade->render("view7");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view7.html"), $result);
    }


    public function testShare()
    {
        $this->blade->share("shareData", "shared");
        $result = $this->blade->render("view8");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view8.html"), $result);
    }


    public function testComposer()
    {
        $this->blade->composer("*", function($view) {
            $view->with("items", ["One", "Two", "Three"]);
        });
        $result = $this->blade->render("view9");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view9.html"), $result);
    }


    public function testCreator()
    {
        $this->blade->creator("*", function($view) {
            $view->with("items", ["One", "Two", "Three"]);
        });
        $result = $this->blade->render("view9");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view9.html"), $result);
    }


    public function testExists1()
    {
        $this->assertTrue($this->blade->exists("view1"));
    }


    public function testDoesntExist()
    {
        $this->assertFalse($this->blade->exists("no-such-view"));
    }
}
