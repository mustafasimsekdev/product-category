<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductLogs extends Model
{
    use HasFactory;

    protected $table = 'product_logs';
    protected $guarded = [];

    public function authorD()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function oldLogC()
    {
        return $this->belongsTo(Logs::class, 'old_log_id', 'id');
    }

    public function logC()
    {
        return $this->belongsTo(Logs::class, 'log_id', 'id');
    }
}
