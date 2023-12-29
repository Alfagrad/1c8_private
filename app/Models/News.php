<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\News
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property string $content
 * @property string $path_image
 * @property bool $is_show_main
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\News whereAlias($value)
 * @method static \Illuminate\Database\Query\Builder|\App\News whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\News whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\News whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\News whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\News whereIsActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\News whereIsShowMain($value)
 * @method static \Illuminate\Database\Query\Builder|\App\News wherePathImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\News whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\News whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class News extends Model
{

//    protected $connection = 'mysql2';

    protected $fillable = ['title','alias','external_link','title_meta','description','content','path_image','is_show_main','is_active','for_opt','for_retail'];

    public function getRouteName()
    {
        if ($this->external_link) {
            return $this->external_link;
        }
        $parts = array_filter(explode('/', $this->alias));
        return (!empty($parts[0]) && in_array(array_shift($parts), ['catalogue']))
            ? route('newCatalogView', [implode('/', $parts)])
            : route('news.show', [$this->alias]);
    }

}
