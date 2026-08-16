<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolStockRequest extends Model
{
    protected $fillable = [
        'requester_id',
        'approver_id',
        'status',
        'notes'
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function items()
    {
        return $this->hasMany(ToolStockRequestItem::class);
    }
}
