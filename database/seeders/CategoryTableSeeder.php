<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->delete();

        $subcategoriesJson = file_get_contents(
            database_path() . DIRECTORY_SEPARATOR .
            'data' . DIRECTORY_SEPARATOR .
            'categories' . DIRECTORY_SEPARATOR .
            "categories.json");
        $subcategories = json_decode($subcategoriesJson);

        foreach ($subcategories as $subcategory) {
            Category::create([
                'name' => $subcategory->name,
                'description' => $subcategory->description,
                'pic_url' => $subcategory->pic_url
            ]);
        }
    }
}
