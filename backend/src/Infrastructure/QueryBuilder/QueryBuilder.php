<?php declare(strict_types=1);

namespace src\Infrastructure\QueryBuilder;

use src\Infrastructure\DatabaseConnector;

class QueryBuilder {
    public function __construct(DatabaseConnector $dbConnector) {
        $dbConnector->getConnection();
    }

    public function having(string $condition): string {
        return 'HAVING ' . $condition . ' ';
    }

    public function groupBy(string $columns): string {
        return 'GROUP BY ' . $columns . ' ';
    }

    public function orderByAscendent(string $columns): string {
        return 'ORDER BY ' . $columns . ' ASC ';
    }

    public function orderByDecrescent(string $columns): string {
        return 'ORDER BY ' . $columns . ' DESC ';
    }

    public function limit(int $limit, int $offset = 0): string {
        return 'LIMIT ' . $offset . ', ' . $limit . ' ';
    }

    public function where(string $condition): string {
        return 'WHERE ' . $condition . ' ';
    }

    public function join(string $table, string $condition, string $type = 'INNER'): string {
        return $type . ' JOIN ' . $table . ' ON ' . $condition . ' ';
    }

    public function select(string $columns, string $table): string {
        return 'SELECT ' . $columns . ' FROM ' . $table . ' ';
    }

    public function insertPrepared(string $table, array $columns): string {
        $columnList = implode(', ', $columns);
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));

        return 'INSERT INTO ' . $table . ' (' . $columnList . ') VALUES (' . $placeholders . ')';
    }

    public function updatePrepared(string $table, array $columns, string $condition): string {
        $setClause = implode(', ', array_map(static fn (string $column): string => $column . ' = ?', $columns));

        return 'UPDATE ' . $table . ' SET ' . $setClause . ' WHERE ' . $condition;
    }

    public function delete(string $table, string $condition): string {
        return 'DELETE FROM ' . $table . ' WHERE ' . $condition;
    }
}