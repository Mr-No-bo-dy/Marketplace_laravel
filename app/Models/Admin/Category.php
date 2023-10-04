<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_category';
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Read all entities from DB table Categories
     *
     * @return Collection
     */
    public function readAllCategories(): Collection
    {
        return DB::table($this->table)
                ->get();
    }

    /**
     * Read all entities ids & names from DB table Categories
     *
     * @return Collection
     */
    public function readCategoriesNames(): Collection
    {
        return DB::table($this->table)
                    ->select($this->primaryKey, 'name')
                    ->get();
    }

    /**
     * Read one entity from DB table Categories
     *
     * @param int $idCategory
     * @return object
     */
    public function readCategory(int $idCategory): object
    {
        return DB::table($this->table)
                    ->where($this->primaryKey, $idCategory)
                    ->first();
    }

    /**
     * Insert entity into DB table Categories
     *
     * @param array $data
     */
    public function storeCategory(array $data): void
    {
        DB::table($this->table)
            ->insert($data);
    }

    /**
     * Insert entity into DB table Categories
     *
     * @param int $idCategory
     * @param array $data
     */
    public function updateCategory(int $idCategory, array $data): void
    {
        DB::table($this->table)
            ->where($this->primaryKey, $idCategory)
            ->update($data);
    }

    /**
     * Delete entity from DB table Categories
     *
     * @param int $idCategory
     */
    public function deleteCategory(int $idCategory): void
    {
        DB::table($this->table)
            ->where($this->primaryKey, $idCategory)
            ->delete();
    }
}
