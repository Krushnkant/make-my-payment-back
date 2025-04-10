<?php
use App\Models\Reminderlogs;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

//response api
if (! function_exists('ResponseAPI')) {
    function ResponseAPI($status=true,$message="successed data",$error=array(),$data=array(),$responsecode=200, $flag= "", $token="")
    {
        return response()->json(['success' => $status, 'message' => $message,'error'=>$error,'data'=>$data, 'flag'=>$flag, 'token'=>$token],$responsecode);
    }
}

if (! function_exists('areActiveRoutes')) {
    function areActiveRoutes(Array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }

    }
}

if (! function_exists('FinalPrice')) {
    function FinalPrice($price)
    {
        return '₹ '. $price;
    }
}

if (! function_exists('SendCall')) {
    function SendCall($to, $name, $amount, $sname=null, $native_lang="EN")
    {
        if(strpos($to, '+91') !== false){
            $to = substr($to, 3);
        }
        $amount = str_replace('-', '', $amount);
        $name = str_replace(' ', '%20', $name);
        $sname = str_replace(' ', '%20', $sname);
        
        $username = '';
        $password = '';
        $obd_type = '';
        // Static array of username-password pairs

        switch ($native_lang) {
            case 'BN':
                $obd_type = "PRP_PAYMENT_BN";
                $credentials = [
                    ['username' => 'makemypayment', 'password' => 'makemypayment', 'voiceid' => '39766'],
                    // ['username' => 'makemypayment2', 'password' => 'makemypayment2', 'voiceid' => '38625'],
                    ['username' => 'makemypayment3', 'password' => 'makemypayment3', 'voiceid' => '39781'],
                    ['username' => 'makemypayment4', 'password' => 'makemypayment4', 'voiceid' => '39744'],
                    ['username' => 'makemypayment5', 'password' => 'makemypayment5', 'voiceid' => '39758'],
                    ['username' => 'makemypayment6', 'password' => 'makemypayment6', 'voiceid' => '39792'],
                    ['username' => 'makemypayment7', 'password' => 'makemypayment7', 'voiceid' => '39802'],
                    ['username' => 'makemypayment8', 'password' => 'makemypayment8', 'voiceid' => '39811'],
                    ['username' => 'makemypayment9', 'password' => 'makemypayment9', 'voiceid' => '39820'],
                    ['username' => 'makemypayment10', 'password' => 'makemypayment10', 'voiceid' => '39829'],
                ];
                break;

            case 'EN':
                $obd_type = "PRP_PAYMENT_EN";
                $credentials = [
                    ['username' => 'makemypayment', 'password' => 'makemypayment', 'voiceid' => '39754'],
                    // ['username' => 'makemypayment2', 'password' => 'makemypayment2', 'voiceid' => '38625'],
                    ['username' => 'makemypayment3', 'password' => 'makemypayment3', 'voiceid' => '39780'],
                    ['username' => 'makemypayment4', 'password' => 'makemypayment4', 'voiceid' => '39743'],
                    ['username' => 'makemypayment5', 'password' => 'makemypayment5', 'voiceid' => '39757'],
                    ['username' => 'makemypayment6', 'password' => 'makemypayment6', 'voiceid' => '39791'],
                    ['username' => 'makemypayment7', 'password' => 'makemypayment7', 'voiceid' => '39801'],
                    ['username' => 'makemypayment8', 'password' => 'makemypayment8', 'voiceid' => '39810'],
                    ['username' => 'makemypayment9', 'password' => 'makemypayment9', 'voiceid' => '39819'],
                    ['username' => 'makemypayment10', 'password' => 'makemypayment10', 'voiceid' => '39828'],
                ];
                break;

            case 'GU':
                $obd_type = "PRP_PAYMENT_GU";
                $credentials = [
                    ['username' => 'makemypayment', 'password' => 'makemypayment', 'voiceid' => '39779'],
                    // ['username' => 'makemypayment2', 'password' => 'makemypayment2', 'voiceid' => '38625'],
                    ['username' => 'makemypayment3', 'password' => 'makemypayment3', 'voiceid' => '39790'],
                    ['username' => 'makemypayment4', 'password' => 'makemypayment4', 'voiceid' => '39752'],
                    ['username' => 'makemypayment5', 'password' => 'makemypayment5', 'voiceid' => '39765'],
                    ['username' => 'makemypayment6', 'password' => 'makemypayment6', 'voiceid' => '39800'],
                    ['username' => 'makemypayment7', 'password' => 'makemypayment7', 'voiceid' => '39809'],
                    ['username' => 'makemypayment8', 'password' => 'makemypayment8', 'voiceid' => '39818'],
                    ['username' => 'makemypayment9', 'password' => 'makemypayment9', 'voiceid' => '39827'],
                    ['username' => 'makemypayment10', 'password' => 'makemypayment10', 'voiceid' => '39839'],
                ];
                break;

            // case 'HI':
            //     $obd_type = "PRP_PAYMENT_HI";
            //     break;

            case 'KN':
                $obd_type = "PRP_PAYMENT_KN";
                $credentials = [
                    ['username' => 'makemypayment', 'password' => 'makemypayment', 'voiceid' => '39778'],
                    // ['username' => 'makemypayment2', 'password' => 'makemypayment2', 'voiceid' => '38625'],
                    ['username' => 'makemypayment3', 'password' => 'makemypayment3', 'voiceid' => '39789'],
                    ['username' => 'makemypayment4', 'password' => 'makemypayment4', 'voiceid' => '39751'],
                    ['username' => 'makemypayment5', 'password' => 'makemypayment5', 'voiceid' => '39764'],
                    ['username' => 'makemypayment6', 'password' => 'makemypayment6', 'voiceid' => '39799'],
                    ['username' => 'makemypayment7', 'password' => 'makemypayment7', 'voiceid' => '39808'],
                    ['username' => 'makemypayment8', 'password' => 'makemypayment8', 'voiceid' => '39817'],
                    ['username' => 'makemypayment9', 'password' => 'makemypayment9', 'voiceid' => '39826'],
                    ['username' => 'makemypayment10', 'password' => 'makemypayment10', 'voiceid' => '39838'],
                ];
                break;

            case 'MR':
                $obd_type = "PRP_PAYMENT_MR";
                $credentials = [
                    ['username' => 'makemypayment', 'password' => 'makemypayment', 'voiceid' => '39767'],
                    // ['username' => 'makemypayment2', 'password' => 'makemypayment2', 'voiceid' => '38625'],
                    ['username' => 'makemypayment3', 'password' => 'makemypayment3', 'voiceid' => '39782'],
                    ['username' => 'makemypayment4', 'password' => 'makemypayment4', 'voiceid' => '39746'],
                    ['username' => 'makemypayment5', 'password' => 'makemypayment5', 'voiceid' => '39759'],
                    ['username' => 'makemypayment6', 'password' => 'makemypayment6', 'voiceid' => '39793'],
                    ['username' => 'makemypayment7', 'password' => 'makemypayment7', 'voiceid' => '39803'],
                    ['username' => 'makemypayment8', 'password' => 'makemypayment8', 'voiceid' => '39812'],
                    ['username' => 'makemypayment9', 'password' => 'makemypayment9', 'voiceid' => '39821'],
                    ['username' => 'makemypayment10', 'password' => 'makemypayment10', 'voiceid' => '39832'],
                ];
                break;

            case 'ML':
                $obd_type = "PRP_PAYMENT_ML";
                $credentials = [
                    ['username' => 'makemypayment', 'password' => 'makemypayment', 'voiceid' => '39768'],
                    // ['username' => 'makemypayment2', 'password' => 'makemypayment2', 'voiceid' => '38625'],
                    ['username' => 'makemypayment3', 'password' => 'makemypayment3', 'voiceid' => '39783'],
                    ['username' => 'makemypayment4', 'password' => 'makemypayment4', 'voiceid' => '39747'],
                    ['username' => 'makemypayment5', 'password' => 'makemypayment5', 'voiceid' => '39760'],
                    ['username' => 'makemypayment6', 'password' => 'makemypayment6', 'voiceid' => '39794'],
                    ['username' => 'makemypayment7', 'password' => 'makemypayment7', 'voiceid' => '39804'],
                    ['username' => 'makemypayment8', 'password' => 'makemypayment8', 'voiceid' => '39813'],
                    ['username' => 'makemypayment9', 'password' => 'makemypayment9', 'voiceid' => '39822'],
                    ['username' => 'makemypayment10', 'password' => 'makemypayment10', 'voiceid' => '39833'],
                ];
                break;
            
            case 'PA':
                $obd_type = "PRP_PAYMENT_PA";
                $credentials = [
                    ['username' => 'makemypayment', 'password' => 'makemypayment', 'voiceid' => '39778'],
                    // ['username' => 'makemypayment2', 'password' => 'makemypayment2', 'voiceid' => '38625'],
                    ['username' => 'makemypayment3', 'password' => 'makemypayment3', 'voiceid' => '39786'],
                    ['username' => 'makemypayment4', 'password' => 'makemypayment4', 'voiceid' => '39748'],
                    ['username' => 'makemypayment5', 'password' => 'makemypayment5', 'voiceid' => '39761'],
                    ['username' => 'makemypayment6', 'password' => 'makemypayment6', 'voiceid' => '39795'],
                    ['username' => 'makemypayment7', 'password' => 'makemypayment7', 'voiceid' => '39805'],
                    ['username' => 'makemypayment8', 'password' => 'makemypayment8', 'voiceid' => '39814'],
                    ['username' => 'makemypayment9', 'password' => 'makemypayment9', 'voiceid' => '39823'],
                    ['username' => 'makemypayment10', 'password' => 'makemypayment10', 'voiceid' => '39834'],
                ];
                break;

            case 'TA':
                $obd_type = "PRP_PAYMENT_TA";
                $credentials = [
                    ['username' => 'makemypayment', 'password' => 'makemypayment', 'voiceid' => '39776'],
                    // ['username' => 'makemypayment2', 'password' => 'makemypayment2', 'voiceid' => '38625'],
                    ['username' => 'makemypayment3', 'password' => 'makemypayment3', 'voiceid' => '39787'],
                    ['username' => 'makemypayment4', 'password' => 'makemypayment4', 'voiceid' => '39749'],
                    ['username' => 'makemypayment5', 'password' => 'makemypayment5', 'voiceid' => '39762'],
                    ['username' => 'makemypayment6', 'password' => 'makemypayment6', 'voiceid' => '39796'],
                    ['username' => 'makemypayment7', 'password' => 'makemypayment7', 'voiceid' => '39806'],
                    ['username' => 'makemypayment8', 'password' => 'makemypayment8', 'voiceid' => '39815'],
                    ['username' => 'makemypayment9', 'password' => 'makemypayment9', 'voiceid' => '39824'],
                    ['username' => 'makemypayment10', 'password' => 'makemypayment10', 'voiceid' => '39836'],
                ];
                break;

            case 'TE':
                $obd_type = "PRP_PAYMENT_TE";
                $credentials = [
                    ['username' => 'makemypayment', 'password' => 'makemypayment', 'voiceid' => '39777'],
                    // ['username' => 'makemypayment2', 'password' => 'makemypayment2', 'voiceid' => '38625'],
                    ['username' => 'makemypayment3', 'password' => 'makemypayment3', 'voiceid' => '39788'],
                    ['username' => 'makemypayment4', 'password' => 'makemypayment4', 'voiceid' => '39750'],
                    ['username' => 'makemypayment5', 'password' => 'makemypayment5', 'voiceid' => '39763'],
                    ['username' => 'makemypayment6', 'password' => 'makemypayment6', 'voiceid' => '39798'],
                    ['username' => 'makemypayment7', 'password' => 'makemypayment7', 'voiceid' => '39807'],
                    ['username' => 'makemypayment8', 'password' => 'makemypayment8', 'voiceid' => '39816'],
                    ['username' => 'makemypayment9', 'password' => 'makemypayment9', 'voiceid' => '39825'],
                    ['username' => 'makemypayment10', 'password' => 'makemypayment10', 'voiceid' => '39837'],
                ];
                break;

            default:
                $obd_type = "PRP_PAYMENT";
                $credentials = [
                    ['username' => 'makemypayment', 'password' => 'makemypayment', 'voiceid' => '38623'],
                    ['username' => 'makemypayment2', 'password' => 'makemypayment2', 'voiceid' => '38625'],
                    ['username' => 'makemypayment3', 'password' => 'makemypayment3', 'voiceid' => '39444'],
                    ['username' => 'makemypayment4', 'password' => 'makemypayment4', 'voiceid' => '39446'],
                    ['username' => 'makemypayment5', 'password' => 'makemypayment5', 'voiceid' => '39449'],
                    ['username' => 'makemypayment6', 'password' => 'makemypayment6', 'voiceid' => '39450'],
                    ['username' => 'makemypayment7', 'password' => 'makemypayment7', 'voiceid' => '39451'],
                    ['username' => 'makemypayment8', 'password' => 'makemypayment8', 'voiceid' => '39452'],
                    ['username' => 'makemypayment9', 'password' => 'makemypayment9', 'voiceid' => '39453'],
                    ['username' => 'makemypayment10', 'password' => 'makemypayment10', 'voiceid' => '39454'],
                ];
                break;
        }

        // Get a random pair from the array
        $randomCredential = $credentials[array_rand($credentials)];

        // API endpoint
        // $url = 'https://prpsms.co.in/OBD_REST_API/api/OBD_Rest/SINGLE_CALLWithCustCall_ID';

        // Data to be sent in JSON format
        $data = [
            "UserName" => $randomCredential["username"],
            "Password" => $randomCredential["password"],
            "VoiceId" => $randomCredential["voiceid"],
            "MSISDN" => $to,
            "CustCall_ID" => "173",
            "PARAM1" => $name,
            "PARAM2" => $sname,
            "PARAM3" => $amount,
            "PARAM4" => "",
            "OBD_TYPE" => $obd_type,
            "DTMF" => "",
            "THKS_VOX_ID" => ""
        ];
        // Initialize cURL
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://103.132.146.183/OBD_REST_API/api/OBD_Rest/SINGLE_CALLWithCustCall_ID',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
              'Content-Type: application/json'
            ),
        ));

        // Execute the request
        $response = curl_exec($curl);
        // dd($response);
        // Check for errors
        if (curl_errno($curl)) {
            return curl_error($curl);
        } else {
            // Output the response
           // Close cURL session
            curl_close($curl);
            return $response;

        }


    }
}

