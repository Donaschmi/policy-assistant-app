<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'actions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'assignable',
        'sentence'
    ];

    protected $casts = [
        'assignable' => 'boolean'
    ];


    public $timestamps = false;

    public function scopeAssignable(Builder $query)
    {
        return $query->where('assignable', 1);
    }
}
