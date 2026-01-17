---
description: Create a new CRUD resource following clean code best practices
---

# CRUD Resource Creation Workflow

This workflow creates a complete CRUD resource with all necessary files following the established patterns in this project.

## Prerequisites
- Table schema defined in a migration file or temp file
- Understanding of the resource's relationships and business rules

## File Structure
For a resource named `{Resource}` (e.g., `Wallet`, `Challenge`):
```
app/
├── Enums/
│   └── {Resource}Type.php          # If the resource has type-safe enums
├── Http/
│   ├── Controllers/Api/
│   │   └── {Resource}Controller.php
│   ├── Requests/{Resource}/
│   │   ├── Create{Resource}Request.php
│   │   └── Update{Resource}Request.php
│   └── Resources/
│       └── {Resource}Resource.php
├── Models/
│   └── {Resource}.php
├── Policies/
│   └── {Resource}Policy.php
└── Services/
    └── {Resource}Service.php
database/
├── factories/
│   └── {Resource}Factory.php
└── migrations/
    └── YYYY_MM_DD_HHMMSS_create_{resources}_table.php
```

---

## Step 1: Create Migration
// turbo
```bash
php artisan make:migration create_{resources}_table
```

Edit the migration file with the table schema. Follow these conventions:
- Use `$table->id()` for primary key
- Use `$table->foreignId('user_id')->constrained()->cascadeOnDelete()` for user relationships
- Use `$table->string('type')` for enum fields (cast in model)
- Use `$table->timestamps()` for created_at/updated_at
- Add unique constraints as needed

---

## Step 2: Create Enum (if needed)
Create `app/Enums/{Resource}Type.php`:
```php
<?php

namespace App\Enums;

enum {Resource}Type: string
{
    case VALUE_ONE = 'value_one';
    case VALUE_TWO = 'value_two';

    /**
     * Get all type values.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
```

---

## Step 3: Create Model
// turbo
```bash
php artisan make:model {Resource}
```

Edit `app/Models/{Resource}.php`:
```php
<?php

namespace App\Models;

use App\Enums\{Resource}Type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class {Resource} extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'field_one',
        'field_two',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => {Resource}Type::class,
            // Add other casts as needed
        ];
    }

    /**
     * Get the user that owns the resource.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

---

## Step 4: Create API Resource
Create `app/Http/Resources/{Resource}Resource.php`:
```php
<?php

namespace App\Http\Resources;

use App\Models\{Resource};
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin {Resource}
 */
class {Resource}Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // Map all public fields
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
```

---

## Step 5: Create Policy
// turbo
```bash
php artisan make:policy {Resource}Policy --model={Resource}
```

Edit `app/Policies/{Resource}Policy.php`:
```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\{Resource};

class {Resource}Policy
{
    /**
     * Determine whether the user can view any resources.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the resource.
     */
    public function view(User $user, {Resource} $resource): bool
    {
        return $user->id === $resource->user_id;
    }

    /**
     * Determine whether the user can create resources.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the resource.
     */
    public function update(User $user, {Resource} $resource): bool
    {
        return $user->id === $resource->user_id;
    }

    /**
     * Determine whether the user can delete the resource.
     */
    public function delete(User $user, {Resource} $resource): bool
    {
        return $user->id === $resource->user_id;
    }
}
```

---

## Step 6: Create Service
Create `app/Services/{Resource}Service.php`:
```php
<?php

namespace App\Services;

use App\Models\{Resource};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class {Resource}Service
{
    /**
     * List all resources for the authenticated user.
     *
     * @return Collection<int, {Resource}>
     */
    public function index(): Collection
    {
        return {Resource}::where('user_id', Auth::id())->get();
    }

    /**
     * Get a specific resource.
     */
    public function show({Resource} $resource): {Resource}
    {
        return $resource;
    }

    /**
     * Create a new resource.
     *
     * @param array<string, mixed> $data
     */
    public function store(array $data): {Resource}
    {
        return {Resource}::create([
            'user_id' => Auth::id(),
            ...$data,
        ]);
    }

    /**
     * Update an existing resource.
     *
     * @param array<string, mixed> $data
     */
    public function update(array $data, {Resource} $resource): {Resource}
    {
        $resource->update($data);
        return $resource->fresh();
    }

    /**
     * Delete a resource.
     */
    public function destroy({Resource} $resource): bool
    {
        return $resource->delete();
    }
}
```

---

## Step 7: Create Form Requests
Create directory and files:
```bash
mkdir -p app/Http/Requests/{Resource}
```

### Create{Resource}Request.php
```php
<?php

namespace App\Http\Requests\{Resource};

use Illuminate\Foundation\Http\FormRequest;

class Create{Resource}Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'field_one' => 'required|string',
            // Add validation rules
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Custom error messages
        ];
    }
}
```

### Update{Resource}Request.php
```php
<?php

