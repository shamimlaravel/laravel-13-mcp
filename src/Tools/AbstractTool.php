<?php

namespace Shamimstack\Laravel13Mcp\Tools;

use Laravel\Mcp\Contracts\Tool;
use Shamimstack\Laravel13Mcp\Services\AttributeRefactorService;

abstract class AbstractTool implements Tool
{
    protected AttributeRefactorService $service;

    public function __construct(AttributeRefactorService $service)
    {
        $this->service = $service;
    }

    abstract public function name(): string;

    abstract public function description(): string;

    abstract public function execute(array $parameters): array;

    public function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => $this->getSchemaProperties(),
            'required' => $this->getSchemaRequired(),
        ];
    }

    protected function getSchemaProperties(): array
    {
        return [
            'code' => [
                'type' => 'string',
                'description' => 'The Laravel code to refactor',
            ],
        ];
    }

    protected function getSchemaRequired(): array
    {
        return ['code'];
    }
}
