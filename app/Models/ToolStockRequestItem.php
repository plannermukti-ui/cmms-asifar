<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolStockRequestItem extends Model
{
    protected $fillable = [
        'tool_stock_request_id',
        'tool_id',
        'location_type',
        'mechanic_id',
        'quantity'
    ];

    public function request()
    {
        return $this->belongsTo(ToolStockRequest::class, 'tool_stock_request_id');
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }
}
