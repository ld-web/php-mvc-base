<?php

use App\Routing\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    #[DataProvider('routeRegexProvider')]
    public function testRouteRegex(string $path, string $expected): void
    {
        $route = new Route($path, "Test", "test");

        $this->assertEquals($expected, $route->getRegex());
    }

    public static function routeRegexProvider(): Generator
    {
        yield "No params" => ["/about", "/^\/about$/"];
        yield "Slug" => ["/user/{slug}", "/^\/user\/(?P<slug>.+)$/"];
        yield "ID, multiple URL parts" => ["/user/edit/{id}", "/^\/user\/edit\/(?P<id>.+)$/"];
        yield "Multiple params" => ["/user/edit/{id}/{param}", "/^\/user\/edit\/(?P<id>.+)\/(?P<param>.+)$/"];
        yield "URL Param in between" => ["/user/{slug}/profile", "/^\/user\/(?P<slug>.+)\/profile$/"];
    }
}
