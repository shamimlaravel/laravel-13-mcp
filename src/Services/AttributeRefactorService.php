<?php

namespace Shamimstack\Laravel13Mcp;

class AttributeRefactorService
{
    public function analyzeCode(string $code): array
    {
        $type = $this->detectLaravelComponent($code);
        
        return [
            'type' => $type,
            'hasProperties' => $this->hasProperties($code),
            'suggestions' => $this->generateSuggestions($code, $type),
        ];
    }

    public function refactorModel(string $code): array
    {
        $result = [
            'original' => $code,
            'refactored' => '',
            'changes' => [],
        ];

        if (preg_match('/protected\s+\$table\s*=\s*[\'"](.+?)[\'"]/', $code, $matches)) {
            $result['refactored'] .= "#[Table('{$matches[1]}')]\n";
            $result['changes'][] = "Added #[Table] attribute for '{$matches[1]}'";
        }

        if (preg_match('/protected\s+\$fillable\s*=\s*\[([^\]]+)\]/', $code, $matches)) {
            $fields = $this->parseArray($matches[1]);
            $result['refactored'] .= "#[Fillable([{$fields}])]\n";
            $result['changes'][] = "Added #[Fillable] attribute";
        }

        if (preg_match('/protected\s+\$guarded\s*=\s*\[([^\]]+)\]/', $code, $matches)) {
            $fields = $this->parseArray($matches[1]);
            $result['refactored'] .= "#[Guarded([{$fields}])]\n";
            $result['changes'][] = "Added #[Guarded] attribute";
        }

        if (preg_match('/protected\s+\$guarded\s*=\s*\[\]/', $code)) {
            $result['refactored'] .= "#[Unguarded]\n";
            $result['changes'][] = "Added #[Unguarded] attribute";
        }

        if (preg_match('/protected\s+\$hidden\s*=\s*\[([^\]]+)\]/', $code, $matches)) {
            $fields = $this->parseArray($matches[1]);
            $result['refactored'] .= "#[Hidden([{$fields}])]\n";
            $result['changes'][] = "Added #[Hidden] attribute";
        }

        if (preg_match('/protected\s+\$visible\s*=\s*\[([^\]]+)\]/', $code, $matches)) {
            $fields = $this->parseArray($matches[1]);
            $result['refactored'] .= "#[Visible([{$fields}])]\n";
            $result['changes'][] = "Added #[Visible] attribute";
        }

        if (preg_match('/protected\s+\$casts\s*=\s*\[([^\]]+)\]/s', $code, $matches)) {
            $casts = $this->parseCasts($matches[1]);
            foreach ($casts as $cast) {
                $result['refactored'] .= "#[Cast('{$cast['column']}', '{$cast['type']}')]\n";
            }
            $result['changes'][] = "Added " . count($casts) . " #[Cast] attributes";
        }

        if (preg_match('/protected\s+\$with\s*=\s*\[([^\]]+)\]/', $code, $matches)) {
            $relations = $this->parseArray($matches[1]);
            $result['refactored'] .= "#[With([{$relations}])]\n";
            $result['changes'][] = "Added #[With] attribute";
        }

        if (preg_match('/protected\s+\$appends\s*=\s*\[([^\]]+)\]/', $code, $matches)) {
            $appends = $this->parseArray($matches[1]);
            $result['refactored'] .= "#[Appends([{$appends}])]\n";
            $result['changes'][] = "Added #[Appends] attribute";
        }

        if (preg_match('/protected\s+\$touches\s*=\s*\[([^\]]+)\]/', $code, $matches)) {
            $touches = $this->parseArray($matches[1]);
            $result['refactored'] .= "#[Touches([{$touches}])]\n";
            $result['changes'][] = "Added #[Touches] attribute";
        }

        if (preg_match('/protected\s+\$connection\s*=\s*[\'"](.+?)[\'"]/', $code, $matches)) {
            $result['refactored'] .= "#[Connection('{$matches[1]}')]\n";
            $result['changes'][] = "Added #[Connection] attribute";
        }

        return $result;
    }

