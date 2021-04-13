<?php

namespace Database\Seeders;

use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subcategories')->delete();

        $subcategoriesJson = file_get_contents(
            database_path() . DIRECTORY_SEPARATOR .
            'data' . DIRECTORY_SEPARATOR .
            'categories' . DIRECTORY_SEPARATOR .
            "subcategories.json");
        $subcategories = json_decode($subcategoriesJson);

        foreach ($subcategories as $subcategory) {
            Subcategory::create([
                'name' => $subcategory->name,
                'description' => $subcategory->description,
                'category_id' => $subcategory->category_id
            ]);
        }
    }
}
