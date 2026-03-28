# MCP Server Usage Examples

## Basic Usage

### Analyzing Code

```php
use Shamimstack\Laravel13Mcp\Services\AttributeRefactorService;

$service = new AttributeRefactorService();

$code = '
class Post extends Model
{
    protected $table = "posts";
    protected $fillable = ["title", "body"];
    protected $casts = ["is_published" => "boolean"];
}
';

$result = $service->analyzeCode($code);

// Result:
// [
//     'type' => 'model',
//     'hasProperties' => true,
//     'suggestions' => [
//         'Convert $table to #[Table] attribute',
//         'Convert $fillable to #[Fillable] attribute',
//         'Convert $casts to #[Cast] attributes',
//     ]
// ]
```

### Refactoring a Model

```php
$result = $service->refactorModel($code);

// Result:
// [
//     'refactored' => "#[Table('posts')]
// #[Fillable(['title', 'body'])]
// #[Cast('is_published', 'boolean')]
// ",
//     'changes' => [
//         "Added #[Table] attribute for 'posts'",
//         "Added #[Fillable] attribute",
//         "Added 1 #[Cast] attributes"
//     ]
// ]
```

## MCP Tool Examples

### Using with Claude Desktop or opencode

Once the package is installed, you can use these prompts:

**Analyze a model:**
```
Analyze this Laravel code and suggest Laravel 13 attributes:
class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['name', 'price', 'description'];
    protected $casts = ['price' => 'decimal:2', 'is_active' => 'boolean'];
}
```

**Refactor a job:**
```
Refactor this Laravel job to use PHP 8 attributes:
class SendEmail implements ShouldQueue
{
    public $tries = 5;
    public $timeout = 120;
    public $queue = 'emails';
}
```

## Example Conversions

### Model Conversion

**Before:**
```php
class Post extends Model
{
    protected $table = 'blog_posts';
    protected $fillable = ['title', 'slug', 'body'];
    protected $guarded = ['id', 'is_admin'];
    protected $hidden = ['password'];
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];
    protected $with = ['author'];
    protected $appends = ['full_title'];
}
```

**After:**
```php
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Cast;
use Illuminate\Database\Eloquent\Attributes\With;
use Illuminate\Database\Eloquent\Attributes\Appends;

#[Table('blog_posts')]
#[Fillable(['title', 'slug', 'body'])]
#[Guarded(['id', 'is_admin'])]
#[Hidden(['password'])]
#[Cast('is_published', 'boolean')]
#[Cast('published_at', 'datetime')]
#[With(['author'])]
#[Appends(['full_title'])]
class Post extends Model
{
}
```

### Job Conversion

**Before:**
```php
class ProcessPayment implements ShouldQueue
{
    public $tries = 3;
    public $timeout = 120;
    public $backoff = [10, 30, 60];
    public $queue = 'payments';
    public $connection = 'redis';
}
```

**After:**
```php
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\Attributes\Backoff;
use Illuminate\Queue\Attributes\Queue;
use Illuminate\Queue\Attributes\Connection;

#[Tries(3)]
#[Timeout(120)]
#[Backoff([10, 30, 60])]
#[Queue('payments')]
#[Connection('redis')]
class ProcessPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
}
```

### Command Conversion

**Before:**
```php
class SendEmails extends Command
{
    protected $signature = 'mail:send {user} {--subject=Default}';
    protected $description = 'Send emails to a user';
    protected $hidden = false;
    protected $aliases = ['mail'];
}
```

**After:**
```php
use Illuminate\Console\Command;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Hidden;
use Illuminate\Console\Attributes\Aliases;

#[Signature('mail:send {user} {--subject=Default}')]
#[Description('Send emails to a user')]
#[Hidden(false)]
#[Aliases(['mail'])]
class SendEmails extends Command
{
}
```
