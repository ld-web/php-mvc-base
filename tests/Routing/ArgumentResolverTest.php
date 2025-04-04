<?php

use App\Routing\ArgumentResolver;
use App\Routing\Route;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

class ArgumentResolverTest extends TestCase
{
    private Route|Stub|null $route;

    protected function setUp(): void
    {
        $this->route = $this->createStub(Route::class);
        $this->route
            ->method('getRegex')
            ->willReturn("/^\/user\/(?P<slug>.+)$/");
    }

    public function testMatchRoute(): void
    {
        $resolver = new ArgumentResolver();
        $this->assertTrue($resolver->match("/user/my-awesome-login", $this->route));
    }

    public function testResolveUrlParams(): void
    {
        $resolver = new ArgumentResolver();
        $urlParams = $resolver->resolveUrlParams("/user/my-awesome-login", $this->route);

        $this->assertCount(1, $urlParams);
        $this->assertArrayHasKey("slug", $urlParams);
    }

    protected function tearDown(): void
    {
        $this->route = null;
    }
}
