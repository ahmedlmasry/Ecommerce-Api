<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BloodType;
use App\Models\BloodTypes;
use App\Models\Categorie;
use App\Models\category;
use App\Models\City;
use App\Models\Contact;
use App\Models\Governorate;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Token;
use Illuminate\Http\Request;


class MainController extends Controller
{
    public function products()
    {
        $products = Product::with('sizes')->latest()->paginate(5);
        return responseJson(1, 'success', $products);
    }

    public function newOrder(Request $request)
    {
        $validator = validator()->make(request()->all(), [
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.size_id' => 'required|exists:sizes,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);
        $order = $request->user()->orders()->create([
            'user_id' => $request->user()->id,
            'status' => 'pending',
            'total_price' => 0,
        ]);
        foreach ($request->products as $product) {
            $p = Product::find($product['product_id']);
            $readyProduct = [
                $product['product_id'] => [
                    'quantity' => $product['quantity'],
                    'size_id' => $product['size_id'],
                    'price' => $p['price'],
                ]
            ];
            $order->products()->attach($readyProduct);
            $order->total_price += $p['price'] * $product['quantity'];
            $order->save();
        }
        return responseJson(1, 'success', $order);

    }
    public function myOrders(Request $request){
        $orders = $request->user()->orders()->with('products.sizes')->latest()->paginate(5);
        return responseJson(1, 'success', $orders);
    }
    

}
