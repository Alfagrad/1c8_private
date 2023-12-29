<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ReviewItem
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property string $content
 * @property string $path_image
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\ReviewItem whereAlias($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReviewItem whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReviewItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReviewItem whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReviewItem whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReviewItem whereIsActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReviewItem wherePathImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReviewItem whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ReviewItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ReviewItem extends Model
{

    protected $connection = 'mysql2';
    protected $fillable = ['title','alias','description','content','path_image','is_active'];
    //
}
