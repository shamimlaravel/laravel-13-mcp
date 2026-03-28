<?php

namespace Shamimstack\Laravel13Mcp\Tools;

use Laravel\Mcp\Tools\McpTool;
use Shamimstack\Laravel13Mcp\Services\AttributeRefactorService;

class RefactorModelTool
{
    public static function make(AttributeRefactorService $service): McpTool
    {
        return McpTool::make(
            'refactor_model',
            'Refactor Laravel Model from property-based configuration to PHP 8 attributes (Laravel 13 style)',
            'Converts Laravel model properties like $table, $fillable, $casts, $hidden, etc. to PHP 8 attribute syntax. Preserves all business logic.',
            function (string $code) use ($service): array {
                $result = $service->refactorModel($code);
                
                return [
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $this->formatResult($result),
                        ],
                    ],
                ];
            },
            [
                'code' => [
                    'type' => 'string',
                    'description' => 'The Laravel Model code to refactor',
                ],
            ],
            ['code']
        );
    }

    private function formatResult(array $result): string
    {
        $output = "## Refactoring Result\n\n";
        
        if (empty($result['changes'])) {
            return $output . "No refactorable properties found. This model may already use attribute syntax.";
        }

        $output .= "### Changes Made:\n";
        foreach ($result['changes'] as $change) {
            $output .= "- {$change}\n";
        }

        $output .= "\n### Suggested Attributes:\n```php\n";
        $output .= $result['refactored'];
        $output .= "```\n";

        return $output;
    }
}
