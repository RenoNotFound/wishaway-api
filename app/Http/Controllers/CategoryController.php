<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponser;

    /**
     * Get all categories
     *
     * @return JsonResponse
     */
    public function getCategories(): JsonResponse
    {
        try {
            $categories = Category::all();
            return $this->success(['categories' => $categories]);

        } catch (QueryException $e) {
            return $this->error(500, $e->getMessage(), 'Database error');
        } catch (\Exception $e) {
            return $this->error(500, $e->getMessage());
        }
    }

}
