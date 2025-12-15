<?php
namespace App\Services;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Assets;
use App\Models\Trades;
use App\Events\OrderMatched;

class MatchingService
{
 
public function matchOrder(Order $newOrder)
  {
    DB::transaction(function () use ($newOrder) {
     
        // lock the new order
        $newOrder->lockForUpdate();
        
        if ($newOrder->status !== 1) {
            return;
        }
       

        $counterOrder = $this->findCounterOrder($newOrder);

        if (! $counterOrder) {
            return;
        }

        $this->executeMatch($newOrder, $counterOrder);
    });
}


protected function findCounterOrder(Order $order): ?Order
{
    if ($order->side === 'buy') {
        return Order::where('symbol', $order->symbol)
            ->where('side', 'sell')
            ->where('status', 1)
            ->where('price', '<=', $order->price)
            ->orderBy('price', 'asc')
            ->lockForUpdate()
            ->first();
    }

    return Order::where('symbol', $order->symbol)
        ->where('side', 'buy')
        ->where('status', 1)
        ->where('price', '>=', $order->price)
        ->orderBy('price', 'desc')
        ->lockForUpdate()
        ->first();
}

protected function executeMatch(Order $a, Order $b): void
{
    $buy  = $a->side === 'buy'  ? $a : $b;
    $sell = $a->side === 'sell' ? $a : $b;

    $amount = $buy->amount;
    $price  = $sell->price; // usually use sell price
    $usdVolume = $amount * $price;

    $fee = $usdVolume * 0.015;

    // lock users
    $buyer  = User::lockForUpdate()->find($buy->user_id);
    $seller = User::lockForUpdate()->find($sell->user_id);

    // buyer receives BTC
    $buyerAsset = Assets::firstOrCreate(
        ['user_id' => $buyer->id, 'symbol' => $buy->symbol],
        ['amount' => 0, 'locked_amount' => 0]
    );

    $buyerAsset->amount += $amount;
    $buyerAsset->save();

    // seller releases locked BTC
    $sellerAsset = Assets::where('user_id', $seller->id)
        ->where('symbol', $sell->symbol)
        ->lockForUpdate()
        ->first();

    $sellerAsset->locked_amount -= $amount;
    $sellerAsset->save();

    // seller receives USDT minus fee
    $seller->balance += ($usdVolume - $fee);
    $seller->save();

    // orders completed
    $buy->update(['status' => 2]);
    $sell->update(['status' => 2]);

    Trades::create([
        'buy_order_id' => $buy->id,
        'sell_order_id' => $sell->id,
        'symbol' => $buy->symbol,
        'price' => $price,
        'amount' => $amount,
        'usd_volume' => $usdVolume,
        'fee' => $fee,
    ]);

   broadcast(new OrderMatched(auth()->user(), $buy, $sell));
}

}