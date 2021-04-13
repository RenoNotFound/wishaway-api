<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->delete();
        $this->handleHealthProductsInsertion();

    }

    /**
     * Handles getting products and inserting them to database
     *
     */
    private function handleHealthProductsInsertion(): void
    {
        $healthSubcategories = ['posture.json', 'massagers.json', 'orthoses.json', 'fitness.json', 'masks.json'];

        foreach ($healthSubcategories as $healthSubcategory) {
            $products = $this->getContents('health', $healthSubcategory);
            $this->insertProducts($products);
        }
    }

    /**
     * Get the content of json files for products
     *
     * @param string $category
     * @param string $fileName
     * @return array
     */
    private function getContents(string $category, string $fileName): array
    {
        $contentsJson = file_get_contents(
            database_path() . DIRECTORY_SEPARATOR .
            'data' . DIRECTORY_SEPARATOR .
            'products' . DIRECTORY_SEPARATOR .
            $category . DIRECTORY_SEPARATOR . $fileName);

        return json_decode($contentsJson);
    }

    /**
     * Insert products to product table
     *
     * @param array $products
     */
    private function insertProducts(array $products): void
    {
        foreach ($products as $product) {
            Product::query()->create([
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'pic_url' => $product->pic_url,
                'category_id' => $product->category_id,
                'subcategory_id' => $product->subcategory_id,
            ]);
        }
    }
}