    public function refactorJob(string $code): array
    {
        $result = [
            'original' => $code,
            'refactored' => '',
            'changes' => [],
        ];

        if (preg_match('/public\s+\$tries\s*=\s*(\d+)/', $code, $matches)) {
            $result['refactored'] .= "#[Tries({$matches[1]})]\n";
            $result['changes'][] = "Added #[Tries({$matches[1]})] attribute";
        }

        if (preg_match('/public\s+\$timeout\s*=\s*(\d+)/', $code, $matches)) {
            $result['refactored'] .= "#[Timeout({$matches[1]})]\n";
            $result['changes'][] = "Added #[Timeout({$matches[1]})] attribute";
        }

        if (preg_match('/public\s+\$backoff\s*=\s*(\d+)/', $code, $matches)) {
            $result['refactored'] .= "#[Backoff({$matches[1]})]\n";
            $result['changes'][] = "Added #[Backoff({$matches[1]})] attribute";
        }

        if (preg_match('/public\s+\$backoff\s*=\s*\[([^\]]+)\]/', $code, $matches)) {
            $backoff = $this->parseArray($matches[1]);
            $result['refactored'] .= "#[Backoff([{$backoff}])]\n";
            $result['changes'][] = "Added #[Backoff] attribute with exponential backoff";
        }

        if (preg_match('/public\s+\$maxExceptions\s*=\s*(\d+)/', $code, $matches)) {
            $result['refactored'] .= "#[MaxExceptions({$matches[1]})]\n";
            $result['changes'][] = "Added #[MaxExceptions({$matches[1]})] attribute";
        }

        if (preg_match('/public\s+\$queue\s*=\s*[\'"](.+?)[\'"]/', $code, $matches)) {
            $result['refactored'] .= "#[Queue('{$matches[1]}')]\n";
            $result['changes'][] = "Added #[Queue('{$matches[1]}')] attribute";
        }

        if (preg_match('/public\s+\$connection\s*=\s*[\'"](.+?)[\'"]/', $code, $matches)) {
            $result['refactored'] .= "#[Connection('{$matches[1]}')]\n";
            $result['changes'][] = "Added #[Connection('{$matches[1]}')] attribute";
        }

        if (preg_match('/public\s+\$uniqueFor\s*=\s*(\d+)/', $code, $matches)) {
            $result['refactored'] .= "#[UniqueFor({$matches[1]})]\n";
            $result['changes'][] = "Added #[UniqueFor({$matches[1]})] attribute";
        }

        if (preg_match('/public\s+\$failOnTimeout\s*=\s*true/', $code)) {
            $result['refactored'] .= "#[FailOnTimeout]\n";
            $result['changes'][] = "Added #[FailOnTimeout] attribute";
        }

        return $result;
    }

    public function refactorCommand(string $code): array
    {
        $result = [
            'original' => $code,
            'refactored' => '',
            'changes' => [],
        ];

        if (preg_match('/protected\s+\$signature\s*=\s*[\'"](.+?)[\'"]/', $code, $matches)) {
            $signature = addslashes($matches[1]);
            $result['refactored'] .= "#[Signature('{$signature}')]\n";
            $result['changes'][] = "Added #[Signature] attribute";
        }

        if (preg_match('/protected\s+\$description\s*=\s*[\'"](.+?)[\'"]/', $code, $matches)) {
            $desc = addslashes($matches[1]);
            $result['refactored'] .= "#[Description('{$desc}')]\n";
            $result['changes'][] = "Added #[Description] attribute";
        }

        if (preg_match('/protected\s+\$help\s*=\s*[\'"](.+?)[\'"]/', $code, $matches)) {
            $help = addslashes($matches[1]);
            $result['refactored'] .= "#[Help('{$help}')]\n";
            $result['changes'][] = "Added #[Help] attribute";
        }

        if (preg_match('/protected\s+\$hidden\s*=\s*true/', $code)) {
            $result['refactored'] .= "#[Hidden]\n";
            $result['changes'][] = "Added #[Hidden] attribute";
        }

        if (preg_match('/protected\s+\$aliases\s*=\s*\[([^\]]+)\]/', $code, $matches)) {
            $aliases = $this->parseArray($matches[1]);
            $result['refactored'] .= "#[Aliases([{$aliases}])]\n";
            $result['changes'][] = "Added #[Aliases] attribute";
        }

        return $result;
    }

