<?php

namespace App\Repository;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use PDO;

/**
 * @template T of object
 */
abstract class ModelRepository
{
    protected Serializer $serializer;

    public function __construct(
        protected PDO $pdo,
        protected string $modelClass,
        protected string $table
    ) {
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * @phpstan-return list<T> The entities.
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM ' . $this->table);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->serializer->deserialize(
            json_encode($results),
            'array<int, ' . $this->modelClass . '>',
            'json'
        );
    }

    /**
     * @phpstan-return T|null
     */
    public function find(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE id=:id');
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            return null;
        }

        return $this->serializer->deserialize(
            json_encode($result),
            $this->modelClass,
            'json'
        );
    }
}