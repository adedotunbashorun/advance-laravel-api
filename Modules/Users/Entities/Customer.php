<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\History\Traits\Historable;

class Customer extends Model
{
    use Historable;

    protected $fillable = [];
    protected $guarded = ['email','password','profile_image','roles'];

    public $attachments = [
        'valid_means_of_id','work_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
