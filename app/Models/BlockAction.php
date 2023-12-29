<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\BlockAction
 *
 * @property int $id
 * @property string $title
 * @property string $link
 * @property string $image
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\BlockAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BlockAction whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BlockAction whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BlockAction whereIsActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BlockAction whereLink($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BlockAction whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BlockAction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BlockAction extends Model
{
    protected $fillable = ['title','link','image','is_active'];
}
