# Laravel 13 MCP

MCP (Model Context Protocol) server for Laravel 13 PHP 8 attribute refactoring - provides AI-powered tools to convert Laravel code to attribute syntax.

[![PHP Version](https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat&logo=php)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![License](https://img.shields.io/badge/License-MIT-4DC143?style=flat)](LICENSE)

## Description

This package provides MCP (Model Context Protocol) tools that enable AI agents to automatically refactor Laravel code from property-based configuration to modern PHP 8 attribute syntax (Laravel 13 style).

## Features

- **RefactorModel** - Convert Eloquent model properties to attributes
- **RefactorJob** - Convert queue job properties to attributes
- **RefactorCommand** - Convert artisan command properties to attributes
- **RefactorController** - Convert controller middleware to attributes
- **AnalyzeCode** - Analyze code and suggest improvements

## Requirements

- PHP 8.3+
- Laravel 13+
- laravel/mcp ^0.6

## Installation

```bash
composer require shamimstack/laravel-13-mcp
```

The service provider will be registered automatically.

## Usage

### MCP Tools

Once installed, the following MCP tools become available:

1. **refactor_model** - Refactor Laravel Model to use PHP 8 attributes
2. **refactor_job** - Refactor Laravel Job to use PHP 8 attributes
3. **refactor_command** - Refactor Laravel Command to use PHP 8 attributes
4. **refactor_controller** - Refactor Laravel Controller middleware to attributes
5. **analyze_laravel_code** - Analyze code and provide suggestions

### Example Usage

```php
// Pass code to the refactor tools
$code = '
class Post extends Model
{
    protected $table = "posts";
    protected $fillable = ["title", "body"];
    protected $casts = ["is_published" => "boolean"];
}
';

// The MCP tool will return:
#[Table("posts")]
#[Fillable(["title", "body"])]
#[Cast("is_published", "boolean")]
```

## Supported Conversions

### Models
- `$table` → `#[Table]`
- `$fillable` → `#[Fillable]`
- `$guarded` → `#[Guarded]`
- `$hidden` → `#[Hidden]`
- `$visible` → `#[Visible]`
- `$casts` → `#[Cast]`
- `$with` → `#[With]`
- `$appends` → `#[Appends]`
- `$touches` → `#[Touches]`
- `$connection` → `#[Connection]`

### Jobs
- `$tries` → `#[Tries]`
- `$timeout` → `#[Timeout]`
- `$backoff` → `#[Backoff]`
- `$maxExceptions` → `#[MaxExceptions]`
- `$queue` → `#[Queue]`
- `$connection` → `#[Connection]`
- `$uniqueFor` → `#[UniqueFor]`
- `$failOnTimeout` → `#[FailOnTimeout]`

### Commands
- `$signature` → `#[Signature]`
- `$description` → `#[Description]`
- `$help` → `#[Help]`
- `$hidden` → `#[Hidden]`
- `$aliases` → `#[Aliases]`

### Controllers
- `$this->middleware()` → `#[Middleware]`
- `$this->authorizeResource()` → `#[Authorize]`

## Configuration

No configuration required. The package works out of the box with Laravel MCP.

## License

MIT License - see [LICENSE](LICENSE) file for details.

## Author

- **Shamim** - [shamimstack@gmail.com](mailto:shamimstack@gmail.com)

## Resources

- [Laravel MCP Documentation](https://laravel.com/docs/mcp)
- [Laravel 13 Attributes](https://laraveldaily.com/post/php-attributes-in-laravel-13-the-ultimate-guide-36-new-attributes)
