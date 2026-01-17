---
description: Database migration conventions and best practices
---

# Database Migration Conventions

## Foreign Key Delete Behavior

**IMPORTANT**: We always prefer `SET NULL` over `CASCADE` for foreign key deletion constraints.

### Why?
- Data preservation: Keeps historical records even when referenced entities are deleted
- Audit trail: Maintains transaction history, logs, and other important data
- Safer operations: Prevents accidental mass data deletion

### How to implement:

1. Make the foreign key column **nullable**:
```php
$table->foreignId('user_id')->nullable()
```

2. Use `nullOnDelete()` instead of `cascadeOnDelete()`:
```php
$table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
```

### Full Example:
```php
Schema::create('wallets', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->string('type');
    $table->bigInteger('balance')->default(0);
    $table->timestamps();
});
```

### When CASCADE might still be appropriate:
- Pivot/junction tables (e.g., `users_challenges`) where the record has no meaning without both sides
- Temporary or session-based data that should be cleaned up
- **Always confirm with the team before using CASCADE**
