<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\AdIds;
use Illuminate\Http\Request; 

class AdsController extends Controller
{
    public function getAdsInfo(Request $request)
    {
        try {
            
            $settings  = GeneralSetting::first(['is_ads_active']);
            $is_ad_active = true;
            if($settings->is_ads_active == 0){
                $is_ad_active = false;
            } 
            
            $adIdData = AdIds::get(['id', 'device_type', 'ad_id']);

            $data[] = array(
                        'is_ad_active' => $is_ad_active,
                        'ad_ids' => $adIdData
                    );

            return ResponseAPI(true,"Data Found..", "", $data);

        } catch (\Throwable $th) {
            return ResponseAPI(false,'Something Wrongs.',"",array(),401);
        }
    }
}
