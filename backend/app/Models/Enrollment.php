<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    /** @use HasFactory<\Database\Factories\EnrollmentFactory> */
    use HasFactory;

    protected $fillable = [
        'course_session_id',
        'student_id',
        'status',
    ];

    public function courseSession(): BelongsTo
    {
        return $this->belongsTo(CourseSession::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
