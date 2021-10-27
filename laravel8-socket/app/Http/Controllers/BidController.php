<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bid;
use App\Events\BidEvent;

class BidController extends Controller
{
    public function lastPriceUnit()
    {

        $lastPrice = Bid::orderBy('price', 'desc')->first();

        $json = [
            "success" => "data tersedia",
            "data" => $lastPrice
        ];

        return response()->json($json, 200);
    }

    public function sendBidUnit(Request $request)
    {
        try {

            $bid = new Bid();
            $bid->user_id = $request->user_id;
            $bid->car_id = $request->car_id;
            $bid->price = $request->price;
            $bid->save();

            // Send event broadcast
            event(new BidEvent($request->price));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['success' => "sukses bid"], 200);
    }

    public function priceList()
    {
        $priceList = Bid::orderBy('price', 'desc')->get();

        $json = [
            "success" => "data tersedia",
            "data" => $priceList
        ];

        return response()->json($json, 200);
    }
}
