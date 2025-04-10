<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function update_call_status(Request $request)
    {
        $providedToken = $request->header('Authorization');;
        $payload = $request->getContent();
        $expectedToken = env('WEBHOOK_TOKEN');

        if ($providedToken !== 'Bearer ' . $expectedToken) {
            return response()->json(['error' => 'Unauthorized!'], 401);
        }

        $validator=Validator::make($request->all(),[
            'transaction_id' => 'required',
            'status' => 'required'
        ]);

        // Find the record by ID and update the status
        $CallDetails = Notification::where('call_id', $request->transaction_id)->first();
        
        if ($CallDetails) {
            $CallDetails->receive_status = $request->status;
            $CallDetails->save();

            return ResponseAPI(true, "Status updated successfully.", "", "", 200, 0);
        } else {
            return ResponseAPI(false, 'Record not found. Please Try Again.',"",array(),401);
        }

        return response()->json(['status' => 'success'], 200);
    }
}
