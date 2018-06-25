<?php

namespace App\Models;

class SmsCode extends BaseModel
{
    const STATUS_UNUSED = 0;
    const STATUS_USED = 1;

    protected $table = 'sms_code';
}