if (! function_exists('SendSMS')) {
    function SendSMS($to, $name, $sname, $amount, $native_lang)
    {
        if(strpos($to, '+91') !== false){
            $to = substr($to, 3);
        }
        $amount = str_replace('-', '', $amount);
        $website_url = 'https://makemypayment.co.in/';
        $msg = '';
        $unicodeVar = '';
        $ansiCode = "%0A";
        switch ($native_lang) {
            case 'BN':
                // Success
                $msg = "হ্যালো ".$name.", আপনার ".$sname." এর কাছে Rs.".$amount." টাকা বকেয়া রয়েছে। দয়া করে এটি যত দ্রুত%0Aসম্ভব পরিশোধ করুন। আপনি যদি ইতিমধ্যে বকেয়া পরিশোধ করে থাকেন, তবে অনুগ্রহ করে এই%0Aবার্তাটি উপেক্ষা করুন। ধন্যবাদ।";
                $msg = str_replace(' ', '%20', $msg);
                $unicodeVar = "&unicode=1";
                break;

            case 'EN':
                // Success
                $msg = "Hello ".$name.", You owe Rs".$amount." to ".$sname.". Please make the payment as soon as%0Apossible. If you have already made the payment, please ignore this message. Thank%0Ayou.";
                $msg = str_replace(' ', '%20', $msg);
                break;

            case 'GU':
                // Success
                $msg = "નમસ્તે ".$name.", તમારા ".$sname." પર Rs".$amount." રૂપિયા બાકી છે. કૃપા કરીને આ રકમ બને તેટલી વહેલી તકે ચૂકવી%0Aદો. જો તમે પહેલેથી જ બાકી ચૂકવણી ચૂકવી દીધું હોય, તો કૃપા કરીને આ સંદેશાને%C2%A0અવગણો. ધન્યવાદ";
                $msg = str_replace(' ', '%20', $msg);
                $unicodeVar = "&unicode=1";
                break;

            case 'HI':
                // Success
                $msg = "नमस्ते ".$name.", आपका ".$sname." पर ".$amount." रुपये बकाया है। कृपया इसका जल्द से जल्द भुगतान करें।%0Aयदि आपने पहले ही बकाया राशि का भुगतान कर दिया है, तो कृपया ध्यान न दें। धन्यवाद।";
                $msg = str_replace(' ', '%20', $msg);
                $unicodeVar = "&unicode=1";
                break;

            case 'KN':
                $msg = "ಹಲೋ ".$name.", ನಿಮ್ಮ ".$sname." ಗೆ Rs".$amount." ಬಾಕಿ ಇದೆ. ದಯವಿಟ್ಟು ಇದನ್ನು ಶೀಘ್ರವೇ ಪಾವತಿಸಿ. ನೀವು ಈಗಾಗಲೇ ಬಾಕಿ ಮೊತ್ತವನ್ನು ಪಾವತಿಸಿದರೆ, ದಯವಿಟ್ಟು ಈ ಸಂದೇಶವನ್ನು ಕಡೆಗಣಿಸಿ. ಧನ್ಯವಾದಗಳು।";
                $msg = str_replace(' ', '%20', $msg);
                $unicodeVar = "&unicode=1";
                $ansiCode = "%20";
                break;

            case 'MR':
                // Success
                $msg = "नमस्कार ".$name.", आपल्यावर ".$sname." कडे Rs".$amount." बकायाआहे.%0Aकृपया ते लवकरात लवकर भरा. जर आपण आधीच बकाया रक्कम भरली असेल, तर कृपया या संदेशाकडे%0Aदुर्लक्ष करा. धन्यवाद";
                $msg = str_replace(' ', '%20', $msg);
                $unicodeVar = "&unicode=1";
                break;

            case 'ML':
                // Success
                $msg = "ഹലോ 12345, നിങ്ങളുടെ 12345ന് Rs12345 ബാക്കി അടയ്%E2%80%8Cക്കേണ്ട%0Aതുകയുണ്ട്. ദയവായി ഇത് വളരെ അവശ്യമായി ഉടൻ അടയ്ക്കുക.%0Aനിങ്ങൾ ഇതിനകം ബാക്കി തുക അടച്ചിട്ടുണ്ടെങ്കിൽ, ദയവായി ഈ%0Aസന്ദേശം ഉപേക്ഷിക്കൂ. നന്ദി.";
                $msg = str_replace(' ', '%20', $msg);
                $unicodeVar = "&unicode=1";
                break;
            
            case 'PA':
                // Success
                $msg = "ਨਮਸਕਾਰ ".$name.", ਤੁਹਾਡੇ ".$sname." ਤੇ Rs".$amount." ਰੁਪਏ ਬਕਾਇਆ ਹਨ। ਕਿਰਪਾ ਕਰਕੇ ਇਸਨੂੰ ਜਲਦ ਤੋਂ ਜਲਦ ਅਦਾ%0Aਕਰੋ। ਜੇਕਰ ਤੁਸੀਂ ਪਹਿਲਾਂ ਹੀ ਬਕਾਇਆ ਰਕਮ ਅਦਾ ਕਰ ਚੁੱਕੇ ਹੋ ਤਾਂ ਕਿਰਪਾ ਕਰਕੇ ਇਸ ਸੁਨੇਹੇ ਨੂੰ ਅਣਡਿੱਠਾ ਕਰੋ।%0Aਧੰਨਵਾਦ";
                $msg = str_replace(' ', '%20', $msg);
                $unicodeVar = "&unicode=1";
                break;

            case 'TA':
                // Success
                $msg = "வணக்கம் ".$name.", உங்கள் ".$sname." க்குப் Rs".$amount." பாக்கி உள்ளது. தயவுசெய்து இதை%0Aவிரைவில் செலுத்தவும். நீங்கள் ஏற்கனவே பாக்கி தொகையை செலுத்தியிருந்தால்,%0Aஇந்த செய்தியினை புறக்கணிக்கவும். நன்றி.";
                $msg = str_replace(' ', '%20', $msg);
                $unicodeVar = "&unicode=1";
                break;

            case 'TE':
                // Success
                $msg = "హలో ".$name.", మీరు ".$sname." కు Rs".$amount." బకాయి ఉన్నది. దయచేసి ఇది వీలైనంత%0Aత్వరగా చెల్లించండి. మీరు ఇప్పటికే బకాయి చెల్లించివుంటే, ఈ%0Aసందేశాన్ని గమనించకండి. ధన్యవాదాలు.";
                $msg = str_replace(' ', '%20', $msg);
                $unicodeVar = "&unicode=1";
                break;

            default:
                $msg = "Hello ".$name.", You owe Rs".$amount." to ".$sname.". Please make the payment as soon as%0Apossible. If you have already made the payment, please ignore this message. Thank%0Ayou.";
                $msg = str_replace(' ', '%20', $msg);
                break;
        }

        $url = "https://prpsms.co.in/API/SendMsg.aspx?uname=20240345&pass=W999XmmR&send=TRAVNT&dest=".$to."&msg=".$msg.$ansiCode."MakeMyPayment".$unicodeVar;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        curl_close($curl);
        // print_r($url);
        // dd($response);
        return $response;
    }
}

