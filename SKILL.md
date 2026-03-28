---
name: Laravel 13 MCP Server
description: MCP server for Laravel 13 PHP 8 attribute refactoring - provides AI-powered tools to convert Laravel code to attribute syntax
version: 1.0.0
author: shamimstack
tags: [laravel, php, laravel-13, mcp, model-context-protocol, attributes, refactor]
requires: PHP 8.3+ | Laravel 13+ | laravel/mcp ^0.6
---

# Laravel 13 MCP Server Skill

## Purpose

This skill enables AI agents to use the Laravel 13 MCP server package for automated code refactoring. The MCP server provides tools that convert Laravel code from property-based configuration to PHP 8 attribute syntax.

## When to Use

- User wants to refactor Laravel code using MCP tools
- User wants AI-assisted attribute conversion
- User is working with Laravel 13+ and PHP 8.3+

## MCP Tools Available

### 1. refactor_model
**Description:** Refactor Laravel Model from property-based configuration to PHP 8 attributes

**Parameters:**
- `code` (string, required): The Laravel Model code to refactor

**Converts:**
- `$table` → `#[Table('name')]`
- `$fillable` → `#[Fillable([...])]`
- `$guarded` → `#[Guarded([...])]`
- `$hidden` → `#[Hidden([...])]`
- `$visible` → `#[Visible([...])]`
- `$casts` → `#[Cast('column', 'type')]`
- `$with` → `#[With([...])]`
- `$appends` → `#[Appends([...])]`
- `$touches` → `#[Touches([...])]`
- `$connection` → `#[Connection('name')]`

### 2. refactor_job
**Description:** Refactor Laravel Queue Job to PHP 8 attributes

**Parameters:**
- `code` (string, required): The Laravel Job code to refactor

**Converts:**
- `$tries` → `#[Tries(n)]`
- `$timeout` → `#[Timeout(n)]`
- `$backoff` → `#[Backoff(n)]` or `#[Backoff([...])]`
- `$maxExceptions` → `#[MaxExceptions(n)]`
- `$queue` → `#[Queue('name')]`
- `$connection` → `#[Connection('name')]`
- `$uniqueFor` → `#[UniqueFor(n)]`
- `$failOnTimeout` → `#[FailOnTimeout]`

### 3. refactor_command
**Description:** Refactor Laravel Artisan Command to PHP 8 attributes

**Parameters:**
- `code` (string, required): The Laravel Command code to refactor

**Converts:**
- `$signature` → `#[Signature('...')]`
- `$description` → `#[Description('...')]`
- `$help` → `#[Help('...')]`
- `$hidden` → `#[Hidden]`
- `$aliases` → `#[Aliases([...])]`

### 4. refactor_controller
**Description:** Refactor Laravel Controller middleware to PHP 8 attributes

**Parameters:**
- `code` (string, required): The Laravel Controller code to refactor

**Converts:**
- `$this->middleware('auth')` → `#[Middleware('auth')]`
- `$this->authorizeResource()` → `#[Authorize]`

### 5. analyze_laravel_code
**Description:** Analyze Laravel code and provide refactoring suggestions

**Parameters:**
- `code` (string, required): The Laravel code to analyze

**Returns:**
- Component type (model, job, command, controller, etc.)
- Whether code has convertible properties
- Specific suggestions for Laravel 13 attributes

## Installation

```bash
composer require shamimstack/laravel-13-mcp
```

## Usage Example

```php
// Input code
$code = '
class Post extends Model
{
    protected $table = "posts";
    protected $fillable = ["title", "body"];
    protected $casts = ["is_published" => "boolean"];
}
';

// MCP tool returns suggested attributes
#[Table("posts")]
#[Fillable(["title", "body"])]
#[Cast("is_published", "boolean")]
```

## Important Notes

- The MCP tools analyze code and suggest attribute conversions
- Business logic in method bodies is preserved
- Some complex configurations may need manual review
- Supports Laravel 13.0+ and Laravel 13.2+ features (variadic Backoff, enum support)

## Skill Workflow

1. **Analyze** - Use analyze_laravel_code tool to identify component type
2. **Suggest** - Provide appropriate refactor tool based on component
3. **Refactor** - Convert properties to attributes
4. **Verify** - Ensure business logic remains intact

## Related Packages

- `shamimstack/php-laravel-13-skills` - AI skill definition for attribute refactoring
- `@shamimstack/laravel-13-skills` - NPM package for AI agents
