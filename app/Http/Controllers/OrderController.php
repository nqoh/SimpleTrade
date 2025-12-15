<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\HttpRequest;
use App\Models\User;
use App\Models\Assets;
use App\Models\Order;
use App\Models\Trades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\MatchingService;

class OrderController extends Controller
{

     use HttpRequest;

    public function __construct(public MatchingService $MatchingService)
    {
        
    }

    public function profile()
      {
        $user = auth()->user();
        return response()->json([
            'usd_balance' => $user->balance,
            'assets' => Assets::where('user_id', $user->id)->get(),
        ]);
    
      }

    public function index(Request $request)
     {
        $user  = Auth()->user();

        $symbol = $request->query('symbol');

        $CurrentOrders = Order::where('symbol', $symbol)
        ->where('status', 1)
        ->orderByDesc('price')
        ->get();


        $OrdersHistoryCanceled = Order::where('user_id', $user->id)
         ->where('status', 3)
         ->orderByDesc('price')
         ->get();

        $OrdersHistoryfilled =  Order::where('user_id', $user->id)
        ->where('status', 2)
        ->orderByDesc('price')
        ->get();

        $AuthUserCurrentOrders =  Order::where('user_id', $user->id)
        ->where('status', 1)
        ->orderByDesc('price')
        ->get();

        $result = collect()
                  ->merge($OrdersHistoryCanceled)
                  ->merge($OrdersHistoryfilled)
                  ->merge($AuthUserCurrentOrders);

      return response()->json([
        'orders' => $CurrentOrders,
        'history' =>  $result,
      ]);
    }

    public function store(OrderStoreRequest $request)
    {
        $user = auth()->user();

        return DB::transaction(function () use ($request, $user) {
    
            // Lock user row
            $user = User::where('id', $user->id)->lockForUpdate()->first();
    
            if ($request->side === 'buy') {
    
                $cost = $request->price * $request->amount;
    
                if ($user->balance < $cost) {
                  //  abort(400, 'Insufficient USD balance');
                   return $this->error('Insufficient USD balance');
                }
                // Deduct USD
                $user->balance -= $cost;
                $user->save();
    
            } else {
                // SELL
                $asset = Assets::where('user_id', $user->id)
                    ->where('symbol', $request->symbol)
                    ->lockForUpdate()
                    ->firstOrFail();
    
                if ($asset->amount < $request->amount) {
                   // abort(400, 'Insufficient asset balance');
                   return $this->error('Insufficient USD balance');
                }
    
                // Lock asset
                $asset->amount -= $request->amount;
                $asset->locked_amount += $request->amount;
                $asset->save();
            }

               // Create order
         $order = Order::create([
            'user_id' => $user->id,
            'symbol'  => $request->symbol,
            'side'    => $request->side,
            'price'   => $request->price,
            'amount'  => $request->amount,
            'status'  => 1,
        ]);
         // Try to match
         $this->MatchingService->matchOrder($order);

         return $this->success($order,'Order placed successfully',201);
    });
    
}


public function cancel($id)
{

    return DB::transaction(function () use ($id) {
     
        $order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 1)
            ->lockForUpdate()
            ->firstOrFail();

        $user = User::where('id', $order->user_id)->lockForUpdate()->first();

        if ($order->side === 'buy') {
            $refund = $order->price * $order->amount;
            $user->balance += $refund;
            $user->save();
        } else {
            $asset = Assets::where('user_id', $user->id)
                ->where('symbol', $order->symbol)
                ->lockForUpdate()
                ->first();

            $asset->locked_amount -= $order->amount;
            $asset->amount += $order->amount;
            $asset->save();
        }

        $order->status = 3;
        $order->save();

        return response()->json(['status' => 'cancelled']);
    });
}



public function trades()
{
   return Trades::all();
}

}