<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brief extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id','user_id','title','status','progress','result_json','error_message','generated_at'
    ];

    protected $casts = [
        'result_json' => 'array',
        'generated_at' => 'datetime',
    ];

    public function project() { return $this->belongsTo(Project::class); }
    public function user() { return $this->belongsTo(User::class); }
}
