<?php

namespace App\Models;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Teacher extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable;

    /** @return bool */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /** @return void */
    public function markEmailAsVerified(): void
    {
        $this->forceFill(['email_verified_at' => $this->freshTimestamp()])->save();
    }

    /** @return string */
    public function getEmailForVerification(): string
    {
        return $this->email;
    }

    /** @return void */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmail);
    }

    protected $fillable = [
        'npsn',
        'name',
        'email',
        'password',
        'phone',
        'domisili',
        'school_name',
        'teaching_field',
        'gender',
        'birth_date',
        'photo',
        'status',
        'code_sales',
        'max_request'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function teacherBooks()
    {
        return $this->hasMany(TeacherBook::class, 'user_id');
    }
}