//Send sms if call count less than 5
//send message call and sms less than 5;
if (! function_exists('SendNewCall')) {
    function SendNewCall($to,$message)
    {

        $url ="http://sms.vccagent.com/ApiSmsHttp?UserId=TRAVNT@GMAIL.COM&pwd=pwd2022&Message=".urlencode($message)."&Contacts=".$to."&SenderId=MMPMNT&ServiceName=SMSTRANS&MessageType=2&DLTTemplateId=1707170567569760889";
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output=curl_exec($ch);
        // print_r($output);
        curl_errno($ch);
        curl_close($ch);
        return $output;
    }
}

//send message sms less than 5;
if (! function_exists('SendNewSms')) {
    function SendNewSms($to,$message)
    {

        // $url ="http://sms.vccagent.com/ApiSmsHttp?UserId=TRAVNT@GMAIL.COM&pwd=pwd2022&Message=".urlencode($message)."&Contacts=".$to."&SenderId=MMPMNT&ServiceName=SMSTRANS&MessageType=2&DLTTemplateId=1707170651118814697";
        $url ="https://prpsms.co.in/API/SendMsg.aspx?uname=20240345&pass=W999XmmR&send=TRAVNT&dest=".$to."&msg=".urlencode($message)."&unicode=1";
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output=curl_exec($ch);
        // print_r($output);
        curl_errno($ch);
        curl_close($ch);
        return $output;
    }
}

