<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Condition;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Payment;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'best');
        if ($tab === 'mylist') {
            if ($user) {
                $products = $user->likedProducts()->get();
            } else {
                $products = collect();
            }
        } else {
            $query = Product::query()->latest();
            if ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('seller_id', '!=', $user->id)
                        ->orWhereNull('seller_id');
                });
            }
            $products = $query->latest()->get();
        }
        return view('list', compact('products', 'tab'));
    }


    public function detail($item_id)
    {
        $product = Product::with('comments.user.profile', 'categories', 'condition', 'likedBy')->findOrFail($item_id);
        $categories = Category::all();
        $comments = Comment::with('user.profile')->where('product_id', $item_id)->latest()->get();
        $isLiked = false;
        if (auth()->check()) {
            $isLiked = $product->likedBy()->where('user_id', auth()->id())->exists();
        }
        return view('detail', compact('product', 'categories', 'comments', 'isLiked'));
    }


    public function postComment(CommentRequest $request, $item_id)
    {
        Comment::create([
            'user_id' => auth()->id(),
            'product_id' => $item_id,
            'content' => $request->input('content')
        ]);
        return redirect()->back();
    }


    public function toggle($item_id)
    {
        $user_id = auth()->id();
        $like = Like::where('user_id', $user_id)
            ->where('product_id', $item_id)
            ->first();
        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user_id,
                'product_id' => $item_id,
            ]);
        }
        return back();
    }


    public function getPurchase($item_id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($item_id);
        $profile = $user->profile;
        $shipping_address = session('shipping_address') ?: [
            'postal_code' => $user->profile->postal_code ?? '',
            'city' => $user->profile->city ?? '',
            'building' => $user->profile->building ?? ''
        ];
        $payments = Payment::all();
        return view('purchase', compact('product', 'profile', 'payments', 'shipping_address'));
    }


    public function editAddress($item_id)
    {
        $product = Product::findOrFail($item_id);
        $profile = Auth::user()->profile;
        return view('address_change', compact('product', 'profile'));
    }


    public function updateAddress(AddressRequest $request, $item_id)
    {
        session([
            'shipping_address' => $request->only('postal_code', 'city', 'building')
        ]);
        return redirect()->route('purchase', ['item_id' => $item_id]);
    }


    // public function postPurchase(PurchaseRequest $request, $item_id)
    // {
    //     $user = Auth::user();
    //     $product = Product::findOrFail($item_id);
    //     $shipping = session('shipping_address') ?: [
    //         'postal_code' => $user->profile->postal_code,
    //         'city' => $user->profile->city,
    //         'building' => $user->profile->building
    //     ];
    //     $product->payment_id = $request->payment_id;
    //     $product->shipping_address = json_encode($shipping);
    //     $product->buyer_id = $user->id;
    //     $product->status = 'sold';
    //     $product->save();
    //     session()->forget('shipping_address');
    //     return redirect('/',);
    // }


    // public function postPurchase(PurchaseRequest $request, $item_id)
    // {
    //     $user = Auth::user();
    //     $product = Product::findOrFail($item_id);
    //     Stripe::setApiKey(env('STRIPE_SECRET'));
    //     $paymentMethod = $request->payment_id;
    //     $session = Session::create([
    //         'payment_method_types' => ['card'],
    //         'line_items' => [[
    //             'price_data' => [
    //                 'currency' => 'jpy',
    //                 'product_data' => [
    //                     'name' => $product->name
    //                 ],
    //                 'unit_amount' => $product->price,
    //             ],
    //             'quantity' => 1,
    //         ]],
    //         'mode' => 'payment',
    //         'success_url' => url('/purchase/success') . '?item_id=' . $product->id . '&payment_id=' . $paymentMethod,
    //         'cancel_url' => url('/purchase/cancel'),
    //     ]);
    //     return redirect($session->url);
    // }

    public function postPurchase(PurchaseRequest $request, $item_id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($item_id);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentMethod = $request->payment_id;

        if ($paymentMethod == 1) {
            $session = Session::create([
                'payment_method_types' => ['konbini'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => ['name' => $product->name],
                        'unit_amount' => $product->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',

                'success_url' => url('/') . '?pending=1',
                'cancel_url' => url('/purchase/cancel'),

                'payment_intent_data' => [
                    'metadata' => [
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                    ]
                ]
            ]);
        }

        if ($paymentMethod == 2) {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => ['name' => $product->name],
                        'unit_amount' => $product->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('/purchase/success') . '?item_id=' . $product->id . '&payment_id=' . $paymentMethod,
                'cancel_url' => url('/purchase/cancel'),
                'payment_intent_data' => [
                    'metadata' => [
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                    ]
                ]
            ]);
        }
        return redirect($session->url);
    }


    public function purchasePending(Request $request)
    {
        $product = Product::findOrFail($request->item_id);

        return view('purchase.pending', compact('product'));
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (\Exception $e) {
            return response('Webhook error', 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;

            $productId = $intent->metadata->product_id;
            $userId = $intent->metadata->user_id;

            $product = Product::find($productId);
            $product->status = 'sold';
            $product->buyer_id = $userId;
            $product->save();
        }

        return response('ok', 200);
    }


    public function purchaseSuccess(Request $request)
    {
        $user = Auth::user();
        $product = Product::findOrFail($request->item_id);
        $shipping = session('shipping_address') ?: [
            'postal_code' => $user->profile->postal_code,
            'city' => $user->profile->city,
            'building' => $user->profile->building
        ];
        $product->payment_id = $request->payment_id;
        $product->shipping_address = json_encode($shipping);
        $product->buyer_id = $user->id;
        $product->status = 'sold';
        $product->save();
        session()->forget('shipping_address');
        return redirect('/');
    }


    public function getSell()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        return view('sell', compact('categories', 'conditions'));
    }


    public function postSell(ExhibitionRequest $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = preg_replace('/[^A-Za-z0-9_-]/u', '', $name);
            $filename = $safeName . '.' . $extension;
            $imagePath = $file->storeAs('images', $filename, 'public');
        }
        $product = Product::create([
            'seller_id' => Auth::id(),
            'condition_id' => $request->input('condition'),
            'image' => $imagePath,
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'brand' => $request->input('brand'),
            'description' => $request->input('description'),
            'status' => 'available',
        ]);
        if ($request->has('product_category')) {
            $product->categories()->attach($request->input('product_category'));
        }
        return redirect('/mypage');
    }


    public function search(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'best');
        $keyword = $request->query('keyword');
        if ($tab === 'mylist') {
            if ($user) {
                $query = $user->likedProducts();
            } else {
                $products = collect();
            }
        } else {
            $query = Product::query();
            if ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('seller_id', '!=', $user->id)
                        ->orWhereNull('seller_id');
                });
            }
        }
        if (isset($query)) {
            $products = $query->KeywordSearch($keyword)->get();
        }
        return view('list', compact('products', 'tab', 'keyword'));
    }
}
