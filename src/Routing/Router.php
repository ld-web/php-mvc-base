<?php

namespace App\Routing;

use App\Routing\Attribute\Route as RouteAttribute;
use App\Utils\Filesystem;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Router
{
    private const CONTROLLERS_BASE_NAMESPACE = "App\\Controller\\";
    private const CONTROLLERS_BASE_DIR = __DIR__ . "/../Controller";

    public function __construct(
        private ContainerInterface $container,
        private ArgumentResolver $argumentResolver
    ) {
    }

    /** @var Route[] */
    private array $routes = [];

    public function addRoute(Route $route): self
    {
        $this->routes[] = $route;

        return $this;
    }

    public function getRoute(string $uri, string $httpMethod): ?Route
    {
        foreach ($this->routes as $route) {
            if ($this->argumentResolver->match($uri, $route) && in_array($httpMethod, $route->getHttpMethods())) {
                $params = $this->argumentResolver->resolveUrlParams($uri, $route);
                $route->setUrlParams($params);

                return $route;
            }
        }

        return null;
    }

    /**
     * @param string $requestUri
     * @param string $httpMethod
     * @return void
     * @throws RouteNotFoundException
     */
    public function execute(string $requestUri, string $httpMethod)
    {
        $route = $this->getRoute($requestUri, $httpMethod);

        if ($route === null) {
            throw new RouteNotFoundException($requestUri, $httpMethod);
        }

        $controllerClass = $route->getController();
        $method = $route->getMethod();

        $constructorParams = $this->getServicesFromSignature($controllerClass . '::__construct');
        $controllerInstance = new $controllerClass(...$constructorParams);

        $serviceParams = $this->getServicesFromSignature($controllerClass . '::' . $method);

        $params = array_merge($serviceParams, $route->getUrlParams());

        echo call_user_func_array([$controllerInstance, $method], $params);
    }

    /**
     * Get an array containing services instances guessed from method signature
     *
     * @param string $method Format : FQCN::method
     * @return array The services to inject
     */
    private function getServicesFromSignature(string $method): array
    {
        $params = [];

        try {
            $methodInfos = ReflectionMethod::createFromMethodName($method);
        } catch (ReflectionException $e) {
            return [];
        }
        $methodParams = $methodInfos->getParameters();

        foreach ($methodParams as $methodParam) {
            $paramName = $methodParam->getName();
            $paramType = $methodParam->getType();
            $paramTypeName = $paramType->getName();
            if ($this->container->has($paramTypeName)) {
                $params[$paramName] = $this->container->get($paramTypeName);
            }
        }

        return $params;
    }

    public function registerRoutes(): void
    {
        $fqcns = Filesystem::getFqcns(
            self::CONTROLLERS_BASE_DIR,
            self::CONTROLLERS_BASE_NAMESPACE
        );

        foreach ($fqcns as $fqcn) {
            $classInfos = new ReflectionClass($fqcn);

            if ($classInfos->isAbstract()) {
                continue;
            }

            $methods = $classInfos->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                if ($method->isConstructor()) {
                    continue;
                }

                $attributes = $method->getAttributes(RouteAttribute::class);

                if (!empty($attributes)) {
                    $routeAttribute = $attributes[0];
                    /** @var RouteAttribute */
                    $routeAttribute = $routeAttribute->newInstance();
                    $this->addRoute(new Route(
                        $routeAttribute->getPath(),
                        $fqcn,
                        $method->getName(),
                        $routeAttribute->getHttpMethods(),
                        $routeAttribute->getName()
                    ));
                }
            }
        }
    }
}