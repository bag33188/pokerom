<?php

namespace Utils\Modules;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class QueryObject implements Arrayable
{
    public Expression $query;
    public array $bindings;

    /**
     * @param string $query Query as a normal string, gets put through `DB::raw`
     * @param array $bindings can be a normal array or associative array depending on type of PDO binding used
     */
    public function __construct(string $query, array $bindings = [])
    {
        $this->bindings = $bindings;
        $this->query = DB::raw($query);
    }

    /**
     * Call {@see getValues `getValues`} accessor method on invocation
     *
     * @return array
     */
    public function __invoke(): array
    {
        return $this->getValues();
    }

    /**
     * Converts to associative array
     *
     * First key/value pair is the query,<br/>
     * Second key/value pair are any parameters for the query
     *
     * @return array
     */
    #[ArrayShape(['query' => "\Illuminate\Database\Query\Expression", 'bindings' => "array"])]
    public function toArray(): array
    {
        return ['query' => $this->query, 'bindings' => $this->bindings];
    }

    /**
     * Returns extracted values of associative array
     *
     * @return array
     */
    public function getValues(): array
    {
        return array_values($this->toArray());
    }
}
