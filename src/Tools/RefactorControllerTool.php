<?php

namespace Shamimstack\Laravel13Mcp\Tools;

use Laravel\Mcp\Tools\McpTool;
use Shamimstack\Laravel13Mcp\Services\AttributeRefactorService;

class RefactorControllerTool
{
    public static function make(AttributeRefactorService $service): McpTool
    {
        return McpTool::make(
            'refactor_controller',
            'Refactor Laravel Controller middleware to PHP 8 attributes (Laravel 13 style)',
            'Converts Laravel controller middleware() calls in constructor to #[Middleware] and #[Authorize] attributes. Preserves all business logic.',
            function (string $code) use ($service): array {
                $result = $service->refactorController($code);
                
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
                    'description' => 'The Laravel Controller code to refactor',
                ],
            ],
            ['code']
        );
    }

    private function formatResult(array $result): string
    {
        $output = "## Refactoring Result\n\n";
        
        if (empty($result['changes'])) {
            return $output . "No middleware or authorization found. This controller may already use attribute syntax.";
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
