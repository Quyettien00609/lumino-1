<?php

namespace App\Http\Controllers\Api\Cart;

use App\Http\Controllers\Controller;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $this->cartRepository->addToCart($validated['product_id'], $validated['quantity']);

        return response()->json(['message' => 'Product added to cart successfully']);
    }

    public function getCart()
    {
        $cart = $this->cartRepository->getCart();
        return response()->json($cart);
    }

    public function clearCart()
    {
        $this->cartRepository->clearCart();
        return response()->json(['message' => 'Cart cleared successfully']);
    }

    public function updateCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $this->cartRepository->addToCart($validated['product_id'], $validated['quantity']);

        return response()->json(['message' => 'Cart updated successfully']);
    }

    public function removeFromCart($productId)
    {
        $this->cartRepository->removeFromCart($productId);
        return response()->json(['message' => 'Product removed from cart successfully']);
    }
    public function SumCart(){
        $Sum=$this->cartRepository->SumCart();
        return response()->json($Sum);
    }

}
