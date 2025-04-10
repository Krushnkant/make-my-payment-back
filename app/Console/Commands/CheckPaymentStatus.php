<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Ixudra\Curl\Facades\Curl;
use App\Models\PhonepayTransaction;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class CheckPaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'call api for check phone pe payment status';

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
    //     $response = Http::get('https://makemypayment.co.in/admin/server.php/api/check-payment-status');

    //     // Handle the API response
    //     if ($response->successful()) {
    //         // API request was successful, process the response
    //         $data = $response->json();
    //         // ... process the data as needed
    //     } else {
    //         // API request failed, handle the error
    //         $error = $response->json();
    //         // ... handle the error as needed
    //     }
    // }

    public function handle()
    {
        try{
 
            $users = PhonepayTransaction::select('*')->where('flag','1')->paginate(50);
           
            foreach ($users as $user) {

                $merchantTransactionId  = $user['merchantTransactionId'];
                $saltKey = '6fbca744-423f-4ebe-93ab-0882f48f7b9b';
                $saltIndex = 1;
                //$merchantTransactionId  =   'TRAVONLINE_655c5503a65d1';
                $string = '/v3/transaction/TRAVONLINE/'.$merchantTransactionId.'/status'.$saltKey;
                
                $sha256 = hash('sha256',$string);
            
            
                // Make the API request
                $finalXHeader = $sha256.'###'.$saltIndex;
                $client = new \GuzzleHttp\Client();
                $response = $client->request('GET', 'https://mercury-t2.phonepe.com/v3/transaction/TRAVONLINE/'.$merchantTransactionId.'/status', [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'X-VERIFY' => $finalXHeader,
                        'accept' => 'application/json',
                    ],
                ]);
    
           $result   =    $response->getBody()->getContents();
        
            $resultarr = json_decode($result);
            if(isset($resultarr) && $resultarr->data->paymentState == 'PENDING')
            {
               $storeGetData = array(
                   'flag'=>1,
                   'status' =>isset($resultarr) ? $resultarr->data->paymentState: ""
               );
           }elseif(isset($resultarr) && $resultarr->data->paymentState == 'COMPLETED'){
               $storeGetData = array(
                   'status' =>isset($resultarr) ? $resultarr->data->paymentState: ""
               );
              
               $userData   =   PhonepayTransaction::where('merchantTransactionId',$merchantTransactionId)->latest()->first();
               if(isset($userData) && !empty($userData))
               {
                   $getUserData = User::select('*')->where(['id'=>$userData['merchantUserId']])->latest()->first();
                   
                   $totalCalls = $getUserData['total_call'];
                   $total_message = $getUserData['total_message'];
    
                  
               }
    
               $amount = isset($resultarr->data->amount) ? number_format($resultarr->data->amount, 2, '.', '') : '0.00';
    
               $package = Package::where(['status'=>1,'price'=>$amount])->first();
              
              if(isset($package) && !empty($package))
              {
               $packageMessage =   $package['package_message'];
               $packageCalls   =  $package['package_calls'];
              }
              else{
               $packageMessage =   0;
               $packageCalls   =  0;
              }
               
    
               $updateUsersPackages    = array(
    
                   'total_call'=>$packageCalls+$totalCalls,
                   'total_message'=>$packageMessage+$total_message
    
               );  
               User::where('id',$userData['merchantUserId'])->update($updateUsersPackages);
    
    
           }
           else{
               $storeGetData = array(
                   'status' =>isset($resultarr) ? $resultarr->data->paymentState: ""
               );
           }
    
            PhonepayTransaction::where('merchantTransactionId',$merchantTransactionId)->update($storeGetData);
        
            if ($response->getStatusCode()==200) {
                // Process the response data as needed
                return 1;
            } else {
                // Handle the case where the request was not successful
                return 0;
            }
        }
       }catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle exceptions, e.g., connection issues, timeouts, etc.
            return 0;
        }
    }
}