if (! function_exists('SendOTP')) {
    function SendOTP($to, $otp)
    {
        // $url ="http://sms.vccagent.com/ApiSmsHttp?UserId=TRAVNT@GMAIL.COM&pwd=pwd2022&Message=Dear Customer , Your Repay OTP for login is ".$otp." Note: Please DO NOT SHARE this OTP with anyone. Team Travinities&Contacts=".$to."&SenderId=MMPMNT&ServiceName=SMSTRANS&MessageType=1&DLTTemplateId=1707170782099266656";
        // $url = "http://prpsms.co.in/API/SendMsg.aspx?uname=20240345&pass=W999XmmR&send=MMPMNT&dest=".$to."&msg=Dear Customer , Your MakeMyPayment OTP is ".$otp." Note: Please DO NOT SHARE this OTP with anyone.";

        if(strpos($to, '+91') !== false){
            $to = substr($to, 3);
        }
        // Old URL
        // $url = "http://prpsms.co.in/API/SendMsg.aspx?uname=20240345&pass=W999XmmR&send=TRAVNT&dest=".$to."&msg=Dear Customer %2c Your Repay OTP is ".$otp."%0ANote%3a Please DO NOT SHARE this OTP with anyone%2e%0ATeam  Travinities";
        // New URL
        $url = "http://164.52.195.161/API/SendMsg.aspx?uname=20240345&pass=W999XmmR&send=TRAVNT&dest=".$to."&msg=Dear Customer , Your MakeMyPayment OTP is ".$otp." Note: Please DO NOT SHARE this OTP with anyone.";
        $url = str_replace(" ", '%20', $url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $output=curl_exec($ch);
        curl_errno($ch);
        curl_close($ch);
        // print_r($url);
        // dd($output);
        return $output;
    }
}



if (! function_exists('SendNotificationUser')) {
    // function SendNotificationUser($token,$message,$title)
    // {
    //         $url = 'https://fcm.googleapis.com/fcm/send';

    //         $fields = array (
    //                 'registration_ids' => array (
    //                     $token
    //                 ),
    //                 'data' => array (
    //                     'title' => $title,
    //                     'body' => $message,
    //                 ),
    //                 'notification'=>array(
    //                     'title'=>$title,
    //                     'body' => $message,
    //                 )
    //         );
    //         $fields = json_encode ( $fields );

    //         $headers = array (
    //                 'Authorization: key= ' . "AAAACBUmy8g:APA91bGlTz8LDCOrAvjA-as1ORoOtYb8RWkN_sQ1PlOYJ2O4S9uuYTMwsBO1IdqEu4edq59UttOyRRDoYWEf2VqF6RiOwY61mJvmGACojUu3RPvuW9BPS8HzJWUB8Bidj15SUqwTMoGF",
    //                 'Content-Type: application/json'
    //         );

    //         $ch = curl_init ();
    //         curl_setopt ( $ch, CURLOPT_URL, $url );
    //         curl_setopt ( $ch, CURLOPT_POST, true );
    //         curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    //         curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    //         curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    //         $result = curl_exec ( $ch );
    //        //echo $result;
    //         curl_close ( $ch );
    // }

    function SendNotificationUser($tokens, $message, $title){
        // Path to the service account key file
        $serviceAccountPath = storage_path('app/public/make-my-payment-firebase-adminsdk-a1bvu-4d021677b6.json'); // Update with your actual path

        // Create a Google Client
        $client = new Google\Client();
        $client->setAuthConfig($serviceAccountPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        // Get the OAuth 2.0 token
        $accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];

        // Prepare the API URL for FCM v1
        $projectId = 'make-my-payment'; // Replace with your Firebase project ID
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        // Prepare the payload with 'tokens' for multiple recipients
        $payload = [
            'message' => [
                'notification' => [
                    'title' => $title,
                    'body' => $message,
                ],
                'data' => [
                    'title' => $title,
                    'body' => $message,
                ],
            ],
            'validate_only' => false, // You can set this to true to validate without sending
        ];

        $tokens = explode(',',$tokens);
        // Loop through tokens and send individually or as a batch
        foreach ($tokens as $token) {
            $payload['message']['token'] = $token;

            // Encode payload as JSON
            $fields = json_encode($payload);

            // Set headers
            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ];

            // Initialize CURL and send the request
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

            $result = curl_exec($ch);
            $response = json_decode($result, true);


            // Check for errors
            // if (curl_errno($ch)) {
            //     \Log::error('FCM error: ' . curl_error($ch));
            // } else {
            //     if (isset($response['error'])) {
            //         if ($response['error']['details'][0]['errorCode'] === 'UNREGISTERED') {
            //             \Log::warning('Unregistered token detected: ' . $token);
            //         }
            //         \Log::error('FCM error: ' . $response['error']['message']);
            //     } else {
            //         \Log::info('FCM Response: ' . $result); // Log success
            //     }
            // }

            curl_close($ch);
        }
    }

    if (! function_exists('GenerateRandomString')) {
        function GenerateRandomString($length = 6) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
    }

    if (! function_exists('Counterreminder')) {
        function Counterreminder($id) {
          return Reminderlogs::where('reminder_id',$id)->get()->count() ?? 0;
        }
    }

}

    if (! function_exists('logNotification')) {
        function logNotification($userId, $customerId, $type)
        {
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
        }
    }

    if (! function_exists('getTodayNotificationCount')) {
        function getTodayNotificationCount($userId, $customerId, $type)
        {
            $today = Carbon::today();

            // Sum the count for notifications sent today
            return DB::table('notifications_log')
                ->where('user_id', $userId)
                ->where('customer_id', $customerId)
                ->where('type', $type)
                ->whereDate('created_at', $today)
                ->sum('count');
            }
    }

    if (! function_exists('getSetting')) {
        function getSetting()
        {
            return DB::table('tbl_general_settings')->first();
        }
    }


    if (! function_exists('SendEmail')) {
        function SendEmail($to,$name,$sname,$amount)
        {

            $amount = str_replace('-', '', $amount);
            $message="नमस्ते ".$name." आपका ".$sname." पर ".$amount." रुपये बकाया है कृपया जल्द से जल्द इसका भुगतान करे | यदि आपने पहले ही बकाया राशि का भुगतान कर दिया है तो कृपया ध्यान न दें धन्यवाद। टीम MakeMyPayment हमारी वेबसाइट पर जाएँ www.makemypayment.co.in";

            $emailData  =
            [
                "email" => $to,
                "subject"=> "Payment Reminder",
                "content"=> $message
            ];


             Mail::send('email.email_notification', $emailData, function($message)  use($emailData){
                $message->to($emailData['email'], 'Makemypayment')
                // ->cc('makemypayment22@gmail.com')
                ->subject($emailData['subject']);
                $message->from('no-reply@makemypayment.co.in','Makemypayment');
             });

        }
    }

?>