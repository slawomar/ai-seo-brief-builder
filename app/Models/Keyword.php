<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keyword extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'phrase', 'locale', 'intent', 'volume', 'difficulty'];

    public function project() { return $this->belongsTo(Project::class); }
}
