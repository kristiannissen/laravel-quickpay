<?php

namespace QuickPay\Ping;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['msg', 'scope', 'version'];
}
