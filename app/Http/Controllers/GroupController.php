<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Group;
use App\Models\Product;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function index(Request $request): View
    {
        $order = 'asc';
        if ($request->order) {
            $order = $request->order === 'asc' ? 'asc' : 'desc';
        }
        $orderBy = 'name';
        if ($request->order_by) {
            $orderBy = $request->order_by === 'name' ? 'name' : 'currentPrice';
        }
        $categoryId = $request->category_id ? $request->category_id : 0;
        $products = [];
        $selectedGroup = '';
        $currentCategoryName = 'Онлайн Магазин';
        $selectedGroupsIds = [];
        if (!$categoryId) {
            if ($orderBy == 'currentPrice') {
                $products = Product::with('prices')->orderBy(
                    Price::select('price')->whereColumn('id_product', 'products.id')->orderBy('price')->limit(1), $order
                )->paginate(6);
            } else {
                $products = Product::orderBy($orderBy, $order)->paginate(6);
            }
        } else {
            $selectedGroup = Group::where('id', $categoryId)->first();
            if (is_null($selectedGroup)) {
                abort(404);
            }
            $selectedGroupsIds = $selectedGroup->getChildrenIds();
            array_push($selectedGroupsIds, $selectedGroup->id);
            if ($orderBy == 'currentPrice') {
                $products = Product::whereIn('id_group', $selectedGroupsIds)->with('prices')->orderBy(
                    Price::select('price')->whereColumn('id_product', 'products.id')->orderBy('price')->limit(1), $order
                )->paginate(6);
            } else {
                $products = Product::whereIn('id_group', $selectedGroupsIds)->orderBy($orderBy, $order)->paginate(6);
            }
            $selectedGroupsIds = $selectedGroup->getParentsIds();
            array_push($selectedGroupsIds, $selectedGroup->id);
            $currentCategoryName = $selectedGroup->name;
        }
        $rootGroupChildren = Group::where('id_parent', 0)->get();
        
        return view('index', [
            'tree' => $rootGroupChildren,
            'selectedGroupsIds' => $selectedGroupsIds,
            'currentCategoryName' => $currentCategoryName,
            'products' => $products,
        ]);
    }
}