<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;

class ProductController extends Controller
{
    use ApiResponser;

    /**
     * Get all products by subcategory
     *
     * @param int $subcategoryId
     * @return JsonResponse
     */
    public function getProductsBySubcategory(int $subcategoryId): JsonResponse
    {
        try {
            $products = DB::table('products')
                ->join('subcategories', 'products.subcategory_id', '=', "subcategories.id")
                ->select(
                    'products.name',
                    'products.description',
                    'products.price',
                    'products.pic_url',
                    'subcategories.name as subcategory')
                ->where('products.subcategory_id', $subcategoryId)
                ->get();
            return $this->success(['products' => $products]);

        } catch (QueryException $e) {
            return $this->error(500, $e->getMessage(), 'Database error');

        } catch (\Exception $e) {
            return $this->error(500, $e->getMessage());
        }
    }

}
