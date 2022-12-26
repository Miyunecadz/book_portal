<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectedEbookTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'instanceid',
        'author_name',
        'book_title',
        'instance_id',
        'year',
        'month',
        'class_of_trade',
        'line_item_no',
       'transactiondate',
       'agentid',
        'quantity',
        'price',
        'proceeds',
        'royalty'
    ];
}
