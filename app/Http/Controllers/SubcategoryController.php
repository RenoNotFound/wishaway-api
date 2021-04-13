<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class SubcategoryController extends Controller
{
    use ApiResponser;

    /**
     * Get all subcategories by category
     *
     * @param int $categoryId
     * @return JsonResponse
     */
    public function getSubcategories(int $categoryId): JsonResponse
    {
        try {
            $subcategories = Subcategory::where('category_id', $categoryId)->get();
            return $this->success(['subcategories' => $subcategories]);

        } catch (QueryException $e) {
            return $this->error(500, $e->getMessage(), 'Database error');
        } catch (\Exception $e) {
            return $this->error(500, $e->getMessage());
        }
    }
}
