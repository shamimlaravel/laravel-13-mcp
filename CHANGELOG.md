# Changelog

All notable changes to `laravel-13-mcp` will be documented in this file.

## [1.0.0] - 2026-03-29

### Added
- Initial release
- MCP server for Laravel 13 attribute refactoring
- 5 MCP tools:
  - refactor_model
  - refactor_job
  - refactor_command
  - refactor_controller
  - analyze_laravel_code
- AttributeRefactorService for code analysis and refactoring
- Support for all major Laravel components (Models, Jobs, Commands, Controllers)

### Features
- Automatic detection of Laravel component type
- Property to attribute conversion
- Business logic preservation
- Analysis and suggestions

### Requirements
- PHP 8.3+
- Laravel 13+
- laravel/mcp ^0.6
