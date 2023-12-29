<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Category
 *
 * @property int $id
 * @property int $parent_id
 * @property int $order
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $1c_id
 * @property int $parent_1c_id
 * @property bool $level
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Item[] $items
 * @property-read \App\Category $parentCategory
 * @property-read \App\Category $parentId
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Category[] $subCategory
 * @method static \Illuminate\Database\Query\Builder|\App\Category where1cId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereParent1cId($value)
 */
class Category extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'uuid';

//    protected $appends = array('sort_id');

    protected $fillable = ['uuid','id_1c','parent_uuid','parent_id_1c','name','default_sort','image','image_sm'];

    private $breadcrumbs;


    public function subCategory()
    {
        return $this->hasMany('App\Models\Category', 'parent_1c_id', 'id_1c')
            ->select(['name', 'id_1c', 'parent_1c_id', 'image_sm', 'default_sort'])
            ->orderBy('default_sort');
    }




    public function parentCategory()
    {
        return $this->belongsTo('App\Models\Category', 'parent_1c_id', 'id_1c');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Item', '1c_category_id', 'id_1c');
    }

    public function parentId()
    {
        return $this->belongsTo('App\Models\Category', 'parent_1c_id', 'id_1c');
    }
    public function parent1cId()
    {
        return $this->belongsTo('App\Models\Category', 'parent_1c_id', 'id_1c');
        //return Category::get();

    }

    public function getBreadcrumbs($category)
    {
        $this->breadcrumbs[] = ['id' => $category->id_1c, 'name' => $category->name];
        $this->checkParent($category->parent_1c_id);

        return $this->breadcrumbs;
    }

    public function checkParent($parent_1c_id)
    {
        if ($parent_1c_id != 0) {

            $category = self::where('id_1c', $parent_1c_id)->first();

            if($category) {
                array_unshift($this->breadcrumbs, ['id' => $category->id_1c, 'name' => $category->name]);
                $this->checkParent($category->parent_1c_id);
            }
        }
    }

    public function withSubCategories()
    {
        $categories = collect([$this]);
        $ids = [$this->id_1c];
        while(count($ids) > 0){
            $newCategories = $this->whereIn('parent_1c_id', $ids)->get();
            $ids = $newCategories->pluck('id_1c');
            $categories = $categories->merge($newCategories);
        }
        return $categories;
    }

}
