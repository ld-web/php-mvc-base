<?php

namespace App\DependencyInjection;

use App\Utils\Filesystem;
use LogicException;
use PDO;
use Psr\Container\ContainerInterface;
use RuntimeException;

class Container implements ContainerInterface
{
    private array $services = [];

    public function set(string $id, $instance): self
    {
        if ($this->has($id)) {
            throw new LogicException("Le service $id est déjà enregistré");
        }
        $this->services[$id] = $instance;
        return $this;
    }

    /**
     * Gets a service from given service ID
     *
     * @param string $id
     * @return mixed
     * @throws ServiceNotFoundException
     */
    public function get(string $id)
    {
        if (!$this->has($id)) {
            throw new ServiceNotFoundException($id);
        }
        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    /**
     * Explores and registers repositories automatically
     */
    public function registerRepositories(): void
    {
        if (!$this->has(PDO::class)) {
            throw new RuntimeException(
                sprintf("Define service for %s class before registering repositories", PDO::class)
            );
        }

        $pdo = $this->get(PDO::class);

        $fqcns = array_filter(Filesystem::getFqcns(
            __DIR__ . "/../Repository",
            "App\\Repository\\"
        ), fn (string $fqcn) => !str_contains($fqcn, "ModelRepository"));

        foreach ($fqcns as $fqcn) {
            $repository = new $fqcn($pdo);
            $this->set($fqcn, $repository);
        }
    }
}
