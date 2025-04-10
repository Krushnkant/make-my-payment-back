<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\Reminder;
use App\Models\Reminderlogs;
use App\Models\User;
use App\Models\Customer;
use App\Models\Business;
use App\Models\Notification;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReminderController extends Controller
{
    public function create(Request $request)
    {
        try {

            $validator = Validator::make($request->all(),[
                'user_id' => 'required',
                'business_id' => 'required',
                'customer_id' => 'required',
                'reminder_date'=>'required',
                'reminder_time'=>'required',
            ]);

            if(isset($request->reminder_type) && $request->reminder_type != ''){

                $validator = Validator::make($request->all(),[
                    'reminder_type' => 'required'
                ]);

                $is_call = ($request->reminder_type == 'CALL') ? 1 : 0;
                $is_sms = ($request->reminder_type == 'SMS') ? 1 : 0;
                $is_email = ($request->reminder_type == 'EMAIL') ? 1 : 0;

            } else {

                $validator=Validator::make($request->all(),[
                    'is_call'=>'required',
                    'is_sms'=>'required',
                    'is_email'=>'required',
                    // 'is_whatsapp'=>'required',
                    // 'is_flashsms'=>'required',
                ]);

                $is_call = (bool) $request->is_call;
                $is_sms = (bool) $request->is_sms;
                $is_email = (bool) $request->is_email;
            }

            $is_whatsapp = (bool) 0; //$request->is_whatsapp;
            $is_flashsms = (bool) 0; //$request->is_flashsms;

            if($validator->fails())
            {
                return ResponseAPI(false, 'required fields', $validator->errors(), array(), 401);
            } else {

                $reminder_time = $request->reminder_time;
                $reminderHourlyTime = Carbon::createFromFormat('H:i', $reminder_time);
                if($is_call == false && $is_sms == false && $is_email == false && $is_whatsapp == false && $is_flashsms == false){
                    return ResponseAPI(false, 'Select any one type of Reminder.', $validator->errors(), array(), 401);
                }

                if ($reminderHourlyTime->format('H') < 9 || $reminderHourlyTime->format('H') >= 21) {
                    return ResponseAPI(false, 'Reminder time should be only 9 AM to 9 PM.', "", [], 401);
                }

                $userdata = User::where('id', $request->user_id)->first();
                $Reminder = Reminder::where('business_id', $request->business_id)->where('customer_id', $request->customer_id)->first();

                if($userdata->total_call > 0 && $userdata->total_message > 0){

                    if(isset($Reminder) && $Reminder != null){

                        Reminderlogs::where('reminder_id', $Reminder->id)->delete();

                    } else {
                        $Reminder = new Reminder;
                        $Reminder->user_id      = $request->user_id;
                        $Reminder->customer_id  = $request->customer_id;
                        $Reminder->business_id  = $request->business_id;
                    }
                    $Reminder->reminder_date    = $request->reminder_date;
                    $Reminder->reminder_time    = $reminder_time;
                    $Reminder->repeat_type      = $request->repeat_type;
                    $Reminder->frequency        = $request->frequency;
                    $Reminder->every            = $request->every;
                    $Reminder->is_call          = $is_call;
                    $Reminder->is_sms           = $is_sms;
                    $Reminder->is_email         = $is_email;
                    $Reminder->is_whatsapp      = $is_whatsapp;
                    $Reminder->is_flashsms      = $is_flashsms;

                    if($Reminder->save()){
                        $this->Reminderlog($Reminder);
                        return ResponseAPI(true,"Reminder set succesfull", "", "", 200, 0);
                    }

                } else {
                    return ResponseAPI(false,'Please Recharge and Try Again.',"",array(),401);
                }
            }
        } catch (\Throwable $th) {
            return ResponseAPI(false, 'Something Wrongs.',"",array(),401);
        }
     }



     public function destroy(Request $request)
     {
        try {
             $validator=Validator::make($request->all(),[
                'business_id' => 'required',
                'customer_id' => 'required',
             ]);

             if($validator->fails()){
                 return ResponseAPI(false,'required fields',$validator->errors(),array(),401);
             }else{
                 $Reminderdata = Reminder::where('business_id',$request->business_id)->where('customer_id',$request->customer_id)->first();
                 $Reminder = Reminder::where('business_id',$request->business_id)->where('customer_id',$request->customer_id);
                 Reminderlogs::where('reminder_id', $Reminderdata->id)->delete();
                 if($Reminder->delete()){
                     return ResponseAPI(true,"Reminder deleted Succesfull", "", "", 200);
                 }
             }
        } catch (\Throwable $th) {
             return ResponseAPI(false,'Something Wrongs.',"",array(),401);
        }
     }

     public function GetReminder(Request $request)
     {
         try {
             $validator=Validator::make($request->all(),[
                'business_id' => 'required',
                'customer_id' => 'required',
             ]);
             if($validator->fails())
             {
                 return ResponseAPI(false,'required fields',$validator->errors(),array(),401);
             }else{
                $getreminder = Reminder::where('business_id',$request->business_id)->where('customer_id',$request->customer_id)->first();
                if(isset($getreminder) && $getreminder!=null){
                    return ResponseAPI(true,"Customer get Succesfull", "", $getreminder, 200);
                }else{
                    return ResponseAPI(false,"Customer get Succesfull", "", $getreminder, 200);
                }

             }

         } catch (\Throwable $th) {
             return ResponseAPI(false,'Something Wrongs.',"",array(),401);
         }
     }

    public function SendReminderCall($remidata)
    {
        try
        {
            $status="fail";
            $TagId="";
            $userdata = User::where('id', $remidata->user_id)->first();

            //get fcm token
            $userToken = DeviceToken::where('user_id', $remidata->user_id)->first();
            if($userdata == Null){
                return ResponseAPI(false,'Something went Wrong.',"",array(),401);
            }

            $customer = Customer::where('id', $remidata->customer_id)->first();
            $business = Business::where('id', $customer['business_id'])->first();

            if($customer->phone == '9999802607' || $customer->phone == '+919999802607'){
                return ResponseAPI(false,'You can not call this number due to being blocked.',"",array(),401);
            }

            // if(isset($customer->phone) && $customer->phone == '9601798557' && isset($customer->name) && isset($customer->balance)){
            if( isset($customer->name) && isset($customer->balance) ){

                $call = SendCall($customer->phone, $customer->name, $customer->balance, $business->bus_name, $customer->native_language);
                if(isset($call)){
                    $response=json_decode($call);
                    // if(isset($response) && $response->status == "success"){
                    //     $status=$response->status;
                    //     $TagId=$response->TagId;
                    // }
                    if(isset($response) && $response->ERR_CODE == "0"){
                        $status = 'success';
                        $TagId = $response->TRANS_ID;
                    }
                }

                if($status == "success"){

                    //Send notification to user
                    $token = "";
                    $token .= $userdata->getdevicetoken->token;

                    $token = rtrim($token, ',');
                    $title = "Your call has been sent to ".$customer->name;
                    $message = date('Y-m-d');

                    SendNotificationUser($token, $message, $title);

                    //  $SERVER_API_KEY = 'AAAACBUmy8g:APA91bGlTz8LDCOrAvjA-as1ORoOtYb8RWkN_sQ1PlOYJ2O4S9uuYTMwsBO1IdqEu4edq59UttOyRRDoYWEf2VqF6RiOwY61mJvmGACojUu3RPvuW9BPS8HzJWUB8Bidj15SUqwTMoGF';

                    //  $data = [
                    //       "to" =>$userToken['token'],
                    //      "notification" => [
                    //          "title" => "Your call has been sent to ".$customer->name,
                    //          "body" => date('Y-m-d'),
                    //      ]
                    //  ];
                    //  $dataString = json_encode($data);

                    //  $headers = [
                    //      'Authorization: key=' . $SERVER_API_KEY,
                    //      'Content-Type: application/json',
                    //  ];

                    //  $ch = curl_init();

                    //  curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                    //  curl_setopt($ch, CURLOPT_POST, true);
                    //  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    //  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    //  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    //  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    //  curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

                    //  $response = curl_exec($ch);

                    //End send notification to user
                    $notification = new Notification;
                    $notification->user_id = $remidata->user_id;
                    $notification->business_id = $remidata->business_id;
                    $notification->customer_id = $remidata->customer_id;
                    $notification->customer_name = $customer->name;
                    $notification->customer_mobile = $customer->phone;
                    $notification->title = "Payment Reminder for ".$customer->name;
                    $notification->description = "Payment Reminder Call Is Pending";
                    $notification->type = 'CALL';
                    $notification->status = 0;
                    $notification->call_id=$TagId;
                    if($notification->save()){
                        $userdata->total_call-=1;
                        $userdata->save();
                        return ResponseAPI(true,"Notification Send Succesfull", "", $notification, 200);
                    }
                }
                return ResponseAPI(false,'Something went Wrong.',"",array(),401);
            }else{
                return ResponseAPI(false,'Something went Wrong.',"",array(),401);
            }
            return ResponseAPI(false,'Something went Wrong.',"",array(),401);
        } catch (\Throwable $th) {
            return ResponseAPI(false,'Something went Wrong.',"",array(),401);
        }
    }

    public function SendReminderSMS($remidata)
    {
        try {
            $status="fail";
            $TagId="";
            $userdata = User::where('id', $remidata->user_id)->first();

            if($userdata == Null){
                return ResponseAPI(false,'Something went Wrong.',"",array(),401);
            }

            $customer = Customer::where('id', $remidata->customer_id)->first();
            $business = Business::where('id', $customer['business_id'])->first();

            if(isset($customer->phone) && isset($customer->name) && isset($customer->balance)){

                if($userdata->total_message  > 0){

                    $call = SendSMS($customer->phone,$customer->name,$business->bus_name,$customer->balance,$customer->native_language);
                    if ($call == false || str_contains($call, "error")) {
                        return ResponseAPI(false,'SMS sending failed. Please contact Administration. ',"",array(),401);
                    } else {
                        $Response_Array = explode('-', $call);
                        $status = 'success';
                        $TagId = $Response_Array[1];
                    }
                    // dd($call);
                    if($status == "success"){
                        $notification = new Notification;
                        $notification->user_id = $remidata->user_id;
                        $notification->business_id = $remidata->business_id;
                        $notification->customer_id = $remidata->customer_id;
                        $notification->customer_name = $customer->name;
                        $notification->customer_mobile = $customer->phone;
                        $notification->title = "Payment Reminder for ".$customer->name;
                        $notification->description = "Payment Reminder SMS Is Sent";
                        $notification->type = "SMS";
                        $notification->call_id=$TagId;
                        if($notification->save()){
                            $userdata->total_message-=1;
                            $userdata->save();
                            return ResponseAPI(true,"Notification Send Succesfull", "", $notification, 200);
                        }
                    }
                }else{
                    return ResponseAPI(false,'Your SMS balance are ovred.',"",array(),401);
                }
            }else{
                return ResponseAPI(false,'Something went Wrong.',"",array(),401);
            }
            return ResponseAPI(false,'Something went Wrong.',"",array(),401);
        } catch (\Throwable $th) {
            return ResponseAPI(false,'Something went Wrong.',"",array(),401);
        }
    }

    public function SendReminderEmail($remidata)
    {
        try {
            $status="fail";
            $TagId="";
            $userdata = User::where('id', $remidata->user_id)->first();

            if($userdata == Null){
                return ResponseAPI(false,'Something went Wrong.',"",array(),401);
            }

            $customer = Customer::where('id', $remidata->customer_id)->first();

            $business = Business::where('id', $customer['business_id'])->first();

            if(isset($customer->email) && isset($customer->name) && isset($customer->balance)){

                SendEmail($customer->email,$customer->name,$business->bus_name,$customer->balance);

                $notification = new Notification;
                $notification->user_id = $remidata->user_id;
                $notification->business_id = $remidata->business_id;
                $notification->customer_id = $remidata->customer_id;
                $notification->customer_name = $customer->name;
                $notification->customer_mobile = $customer->phone;
                $notification->title = "Payment Reminder for ".$customer->name;
                $notification->description = "Payment Reminder EMAIL Is Sent";
                $notification->type = "EMAIL";
                $notification->call_id=$TagId ?? "";
                if($notification->save()){
                    return ResponseAPI(true,"Notification Send Succesfull", "", $notification, 200);
                }

            }else{
                return ResponseAPI(false,'Something went Wrong.',"",array(),401);
            }
            return ResponseAPI(false,'Something went Wrong.',"",array(),401);
        } catch (\Throwable $th) {
            return ResponseAPI(false,'Something went Wrong.',"",array(),401);
        }
    }

     public function reminder_cron(Request $request)
     {
        $currentHour = now()->format('H');
        if ($currentHour < 9 || $currentHour >= 21) {
            return false;
        }
        // try {
            $current_date = Carbon::today()->toDateString(); // Example: '2025-01-22'
            $now = Carbon::now(); // Current date and time as Carbon instance
            $last_15_minutes = $now->copy()->subMinutes(15); // Subtract 15 minutes from now

            $getreminderlist = Reminderlogs::where('send_date', $current_date)
            ->where('send_time', '>=', $last_15_minutes->format('H:i:s'))  // '14:20:00'
            ->where('send_time', '<=', $now->format('H:i:s'))  // '14:35:00'
            ->get();

            // $getreminderlist = Reminderlogs::where('send_date', $current_date)
            // ->whereRaw("CONCAT(send_date, ' ', send_time) < ?", [$now])
            // ->get();

            // $getreminderlist = Reminderlogs::where('send_date',$current_date)
            // ->where('send_time', $now)
            // ->get();

            // Query to get the records where the send_date is the current date 
            // and send_time is within the last 15 minutes
            $getreminderlist = Reminderlogs::where('send_date', $current_date)
            ->where('send_time', '>=', $last_15_minutes->format('H:i:s'))  // Compare with the time in 'HH:MM:SS' format
            ->where('send_time', '<=', $now->format('H:i:s'))  // Ensure it is within the current time range
            ->get();

            // $query = Reminderlogs::where('send_date', "'".$current_date."'")
            // ->where('send_time', "'".$now."'");
            // $sql = Str::replaceArray('?', $query->getBindings(), $query->toSql());
            // dd($sql);

            $currentdate = date('Y-m-d');
            $currenttime = date('H');
            // dd($getreminderlist);
            // \Log::info("Rreminder List: ". $getreminderlist);
            if($getreminderlist->count() > 0){

                foreach ($getreminderlist as $key => $value) {
                    
                    $reminder_id = $value->reminder_id;
                    if($value->is_call == 1){
                        //dd($value->check_balance($value->user_id));
                        if($value->check_balance($value->user_id) == 1){
                            $remindertime = date('H', strtotime($value->send_time));
                            $logsToday = getTodayNotificationCount($value->user_id, $value->customer_id, 'CALL');

                            if ($logsToday < getSetting()->daily_call_limit) {
                                // if($currentdate == $value->send_date && $currenttime == $remindertime){
                                    $this->SendReminderCall($value);
                                    logNotification($value->user_id, $value->customer_id, 'CALL');
                                // }
                            }
                        }
                    }

                    if($value->is_sms == 1 || $value->is_flashsms == 1){
                        if($value->check_sms_balance($value->user_id) == 1){
                            $remindertime = date('H', strtotime($value->send_time));
                            $logsToday = getTodayNotificationCount($value->user_id, $value->customer_id, 'SMS');
                            if ($logsToday < getSetting()->daily_sms_limit) {
                                if($currentdate == $value->send_date && $currenttime == $remindertime){
                                    $this->SendReminderSMS($value);
                                    logNotification($value->user_id, $value->customer_id, 'SMS');
                                }
                            }
                        }
                    }

                    if($value->is_email == 1){

                        $remindertime = date('H',strtotime($value->send_time));
                        $logsToday = getTodayNotificationCount($value->user_id, $value->customer_id,'EMAIL');
                        if ($logsToday < getSetting()->daily_email_limit) {
                            if($currentdate == $value->send_date && $currenttime == $remindertime){
                                $this->SendReminderEmail($value);
                                logNotification($value->user_id, $value->customer_id, 'EMAIL');
                            }
                        }
                    }

                    if($currentdate == $value->send_date && $currenttime == $remindertime){
                        $value->delete();
                    }

                    if(Counterreminder($reminder_id) == 0){
                        $Reminder = Reminder::where('id',$reminder_id)->delete();
                    }
                }
                return ResponseAPI(true,"Send Succesfull", "", array(), 200);
            }
            return ResponseAPI(true,'data not found.',"",array(),200);
        // } catch (\Throwable $th) {
        //      return ResponseAPI(false,'Getting some error. Something Wrongs.',"",array(),401);
        //      \Log::error('Failed to fetch API: ' . $e->getMessage());
        // }
     }


     function Reminderlog($remidata)
     {

        try {
            // if(isset($remidata)){
            //     if($remidata->repeat_type=="Never"){
            //                 $remiderlog = new Reminderlogs;
            //                 $remiderlog->reminder_id = $remidata->id;
            //                 $remiderlog->user_id = $remidata->user_id;
            //                 $remiderlog->customer_id = $remidata->customer_id;
            //                 $remiderlog->business_id = $remidata->business_id;
            //                 $remiderlog->send_date = $remidata->reminder_date;
            //                 $remiderlog->send_time = $remidata->reminder_time;
            //                 $remiderlog->reminder_type  = $remidata->reminder_type ?? "";
            //                 $remiderlog->save();
            //     }else if($remidata->repeat_type=="Hourly"){
            //             $remindertime = 24 - date('H',strtotime($remidata->reminder_time));
            //             for ($i=0; $i < $remindertime ; $i++) {
            //                 $timeset = date('H:i:s', strtotime($remidata->reminder_time . " +".$i." hours"));
            //                 $remiderlog = new Reminderlogs;
            //                 $remiderlog->reminder_id = $remidata->id;
            //                 $remiderlog->user_id = $remidata->user_id;
            //                 $remiderlog->customer_id = $remidata->customer_id;
            //                 $remiderlog->business_id = $remidata->business_id;
            //                 $remiderlog->send_date = $remidata->reminder_date;
            //                 $remiderlog->send_time = $timeset;
            //                 $remiderlog->reminder_type  = $remidata->reminder_type ?? "";
            //                 $remiderlog->save();
            //             }
            //     }else if($remidata->repeat_type=="Daily"){

            //             for ($i=0; $i < 7 ; $i++) {
            //                 $dateset = date('Y-m-d', strtotime($remidata->reminder_date . " +".$i." days"));
            //                 $remiderlog = new Reminderlogs;
            //                 $remiderlog->reminder_id = $remidata->id;
            //                 $remiderlog->user_id = $remidata->user_id;
            //                 $remiderlog->customer_id = $remidata->customer_id;
            //                 $remiderlog->business_id = $remidata->business_id;
            //                 $remiderlog->send_date = $dateset;
            //                 $remiderlog->send_time = $remidata->reminder_time;
            //                 $remiderlog->reminder_type  = $remidata->reminder_type ?? "";
            //                 $remiderlog->save();
            //             }

            //     }else if($remidata->repeat_type=="Weekly"){

            //             for ($i=0; $i < 7 ; $i++) {
            //                 $dateset = date('Y-m-d', strtotime($remidata->reminder_date . " +".$i." week"));
            //                 $remiderlog = new Reminderlogs;
            //                 $remiderlog->reminder_id = $remidata->id;
            //                 $remiderlog->user_id = $remidata->user_id;
            //                 $remiderlog->customer_id = $remidata->customer_id;
            //                 $remiderlog->business_id = $remidata->business_id;
            //                 $remiderlog->send_date = $dateset;
            //                 $remiderlog->send_time = $remidata->reminder_time;
            //                 $remiderlog->reminder_type  = $remidata->reminder_type ?? "";
            //                 $remiderlog->save();
            //             }

            //     }else if($remidata->repeat_type=="Monthly"){
            //             for ($i=0; $i < 4 ; $i++) {
            //                 $dateset = date('Y-m-d', strtotime($remidata->reminder_date . " +".$i." months"));
            //                 $remiderlog = new Reminderlogs;
            //                 $remiderlog->reminder_id = $remidata->id;
            //                 $remiderlog->user_id = $remidata->user_id;
            //                 $remiderlog->customer_id = $remidata->customer_id;
            //                 $remiderlog->business_id = $remidata->business_id;
            //                 $remiderlog->send_date = $dateset;
            //                 $remiderlog->send_time = $remidata->reminder_time;
            //                 $remiderlog->reminder_type  = $remidata->reminder_type ?? "";
            //                 $remiderlog->save();
            //             }
            //     }else if($remidata->repeat_type=="Every 3 Months"){
            //             for ($i=0; $i < 4 ; $i++) {
            //                 $iset=$i;
            //                 if($i == 1){
            //                 $iset=3;
            //                 }else if($i == 2){
            //                 $iset=6;
            //                 }else if($i == 3){
            //                 $iset=9;
            //                 }
            //                 $dateset = date('Y-m-d', strtotime($remidata->reminder_date . " +".$iset." months"));
            //                 $remiderlog = new Reminderlogs;
            //                 $remiderlog->reminder_id = $remidata->id;
            //                 $remiderlog->user_id = $remidata->user_id;
            //                 $remiderlog->customer_id = $remidata->customer_id;
            //                 $remiderlog->business_id = $remidata->business_id;
            //                 $remiderlog->send_date = $dateset;
            //                 $remiderlog->send_time = $remidata->reminder_time;
            //                 $remiderlog->reminder_type  = $remidata->reminder_type ?? "";
            //                 $remiderlog->save();
            //             }
            //     }else if($remidata->repeat_type=="Every 6 Months"){
            //             for ($i=0; $i < 4 ; $i++) {
            //                 $iset=$i;
            //                 if($i == 1){
            //                 $iset=6;
            //                 }else if($i == 2){
            //                 $iset=12;
            //                 }else if($i == 3){
            //                 $iset=18;
            //                 }
            //                 $dateset = date('Y-m-d', strtotime($remidata->reminder_date . " +".$iset." months"));
            //                 $remiderlog = new Reminderlogs;
            //                 $remiderlog->reminder_id = $remidata->id;
            //                 $remiderlog->user_id = $remidata->user_id;
            //                 $remiderlog->customer_id = $remidata->customer_id;
            //                 $remiderlog->business_id = $remidata->business_id;
            //                 $remiderlog->send_date = $dateset;
            //                 $remiderlog->send_time = $remidata->reminder_time;
            //                 $remiderlog->reminder_type  = $remidata->reminder_type ?? "";
            //                 $remiderlog->save();
            //             }
            //     }else if($remidata->repeat_type=="Yearly"){
            //             for ($i=0; $i < 4 ; $i++) {
            //                 $dateset = date('Y-m-d', strtotime($remidata->reminder_date . " +".$i." years"));
            //                 $remiderlog = new Reminderlogs;
            //                 $remiderlog->reminder_id = $remidata->id;
            //                 $remiderlog->user_id = $remidata->user_id;
            //                 $remiderlog->customer_id = $remidata->customer_id;
            //                 $remiderlog->business_id = $remidata->business_id;
            //                 $remiderlog->send_date = $dateset;
            //                 $remiderlog->send_time = $remidata->reminder_time;
            //                 $remiderlog->reminder_type  = $remidata->reminder_type ?? "";
            //                 $remiderlog->save();
            //             }
            //     }else if($remidata->repeat_type=="Custom"){
            //         if($remidata->frequency == "Hourly") {
            //             $remindertime = 24 - date('H',strtotime($remidata->reminder_time));
            //             for ($i=0; $i < $remindertime ; $i++) {
            //                 $timeset = date('H:i:s', strtotime($remidata->reminder_time . " +".$i." hours"));
            //                 $remiderlog = new Reminderlogs;
            //                 $remiderlog->reminder_id = $remidata->id;
            //                 $remiderlog->user_id = $remidata->user_id;
            //                 $remiderlog->customer_id = $remidata->customer_id;
            //                 $remiderlog->business_id = $remidata->business_id;
            //                 $remiderlog->send_date = $remidata->reminder_date;
            //                 $remiderlog->send_time = $timeset;
            //                 $remiderlog->reminder_type  = $remidata->reminder_type ?? "";
            //                 $remiderlog->save();
            //             }
            //         }else if($remidata->frequency=="Daily"){

            //             for ($i=0; $i < 7 ; $i++) {
            //                 $dateset = date('Y-m-d', strtotime($remidata->reminder_date . " +".$i." days"));
            //                 $remiderlog = new Reminderlogs;
            //                 $remiderlog->reminder_id = $remidata->id;
            //                 $remiderlog->user_id = $remidata->user_id;
            //                 $remiderlog->customer_id = $remidata->customer_id;
            //                 $remiderlog->business_id = $remidata->business_id;
            //                 $remiderlog->send_date = $dateset;
            //                 $remiderlog->send_time = $remidata->reminder_time;
            //                 $remiderlog->reminder_type  = $remidata->reminder_type ?? "";
            //                 $remiderlog->save();
            //             }

            //         } else if($remidata->frequency=="Monthly"){
            //             for ($i=0; $i < 4 ; $i++) {
            //                 $dateset = date('Y-m-d', strtotime($remidata->reminder_date . " +".$i." months"));
            //                 $remiderlog = new Reminderlogs;
            //                 $remiderlog->reminder_id = $remidata->id;
            //                 $remiderlog->user_id = $remidata->user_id;
            //                 $remiderlog->customer_id = $remidata->customer_id;
            //                 $remiderlog->business_id = $remidata->business_id;
            //                 $remiderlog->send_date = $dateset;
            //                 $remiderlog->send_time = $remidata->reminder_time;
            //                 $remiderlog->reminder_type  = $remidata->reminder_type ?? "";
            //                 $remiderlog->save();
            //             }
            //         } else if($remidata->frequency=="Yearly"){
            //         for ($i=0; $i < 4 ; $i++) {
            //             $dateset = date('Y-m-d', strtotime($remidata->reminder_date . " +".$i." years"));
            //             $remiderlog = new Reminderlogs;
            //             $remiderlog->reminder_id = $remidata->id;
            //             $remiderlog->user_id = $remidata->user_id;
            //             $remiderlog->customer_id = $remidata->customer_id;
            //             $remiderlog->business_id = $remidata->business_id;
            //             $remiderlog->send_date = $dateset;
            //             $remiderlog->send_time = $remidata->reminder_time;
            //             $remiderlog->reminder_type  = $remidata->reminder_type ?? "";
            //             $remiderlog->save();
            //         }
            //         } else {
            //                 $remiderlog = new Reminderlogs;
            //                 $remiderlog->reminder_id = $remidata->id;
            //                 $remiderlog->user_id = $remidata->user_id;
            //                 $remiderlog->customer_id = $remidata->customer_id;
            //                 $remiderlog->business_id = $remidata->business_id;
            //                 $remiderlog->send_date = $remidata->reminder_date;
            //                 $remiderlog->send_time = $remidata->reminder_time;
            //                 $remiderlog->reminder_type  = $remidata->reminder_type ?? "";
            //                 $remiderlog->save();
            //         }
            //     }
            // }

            if (isset($remidata)) {
                $repeatType = $remidata->repeat_type;

                $frequencyMap = [
                    "Never" => 1,
                    "Hourly" => 24 - date('H', strtotime($remidata->reminder_time)),
                    "Daily" => 7,
                    "Weekly" => 7,
                    "Monthly" => 4,
                    "Every 3 Months" => 4,
                    "Every 6 Months" => 4,
                    "Yearly" => 4,
                    "Custom" => function ($frequency) {
                        switch ($frequency) {
                            case "Hourly":
                                return 24 - date('H', strtotime(now())); // Remaining hours in the day
                            case "Daily":
                                return 7; // Example: 7 days
                            case "Monthly":
                                return 4; // Example: 4 months
                            case "Yearly":
                                return 4; // Example: 4 years
                            default:
                                return 1; // Default to a single iteration
                        }
                    }
                ];

                $iterations = is_callable($frequencyMap[$repeatType]) ? $frequencyMap[$repeatType]($remidata->frequency ?? "") : ($frequencyMap[$repeatType] ?? 1);

                $getDateIncrement = function ($type, $i) use ($remidata) {
                    switch ($type) {
                        case "Hourly":
                            return ['date' => $remidata->reminder_date, 'time' => date('H:i:s', strtotime($remidata->reminder_time . " +$i hours"))];
                        case "Daily":
                            return ['date' => date('Y-m-d', strtotime($remidata->reminder_date . " +$i days")), 'time' => $remidata->reminder_time];
                        case "Weekly":
                            return ['date' => date('Y-m-d', strtotime($remidata->reminder_date . " +$i week")), 'time' => $remidata->reminder_time];
                        case "Monthly":
                            return ['date' => date('Y-m-d', strtotime($remidata->reminder_date . " +$i months")), 'time' => $remidata->reminder_time];
                        case "Every 3 Months":
                            $monthIncrement = $i * 3;
                            return ['date' => date('Y-m-d', strtotime($remidata->reminder_date . " +$monthIncrement months")), 'time' => $remidata->reminder_time];
                        case "Every 6 Months":
                            $monthIncrement = $i * 6;
                            return ['date' => date('Y-m-d', strtotime($remidata->reminder_date . " +$monthIncrement months")), 'time' => $remidata->reminder_time];
                        case "Yearly":
                            return ['date' => date('Y-m-d', strtotime($remidata->reminder_date . " +$i years")), 'time' => $remidata->reminder_time];
                        case "Custom":
                            if ($remidata->frequency == "Hourly") {
                                return ['date' => $remidata->reminder_date, 'time' => date('H:i:s', strtotime($remidata->reminder_time . " +$i hours"))];
                            } elseif ($remidata->frequency == "Daily") {
                                return ['date' => date('Y-m-d', strtotime($remidata->reminder_date . " +$i days")), 'time' => $remidata->reminder_time];
                            } elseif ($remidata->frequency == "Monthly") {
                                return ['date' => date('Y-m-d', strtotime($remidata->reminder_date . " +$i months")), 'time' => $remidata->reminder_time];
                            } elseif ($remidata->frequency == "Yearly") {
                                return ['date' => date('Y-m-d', strtotime($remidata->reminder_date . " +$i years")), 'time' => $remidata->reminder_time];
                            }
                            return ['date' => $remidata->reminder_date, 'time' => $remidata->reminder_time];
                        default:
                            return ['date' => $remidata->reminder_date, 'time' => $remidata->reminder_time];
                    }
                };

                for ($i = 0; $i < $iterations; $i++) {
                    $dateIncrement = $getDateIncrement($repeatType, $i);

                    $reminderLog = new Reminderlogs();
                    $reminderLog->reminder_id = $remidata->id;
                    $reminderLog->user_id = $remidata->user_id;
                    $reminderLog->customer_id = $remidata->customer_id;
                    $reminderLog->business_id = $remidata->business_id;
                    $reminderLog->send_date = $dateIncrement['date'];
                    $reminderLog->send_time = $dateIncrement['time'];
                    $reminderLog->is_call = $remidata->is_call;
                    $reminderLog->is_sms = $remidata->is_sms;
                    $reminderLog->is_email = $remidata->is_email;
                    $reminderLog->is_whatsapp = $remidata->is_whatsapp;
                    $reminderLog->is_flashsms = $remidata->is_flashsms ?? false;
                    $reminderLog->save();
                }
            }

            return ResponseAPI(true,"Stored Reminderlog Succesfull", "", array(), 200);

        } catch (\Throwable $th) {
            return ResponseAPI(false,'Something went Wrong.',"",array(),401);
        }
     }


    public function SMSorCALLText(Request $request)
    {
        try {
            $userdata = User::where('id', $request->user_id)->first();
            if($userdata == Null){
                return ResponseAPI(false,'User not found.',"",array(),401);
            }
            $customer = Customer::where('id', $request->customer_id)->first();
            // dd($customer);
            if($customer == Null){
                return ResponseAPI(false,'Customer not found.',"",array(),401);
            }
            $business = Business::where('id', $customer['business_id'])->first();
            if(isset($customer->phone) && isset($customer->name) && isset($customer->balance)){

                $amount = str_replace('-', '', $customer->balance);
                $native_lang = $customer->native_language;
                $name = $customer->name;
                $amount = $customer->balance;
                $sname = $business->bus_name;

                switch ($native_lang) {
                    case 'BN':
                        // Success
                        $msg = "হ্যালো ".$name.", আপনার ".$sname." এর কাছে Rs.".$amount." টাকা বকেয়া রয়েছে। দয়া করে এটি যত দ্রুত%0Aসম্ভব পরিশোধ করুন। আপনি যদি ইতিমধ্যে বকেয়া পরিশোধ করে থাকেন, তবে অনুগ্রহ করে এই%0Aবার্তাটি উপেক্ষা করুন। ধন্যবাদ।";
                        break;

                    case 'EN':
                        // Success
                        $msg = "Hello ".$name.", You owe Rs".$amount." to ".$sname.". Please make the payment as soon as%0Apossible. If you have already made the payment, please ignore this message. Thank%0Ayou.";
                        break;

                    case 'GU':
                        // Success
                        $msg = "નમસ્તે ".$name.", તમારા ".$sname." પર Rs".$amount." રૂપિયા બાકી છે. કૃપા કરીને આ રકમ બને તેટલી વહેલી તકે ચૂકવી%0Aદો. જો તમે પહેલેથી જ બાકી ચૂકવણી ચૂકવી દીધું હોય, તો કૃપા કરીને આ સંદેશાને%C2%A0અવગણો. ધન્યવાદ";
                        break;

                    case 'HI':
                        // Success
                        $msg = "नमस्ते ".$name.", आपका ".$sname." पर ".$amount." रुपये बकाया है। कृपया इसका जल्द से जल्द भुगतान करें।%0Aयदि आपने पहले ही बकाया राशि का भुगतान कर दिया है, तो कृपया ध्यान न दें। धन्यवाद।";
                        break;

                    case 'KN':
                        $msg = "ಹಲೋ ".$name.", ನಿಮ್ಮ ".$sname." ಗೆ Rs".$amount." ಬಾಕಿ ಇದೆ. ದಯವಿಟ್ಟು ಇದನ್ನು ಶೀಘ್ರವೇ ಪಾವತಿಸಿ. ನೀವು ಈಗಾಗಲೇ ಬಾಕಿ ಮೊತ್ತವನ್ನು ಪಾವತಿಸಿದರೆ, ದಯವಿಟ್ಟು ಈ ಸಂದೇಶವನ್ನು ಕಡೆಗಣಿಸಿ. ಧನ್ಯವಾದಗಳು।";
                        break;

                    case 'MR':
                        // Success
                        $msg = "नमस्कार ".$name.", आपल्यावर ".$sname." कडे Rs".$amount." बकायाआहे.%0Aकृपया ते लवकरात लवकर भरा. जर आपण आधीच बकाया रक्कम भरली असेल, तर कृपया या संदेशाकडे%0Aदुर्लक्ष करा. धन्यवाद";
                        break;

                    case 'ML':
                        // Success
                        $msg = "ഹലോ 12345, നിങ്ങളുടെ 12345ന് Rs12345 ബാക്കി അടയ്%E2%80%8Cക്കേണ്ട%0Aതുകയുണ്ട്. ദയവായി ഇത് വളരെ അവശ്യമായി ഉടൻ അടയ്ക്കുക.%0Aനിങ്ങൾ ഇതിനകം ബാക്കി തുക അടച്ചിട്ടുണ്ടെങ്കിൽ, ദയവായി ഈ%0Aസന്ദേശം ഉപേക്ഷിക്കൂ. നന്ദി.";
                        break;

                    case 'PA':
                        // Success
                        $msg = "ਨਮਸਕਾਰ ".$name.", ਤੁਹਾਡੇ ".$sname." ਤੇ Rs".$amount." ਰੁਪਏ ਬਕਾਇਆ ਹਨ। ਕਿਰਪਾ ਕਰਕੇ ਇਸਨੂੰ ਜਲਦ ਤੋਂ ਜਲਦ ਅਦਾ%0Aਕਰੋ। ਜੇਕਰ ਤੁਸੀਂ ਪਹਿਲਾਂ ਹੀ ਬਕਾਇਆ ਰਕਮ ਅਦਾ ਕਰ ਚੁੱਕੇ ਹੋ ਤਾਂ ਕਿਰਪਾ ਕਰਕੇ ਇਸ ਸੁਨੇਹੇ ਨੂੰ ਅਣਡਿੱਠਾ ਕਰੋ।%0Aਧੰਨਵਾਦ";
                        break;

                    case 'TA':
                        // Success
                        $msg = "வணக்கம் ".$name.", உங்கள் ".$sname." க்குப் Rs".$amount." பாக்கி உள்ளது. தயவுசெய்து இதை%0Aவிரைவில் செலுத்தவும். நீங்கள் ஏற்கனவே பாக்கி தொகையை செலுத்தியிருந்தால்,%0Aஇந்த செய்தியினை புறக்கணிக்கவும். நன்றி.";
                        break;

                    case 'TE':
                        // Success
                        $msg = "హలో ".$name.", మీరు ".$sname." కు Rs".$amount." బకాయి ఉన్నది. దయచేసి ఇది వీలైనంత%0Aత్వరగా చెల్లించండి. మీరు ఇప్పటికే బకాయి చెల్లించివుంటే, ఈ%0Aసందేశాన్ని గమనించకండి. ధన్యవాదాలు.";
                        break;

                    default:
                        $msg = "Hello ".$name.", You owe Rs".$amount." to ".$sname.". Please make the payment as soon as%0Apossible. If you have already made the payment, please ignore this message. Thank%0Ayou.";
                        break;
                }

                // $call="नमस्ते ".$customer->name." आपका ".$business->bus_name." पर ".$amount." रुपये बकाया है. कृपया जल्द से जल्द इसका भुगतान करे! यदि आपने पहले ही बकाया राशि का भुगतान कर दिया है तो कृपया ध्यान न दें धन्यवाद।";
                $call="";

                $data = array('SMS'=>$msg, 'CALL'=>$call);
                return ResponseAPI(true,'Data found',"",$data,201);
            } else {
                return ResponseAPI(false,'Something went Wrong.',"",array(),401);
            }
            return ResponseAPI(false,'Something went Wrong.',"",array(),401);
        } catch (\Throwable $th) {
            return ResponseAPI(false,'Something went Wrong.',"",array(),401);
        }
   }

    public function getaudio($message){
        $txt=$message;
        $txt=htmlspecialchars($txt);
        $txt=rawurlencode($txt);
        return "https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=".$txt."&tl=en-IN";
    }

}
