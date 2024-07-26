<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(GroupMessage::class, 'group_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_members');
    }
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}