    public function refactorController(string $code): array
    {
        $result = [
            'original' => $code,
            'refactored' => '',
            'changes' => [],
        ];

        if (preg_match_all('/\$this->middleware\(([\'"]([^\'"]+)[\'"]|array\(\[([^\]]+)\]\))/', $code, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                if (isset($match[2]) && !empty($match[2])) {
                    $result['refactored'] .= "#[Middleware('{$match[2]}')]\n";
                    $result['changes'][] = "Added #[Middleware('{$match[2]}')] attribute";
                }
            }
        }

        if (preg_match('/\$this->authorizeResource\(([^\)]+)\)/', $code, $matches)) {
            $result['refactored'] .= "#[Authorize]\n";
            $result['changes'][] = "Added #[Authorize] attribute";
        }

        return $result;
    }

    protected function detectLaravelComponent(string $code): string
    {
        if (preg_match('/extends\s+Model/', $code)) {
            return 'model';
        }
        if (preg_match('/implements\s+ShouldQueue/', $code)) {
            return 'job';
        }
        if (preg_match('/extends\s+Command/', $code)) {
            return 'command';
        }
        if (preg_match('/extends\s+Controller/', $code)) {
            return 'controller';
        }
        if (preg_match('/extends\s+FormRequest/', $code)) {
            return 'form-request';
        }
        if (preg_match('/extends\s+Notification/', $code)) {
            return 'notification';
        }
        
        return 'unknown';
    }

    protected function hasProperties(string $code): bool
    {
        return (bool) preg_match('/protected\s+\$/i', $code) || (bool) preg_match('/public\s+\$/i', $code);
    }

    protected function generateSuggestions(string $code, string $type): array
    {
        $suggestions = [];
        
        switch ($type) {
            case 'model':
                if (preg_match('/protected\s+\$table\s*=/', $code)) {
                    $suggestions[] = 'Convert $table to #[Table] attribute';
                }
                if (preg_match('/protected\s+\$fillable\s*=/', $code)) {
                    $suggestions[] = 'Convert $fillable to #[Fillable] attribute';
                }
                if (preg_match('/protected\s+\$casts\s*=/', $code)) {
                    $suggestions[] = 'Convert $casts to #[Cast] attributes';
                }
                break;
                
            case 'job':
                if (preg_match('/public\s+\$tries\s*=/', $code)) {
                    $suggestions[] = 'Convert $tries to #[Tries] attribute';
                }
                if (preg_match('/public\s+\$timeout\s*=/', $code)) {
                    $suggestions[] = 'Convert $timeout to #[Timeout] attribute';
                }
                if (preg_match('/public\s+\$queue\s*=/', $code)) {
                    $suggestions[] = 'Convert $queue to #[Queue] attribute';
                }
                break;
                
            case 'command':
                if (preg_match('/protected\s+\$signature\s*=/', $code)) {
                    $suggestions[] = 'Convert $signature to #[Signature] attribute';
                }
                if (preg_match('/protected\s+\$description\s*=/', $code)) {
                    $suggestions[] = 'Convert $description to #[Description] attribute';
                }
                break;
                
            case 'controller':
                if (preg_match('/\$this->middleware/', $code)) {
                    $suggestions[] = 'Convert middleware() to #[Middleware] attribute';
                }
                break;
        }
        
        return $suggestions;
    }

    protected function parseArray(string $array): string
    {
        $items = array_map(function ($item) {
            $item = trim($item);
            if (preg_match('/[\'"](.+?)[\'"]/', $item, $m)) {
                return "'{$m[1]}'";
            }
            return $item;
        }, explode(',', $array));
        
        return implode(', ', $items);
    }

    protected function parseCasts(string $casts): array
    {
        $result = [];
        $items = explode(',', $casts);
        
        foreach ($items as $item) {
            if (preg_match('/[\'"]([^\'"]+)[\'"]\s*=>\s*[\'"]([^\'"]+)[\'"]/', $item, $matches)) {
                $result[] = ['column' => $matches[1], 'type' => $matches[2]];
            }
        }
        
        return $result;
    }
}
