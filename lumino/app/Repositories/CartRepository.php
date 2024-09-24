<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class CartRepository
{
    public function addToCart($productId, $quantity = 1) {
        $userId = Auth::id();
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $product = Product::find($productId);
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }
    }

    public function getCart()
    {
        return $this->getDatabaseCart();
    }

    private function getDatabaseCart()
    {
        $userId = Auth::id();
        return Cart::where('user_id', $userId)
            ->with('product')
            ->get();
    }

    public function clearCart()
    {
        $this->clearDatabaseCart();
    }

    private function clearDatabaseCart()
    {
        $userId = Auth::id();
        Cart::where('user_id', $userId)->delete();
    }
    public function SumCart()
    {
        $userId = Auth::id();
        $productCount = Cart::where('user_id', $userId)->sum('quantity');
        return intval($productCount);
    }
}
