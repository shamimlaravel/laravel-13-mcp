<?php

namespace Shamimstack\Laravel13Mcp;

use Illuminate\Support\ServiceProvider;
use Shamimstack\Laravel13Mcp\Tools\RefactorModelTool;
use Shamimstack\Laravel13Mcp\Tools\RefactorJobTool;
use Shamimstack\Laravel13Mcp\Tools\RefactorCommandTool;
use Shamimstack\Laravel13Mcp\Tools\RefactorControllerTool;
use Shamimstack\Laravel13Mcp\Tools\AnalyzeCodeTool;

class Laravel13McpServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AttributeRefactorService::class, function ($app) {
            return new AttributeRefactorService();
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole() || $this->app->bound('mcp')) {
            $this->registerMcpTools();
        }
    }

    protected function registerMcpTools(): void
    {
        $mcp = $this->app->make('mcp');
        
        $refactorService = $this->app->make(AttributeRefactorService::class);

        $mcp->tools->register([
            RefactorModelTool::make($refactorService),
            RefactorJobTool::make($refactorService),
            RefactorCommandTool::make($refactorService),
            RefactorControllerTool::make($refactorService),
            AnalyzeCodeTool::make($refactorService),
        ]);
    }
}
