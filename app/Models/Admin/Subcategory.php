<?php

namespace App\Models\Admin;

use App\Models\Admin\Category;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return DB::table($this->table)
            ->selectRaw('c.name as category, '.$this->table.'.*')
            ->join('categories as c', $this->table.'.id_category', '=', 'c.id_category')
            ->get();
    }

    /**
     * Read all entities' ids & names from DB table Subcategories
     *
     * @return Collection
     */
    public function readSubcategoriesNames(): Collection
    {
        return self::all([$this->primaryKey, 'name']);
    }

    /**
     * Read one entity from DB table Subcategories
     *
     * @param int $idSubcategory
     * @return object
     */
    public function readSubcategory(int $idSubcategory): object
    {
        return DB::table($this->table)
                ->where($this->primaryKey, $idSubcategory)
                ->first();
    }

    /**
     * Insert entity into DB table Subcategories
     *
     * @param array $data
     */
    public function storeSubcategory(array $data): void
    {
        DB::table($this->table)
            ->insert($data);
    }

    /**
     * Insert entity into DB table Subcategories
     *
     * @param int $idSubcategory
     * @param array $data
     */
    public function updateSubcategory(int $idSubcategory, array $data): void
    {
        DB::table($this->table)
            ->where($this->primaryKey, $idSubcategory)
            ->update($data);
    }

    /**
     * Soft-Delete entity in DB table Subcategories
     *
     * @param int $idSubcategory
     */
    public function deleteSubcategory(int $idSubcategory): void
    {
        $subcategory = self::find($idSubcategory);
        if ($subcategory) {
            $subcategory->delete();
        }
    }

    /**
     * Soft-Delete entities in DB table Subcategories by given Category
     *
     * @param int $idCategory
     */
    public function deleteCategorySubcategories(int $idCategory): void
    {
        $idsSubcategories = DB::table($this->table)
            ->select($this->primaryKey)
            ->where('id_category', $idCategory)
            ->get();
        foreach ($idsSubcategories as $std) {
            $subcategory = self::find($std->id_subcategory);
            if ($subcategory) {
                $subcategory->delete();
            }
        }
    }

    /**
     * Restore entity in DB table Subcategories
     *
     * @param int $idSubcategory
     */
    public function restoreSubcategory(int $idSubcategory): void
    {
        $subcategory = self::onlyTrashed()->find($idSubcategory);
        if ($subcategory) {
            $subcategory->restore();
        }
    }

    /**
     * Restore entities in DB table Subcategories by given Category
     *
     * @param int $idCategory
     */
    public function restoreCategorySubcategories(int $idCategory): void
    {
        $idsSubcategories = DB::table($this->table)
            ->select($this->primaryKey)
            ->where('id_category', $idCategory)
            ->get();
        foreach ($idsSubcategories as $std) {
            $subcategory = self::onlyTrashed()->find($std->id_subcategory);
            if ($subcategory) {
                $subcategory->restore();
            }
        }
    }
}
