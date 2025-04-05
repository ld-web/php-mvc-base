<?php

namespace Tests\DependencyInjection;

use App\DependencyInjection\Container;
use App\DependencyInjection\ServiceNotFoundException;
use LogicException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testSetAndGet(): void
    {
        $container = new Container();
        $container->set('test', 'test');
        $this->assertEquals('test', $container->get('test'));
    }

    public function testHas(): void
    {
        $container = new Container();
        $container->set('test', 'test');
        $this->assertTrue($container->has('test'));
    }

    public function testHasNot(): void
    {
        $container = new Container();
        $this->expectException(ServiceNotFoundException::class);
        $container->get('test');
    }

    public function testAlreadyHas(): void
    {
        $container = new Container();
        $container->set('test', 'test');
        $this->expectException(LogicException::class);
        $container->set('test', 'test');
    }
}
