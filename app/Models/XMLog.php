<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\XMLog
 *
 * @property int $id
 * @property string $text
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\XMLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\XMLog whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\XMLog whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\XMLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class XMLog extends Model
{
    protected $guarded = [];
    protected $table = 'xmlogs';
    protected $fillable = ['text'];
}
