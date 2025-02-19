<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'university_id',
        'department_id',
        'session',
        'year',
        'semester',
        'dob',
        'phone',
        'address',
        'city',
        'designation',
        'publication_count',
        'image',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Define the many-to-many relationship with the Course model
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    // Define the many-to-many relationship with the Department model
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // Define the one-to-many relationship with the Notice model
    public function notices(): HasMany
    {
        return $this->HasMany(Notice::class);
    }

    // Define the one-to-many relationship with the Role model
//    public function role(): BelongsToMany
//    {
//        return $this->belongsToMany(Role::class);
//    }

    public function canAccessPanel(Panel $panel): bool
    {
        return  $this->hasRole('admin');
    }
}
