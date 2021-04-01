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
        $this->insertHealthProducts();

    }

    private function insertHealthProducts(): void
    {
        $productsPosture = $this->getContents('health','posture.json');
        $productsMassagers = $this->getContents('health','massagers.json');
        $productsOrthoses = $this->getContents('health','orthoses.json');
        $productsFitness = $this->getContents('health','fitness.json');
        $productsMasks = $this->getContents('health','masks.json');

        $this->insertProducts($productsPosture);
        $this->insertProducts($productsMassagers);
        $this->insertProducts($productsOrthoses);
        $this->insertProducts($productsFitness);
        $this->insertProducts($productsMasks);
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
                'subcategory_id' => $product->subcategory_id,
            ]);
        }
    }
}
