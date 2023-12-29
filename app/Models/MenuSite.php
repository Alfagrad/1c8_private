<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MenuSite
 *
 * @property int $id
 * @property string $name
 * @property string $link
 * @property int $parent_id
 * @property bool $new_window
 * @property int $pos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MenuSite[] $subMenu
 * @method static \Illuminate\Database\Query\Builder|\App\MenuSite whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MenuSite whereLink($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MenuSite whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MenuSite whereNewWindow($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MenuSite whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MenuSite wherePos($value)
 * @mixin \Eloquent
 */
class MenuSite extends Model
{
    protected $table = 'menu_site';

    protected $fillable = ['name','link','parent_id','new_window','pos','is_active'];

    public function subMenu()
    {
        return $this->hasMany('App\Models\MenuSite', 'parent_id', 'id');
    }

    public function parentId()
    {
        return $this->belongsTo('App\Models\MenuSite', 'id', 'parent_id');
        //return MenuSite::orderBy('created_at')->get();
    }

    public function parentIdList()
    {
        $empty = new MenuSite;
        $empty->name = 'Нет';
        $empty->id = 0;

        $siteMenu = MenuSite::where('parent_id', 0)->get();
        $siteMenu->push($empty)->sortBy('id');
        $siteMenu->sortByDesc('id');
        return $siteMenu;
    }



}
