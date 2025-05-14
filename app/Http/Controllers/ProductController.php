<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function view(Request $request): View
    {
        $products = [];
        $debug = '';
        $selectedGroup = '';
        $currentCategoryName = 'Главная';
        $selectedGroupsIds = [];
        $product_id = $request->product_id ? $request->product_id : 0;
        $product = Product::where('id', $product_id)->with('prices')->first();
        if (is_null($product)) {
            abort(404);
        }
        return view('product', [
            'product' => $product,
        ]);
    }
}