namespace App\Http\Requests\{Resource};

use Illuminate\Foundation\Http\FormRequest;

class Update{Resource}Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'field_one' => 'sometimes|string',
            // Use 'sometimes' for optional updates
        ];
    }
}
```

---

## Step 8: Create Controller
Create `app/Http/Controllers/Api/{Resource}Controller.php`:
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{Resource}\Create{Resource}Request;
use App\Http\Requests\{Resource}\Update{Resource}Request;
use App\Http\Resources\{Resource}Resource;
use App\Models\{Resource};
use App\Services\{Resource}Service;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group {Resources}
 *
 * APIs for managing {resources}.
 */
class {Resource}Controller extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected {Resource}Service $service
    ) {}

    /**
     * List all {resources}
     *
     * Get all {resources} for the authenticated user.
     *
     * @apiResourceCollection App\Http\Resources\{Resource}Resource
     * @apiResourceModel App\Models\{Resource}
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', {Resource}::class);
        return {Resource}Resource::collection($this->service->index());
    }

    /**
     * Get a {resource}
     *
     * Get a specific {resource} by ID.
     *
     * @apiResource App\Http\Resources\{Resource}Resource
     * @apiResourceModel App\Models\{Resource}
     * @urlParam {resource} int required The {resource} ID. Example: 1
     */
    public function show({Resource} ${resource}): {Resource}Resource
    {
        $this->authorize('view', ${resource});
        return new {Resource}Resource($this->service->show(${resource}));
    }

    /**
     * Create a {resource}
     *
     * Create a new {resource} for the authenticated user.
     *
     * @apiResource App\Http\Resources\{Resource}Resource
     * @apiResourceModel App\Models\{Resource}
     * @response 201 scenario="{Resource} created" {"data":{...},"message":"{Resource} created successfully"}
     */
    public function store(Create{Resource}Request $request): JsonResponse
    {
        $this->authorize('create', {Resource}::class);
        ${resource} = $this->service->store($request->validated());

        return (new {Resource}Resource(${resource}))
            ->additional(['message' => '{Resource} created successfully'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update a {resource}
     *
     * Update an existing {resource}.
     *
     * @apiResource App\Http\Resources\{Resource}Resource
     * @apiResourceModel App\Models\{Resource}
     * @urlParam {resource} int required The {resource} ID. Example: 1
     */
    public function update(Update{Resource}Request $request, {Resource} ${resource}): {Resource}Resource
    {
        $this->authorize('update', ${resource});
        $updated = $this->service->update($request->validated(), ${resource});

        return (new {Resource}Resource($updated))
            ->additional(['message' => '{Resource} updated successfully']);
    }

    /**
     * Delete a {resource}
     *
     * Delete a {resource} by ID.
     *
     * @response 200 {"message": "{Resource} deleted successfully"}
     * @urlParam {resource} int required The {resource} ID. Example: 1
     */
    public function destroy({Resource} ${resource}): JsonResponse
    {
        $this->authorize('delete', ${resource});
        $this->service->destroy(${resource});

        return response()->json([
            'message' => '{Resource} deleted successfully',
        ]);
    }
}
```

---

## Step 9: Create Factory
Create `database/factories/{Resource}Factory.php`:
```php
<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\{Resource};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\{Resource}>
 */
class {Resource}Factory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = {Resource}::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            // Add fake data for each field
        ];
    }

    // Add state methods for common variations
}
```

---

## Step 10: Register Routes
Add to `routes/api.php` inside the `auth:api` middleware group:
```php
Route::apiResource('{resources}', {Resource}Controller::class);
```

---

## Step 11: Run Migration
// turbo
```bash
php artisan migrate
```

---

## Step 12: Verify
// turbo
```bash
php artisan route:list --path={resources}
```

Check that all 5 routes are registered:
- `GET /api/{resources}` - index
- `POST /api/{resources}` - store
- `GET /api/{resources}/{id}` - show
- `PUT/PATCH /api/{resources}/{id}` - update
- `DELETE /api/{resources}/{id}` - destroy

---

## Best Practices Summary

1. **Thin Controllers**: Controllers only handle HTTP concerns (authorization, request/response transformation)
2. **Service Layer**: All business logic lives in the Service class
3. **Form Requests**: Validation is separated into dedicated request classes
4. **API Resources**: Response transformation is handled by Resource classes
5. **Policies**: Authorization logic is centralized in Policy classes
6. **Enums**: Use PHP 8.1+ enums for type-safe values
7. **Factories**: Include states for common test scenarios
8. **Scribe Annotations**: Add documentation annotations for API docs generation
9. **PHPDoc Types**: Use `@var list<string>`, `@return array<string, mixed>` etc.
10. **Relationships**: Define Eloquent relationships with proper return types
