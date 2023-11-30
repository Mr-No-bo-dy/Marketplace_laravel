<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subcategories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_subcategory';
    protected $fillable = [
        'id_category',
        'name',
        'description',
    ];

    /**
     * Setting relationship with DB table Category.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }

    /**
     * Read all entities from DB table Subcategories
     *
     * @return Collection
     */
    public function readAllSubcategories(): Collection
    {
        return Subcategory::select('c.name as category', $this->table . '.*')
                            ->join('categories as c', $this->table . '.id_category', '=', 'c.id_category')
                            ->withTrashed()
                            ->get();
    }

    /**
     * Soft-Delete entities in DB table Subcategories by given Category
     *
     * @param int $idCategory
     */
    public function deleteCategorySubcategories(int $idCategory): void
    {
        $idsSubcategories = Subcategory::where('id_category', $idCategory)
                                        ->pluck($this->primaryKey)
                                        ->all();
        foreach ($idsSubcategories as $id) {
            Subcategory::find($id)
                        ->delete();
        }
    }

    /**
     * Restore entities in DB table Subcategories by given Category
     *
     * @param int $idCategory
     */
    public function restoreCategorySubcategories(int $idCategory): void
    {
        $idsSubcategories = Subcategory::where('id_category', $idCategory)
                                        ->onlyTrashed()
                                        ->pluck($this->primaryKey)
                                        ->all();
        foreach ($idsSubcategories as $id) {
            Subcategory::onlyTrashed()
                        ->find($id)
                        ->restore();
        }
    }
}
