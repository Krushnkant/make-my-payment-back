<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
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
use Illuminate\Support\Facades\Mail;

class CallApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'call:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call an API using Cron';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    // public function handle()
    // {
    //     // \Log::info("Cron is working fine!");
    //     // Make the API request using Laravel's HTTP client
    //     $response = Http::timeout(300)->get('https://makemypayment.co.in/admin/server.php/api/reminder_cron');
    //     \Log::info("Response is: ". $data);
    //     // Handle the API response
    //     if ($response->successful()) {
    //         // API request was successful, process the response
    //         $data = $response->json();
    //        \Log::info("Response is fine!". $data);
    //         // ... process the data as needed
    //     } else {
    //         // API request failed, handle the error
    //         $error = $response->json();
    //         // ... handle the error as needed
    //     }
    // }

    public function handle()
    {
        $currentHour = now()->format('H');
        if ($currentHour < 9 || $currentHour >= 21) {
            return 0; // No processing needed outside the allowed hours
        }

        try {
            $current_date = Carbon::today()->toDateString();
            $now = Carbon::now();
            $last_15_minutes = $now->copy()->subMinutes(15);

            // Fetch reminders within the time range
            $getreminderlist = Reminderlogs::where('send_date', $current_date)
                ->where('send_time', '>=', $last_15_minutes->format('H:i:s'))
                ->where('send_time', '<=', $now->format('H:i:s'))
                ->get();

            if ($getreminderlist->count() > 0) {
                foreach ($getreminderlist as $value) {
                    $reminder_id = $value->reminder_id;

                    // \Log::info("reminder_id: ".$reminder_id);
                    // Process calls 
                    if ($value->is_call == 1 && $value->check_balance($value->user_id) == 1) {
                        $logsToday = getTodayNotificationCount($value->user_id, $value->customer_id, 'CALL');
                        if ($logsToday < getSetting()->daily_call_limit) {
                            $this->SendReminderCall($value);
                            logNotification($value->user_id, $value->customer_id, 'CALL');
                        }
                    }

                    // Process SMS or Flash SMS
                    if (($value->is_sms == 1 || $value->is_flashsms == 1) && $value->check_sms_balance($value->user_id) == 1) {
                        $logsToday = getTodayNotificationCount($value->user_id, $value->customer_id, 'SMS');
                        if ($logsToday < getSetting()->daily_sms_limit) {
                            $this->SendReminderSMS($value);
                            logNotification($value->user_id, $value->customer_id, 'SMS');
                        }
                    }

                    // Process Emails
                    if ($value->is_email == 1) {
                        $logsToday = getTodayNotificationCount($value->user_id, $value->customer_id, 'EMAIL');
                        if ($logsToday < getSetting()->daily_email_limit) {
                            $this->SendReminderEmail($value);
                            logNotification($value->user_id, $value->customer_id, 'EMAIL');
                        }
                    }

                    // Delete processed reminder
                    $value->delete();

                    // Clean up if no reminders are left
                    if (Counterreminder($reminder_id) == 0) {
                        Reminder::where('id', $reminder_id)->delete();
                    }
                }
            }
            return 0; // Success
        } catch (\Throwable $th) {
            \Log::error('Failed to process reminders: ' . $th->getMessage()); // Use $th instead of $e
            return 1; // Failure
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
                return 0;
            }

            $customer = Customer::where('id', $remidata->customer_id)->first();
            $business = Business::where('id', $customer['business_id'])->first();

            if($customer->phone == '9999802607' || $customer->phone == '+919999802607'){
                return ResponseAPI(false,'You can not call this number due to being blocked.',"",array(),401);
            }

            // if(isset($customer->phone) && $customer->phone == '9601798557' && isset($customer->name) && isset($customer->balance)){
            if( isset($customer->name) && isset($customer->balance) ){

                $call = SendCall($customer->phone, $customer->name, $customer->balance, $business->bus_name, $customer->native_language);
                // \Log::info("call to: ".$customer->phone.' | Response: '.$call);
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

                    //End send notification to user
                    $notification = new Notification;
                    $notification->user_id = $remidata->user_id;
                    $notification->business_id = $remidata->business_id;
                    $notification->customer_id = $remidata->customer_id;
                    $notification->customer_name = $customer->name;
                    $notification->customer_mobile = $customer->phone;
                    // $notification->customer_email = $customer->email;
                    $notification->title = "Payment Reminder for ".$customer->name;
                    $notification->description = "Payment Reminder Call Is Pending";
                    $notification->type = 'CALL';
                    $notification->status = 0;
                    $notification->call_id=$TagId;
                    if($notification->save()){
                        $userdata->total_call-=1;
                        $userdata->save();
                        // \Log::info("call to: ".$customer->phone.' | Response: '.$call.' | Notification Send Succesfull: ');
                        return 0;
                    }
                }
                return 0;
            }else{
                return 0;
            }
            return 0;
        } catch (\Throwable $th) {
            return 1;
        }
    }

    public function SendReminderSMS($remidata)
    {
        try {
            $status="fail";
            $TagId="";
            $userdata = User::where('id', $remidata->user_id)->first();

            if($userdata == Null){
                return 0;
            }

            $customer = Customer::where('id', $remidata->customer_id)->first();
            $business = Business::where('id', $customer['business_id'])->first();

            if(isset($customer->phone) && isset($customer->name) && isset($customer->balance)){

                if($userdata->total_message  > 0){

                    $call = SendSMS($customer->phone,$customer->name,$business->bus_name,$customer->balance,$customer->native_language);
                    // \Log::info("SMS to: ".$customer->phone.' | Response: '.$call);
                    if ($call == false || str_contains($call, "error")) {
                        return 0;
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
                        // $notification->customer_email = $customer->email;
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
                return 0;
            }
            return 0;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public function SendReminderEmail($remidata)
    {
        try {
            $status="fail";
            $TagId="";
            $userdata = User::where('id', $remidata->user_id)->first();

            if($userdata == Null){
                return 0;
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
                // $notification->customer_email = $customer->email;
                $notification->customer_mobile = $customer->phone;
                $notification->title = "Payment Reminder for ".$customer->name;
                $notification->description = "Payment Reminder EMAIL Is Sent";
                $notification->type = "EMAIL";
                $notification->call_id=$TagId ?? "";
                if($notification->save()){
                    return ResponseAPI(true,"Notification Send Succesfull", "", $notification, 200);
                }

            }else{
                return 0;
            }
            return 0;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public function logNotification($userId, $customerId, $type)
    {
        try {
            $today = Carbon::today();

            // Check if a log already exists for today
            $existingLog = DB::table('notifications_log')
                ->where('user_id', $userId)
                ->where('customer_id', $customerId)
                ->where('type', $type)
                ->whereDate('created_at', $today)
                ->first();

            if ($existingLog) {
                // If log exists, increment the count
                DB::table('notifications_log')
                    ->where('id', $existingLog->id)
                    ->increment('count');
            } else {
                // If log doesn't exist, create a new entry
                DB::table('notifications_log')->insert([
                    'user_id' => $userId,
                    'customer_id' => $customerId,
                    'type' => $type,
                    'count' => 1, // Start with count 1
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
