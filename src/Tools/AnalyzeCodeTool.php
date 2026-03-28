<?php

namespace Shamimstack\Laravel13Mcp\Tools;

use Laravel\Mcp\Tools\McpTool;
use Shamimstack\Laravel13Mcp\Services\AttributeRefactorService;

class AnalyzeCodeTool
{
    public static function make(AttributeRefactorService $service): McpTool
    {
        return McpTool::make(
            'analyze_laravel_code',
            'Analyze Laravel code and suggest Laravel 13 PHP 8 attribute conversions',
            'Analyzes Laravel code and identifies: 1) What type of Laravel component it is (Model, Job, Command, Controller), 2) What properties can be converted to attributes, 3) Specific suggestions for refactoring. Does not modify code, only provides analysis.',
            function (string $code) use ($service): array {
                $result = $service->analyzeCode($code);
                
                return [
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $this->formatAnalysis($result),
                        ],
                    ],
                ];
            },
            [
                'code' => [
                    'type' => 'string',
                    'description' => 'The Laravel code to analyze',
                ],
            ],
            ['code']
        );
    }

    private function formatAnalysis(array $result): string
    {
        $output = "## Code Analysis\n\n";
        
        $output .= "### Component Type: **" . strtoupper($result['type']) . "**\n\n";
        
        $output .= "### Has Convertible Properties: " . 
            ($result['hasProperties'] ? '✅ Yes' : '❌ No') . "\n\n";
        
        if (!empty($result['suggestions'])) {
            $output .= "### Suggestions for Laravel 13 Attributes:\n";
            foreach ($result['suggestions'] as $suggestion) {
                $output .= "- {$suggestion}\n";
            }
        } else {
            if ($result['type'] === 'unknown') {
                $output .= "⚠️ Unable to determine Laravel component type.\n";
            } else {
                $output .= "✅ Code appears to already use attribute syntax!\n";
            }
        }

        return $output;
    }
}
