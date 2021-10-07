<?php

namespace Akaunting\Firewall\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use SoftDeletes;

    protected array $table = 'firewall_logs';

    protected array $dates = ['deleted_at'];

    protected array $fillable = ['ip', 'level', 'middleware', 'user_id', 'url', 'referrer', 'request'];

    public function user()
    {
        return $this->belongsTo(config('firewall.models.user'));
    }
}
