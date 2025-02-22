<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'credit',
        'year',
        'semester',
        'department_id',
    ];

    // Define the many-to-many relationship with the User model
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    public function department(): BelongsTo
    {
        return $this->BelongsTo(Department::class);
    }
}
