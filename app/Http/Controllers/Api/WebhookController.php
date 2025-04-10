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
        // $CallDetails = Notification::where('call_id', $request->transaction_id)->first();

        $CallDetails = Notification::where('tbl_notification.call_id', $request->transaction_id)
                        ->join('tbl_device_token', 'tbl_notification.user_id', '=', 'tbl_device_token.user_id')
                        ->join('tbl_customers', 'tbl_notification.customer_id', '=', 'tbl_customers.id')
                        ->select('tbl_notification.id', 'tbl_notification.receive_status', 'tbl_device_token.token as device_token', 'tbl_customers.name as customer_name')
                        ->first();

        if ($CallDetails) {
            
            // Update the receive_status
            Notification::where('id', $CallDetails->id)->update(['receive_status' => $request->status]);
            
            $deviceToken = $CallDetails->device_token;
            $token = rtrim($deviceToken, ',');
            $customerName = $CallDetails->customer_name;

            $title = "";
            $message = "";
            if($request->status == "Answered"){

                $title = '📞 Call Answered!';
                $message = $customerName. " answered your Call.";
            } else {

                $title = '❌ Missed Call!';
                $message = $customerName. " didn't pick up your Call.";
            }

            // Send notification
            SendNotificationUser($token, $message, $title);

            return ResponseAPI(true, "Status updated successfully.", "", "", 200, 0);
        } else {
            return ResponseAPI(false, 'Record not found. Please Try Again.', "", array(), 401);
        }

        return response()->json(['status' => 'success'], 200);
    }
}
