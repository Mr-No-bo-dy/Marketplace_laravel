<?php

namespace App\Models\Admin;

use App\Models\Admin\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subcategory extends Model
{
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
     * Delete entity from DB table Subcategories
     *
     * @param int $idSubcategory
     */
    public function deleteSubcategory(int $idSubcategory): void
    {
        DB::table($this->table)
            ->where($this->primaryKey, $idSubcategory)
            ->delete();
    }
}
