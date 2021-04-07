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
     * @param string $category
     * @return JsonResponse
     */
    public function getSubcategories(string $category): JsonResponse
    {
        try {
            $subcategories = Subcategory::where('category', $category)->get();
            return $this->success(['subcategories' => $subcategories]);

        } catch (QueryException $e) {
            return $this->error(500, $e->getMessage(), 'Database error');
        } catch (\Exception $e) {
            return $this->error(500, $e->getMessage());
        }
    }

}
