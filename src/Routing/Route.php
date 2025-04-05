<?php

namespace App\Routing;

class Route extends AbstractRoute
{
    private array $urlParams = [];
    private string $controller;
    private string $method;

    public function __construct(
        string $path,
        string $controller,
        string $method,
        array $httpMethods = ["GET"],
        string $name = "default",
    ) {
        parent::__construct($path, $httpMethods, $name);
        $this->controller = $controller;
        $this->method = $method;
    }

    public function getRegex(): string
    {
        // URL parameters into capturing regex parts
        $routeRegex = preg_replace("/\{(\w+)\}/", '(?P<${1}>.+)', $this->getPath());
        // Slashes escaping, add regex delimiters
        $routeRegex = "/^" . str_replace("/", "\/", $routeRegex) . "$/";

        return $routeRegex;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getUrlParams(): array
    {
        return $this->urlParams;
    }

    /**
     * @codeCoverageIgnore
     */
    public function setUrlParams(array $urlParams): self
    {
        $this->urlParams = $urlParams;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @codeCoverageIgnore
     */
    public function setController(string $controller): self
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @codeCoverageIgnore
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }
}
