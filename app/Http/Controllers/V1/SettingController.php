<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SettingController extends Controller
{
    public function updatePaymentSettings(Request $request)
    {
        $request->validate([
            'khalti_secret_key' => 'required|string',
            'khalti_base_url' => 'required|string',
            'esewa_product_code' => 'required|string',
            'esewa_secret' => 'required|string',
        ]);

        Setting::updateOrCreate(
            ['key' => 'khalti_secret_key'],
            ['value' => Crypt::encryptString($request->khalti_secret_key)]
        );
        Setting::updateOrCreate(
            ['key' => 'khalti_base_url'],
            [
                'value' => $request->khalti_base_url
            ]
        );
        Setting::updateOrCreate(
            ['key' => 'esewa_product_code'],
            ['value' => $request->esewa_product_code]
        );
        Setting::updateOrCreate(
            ['key' => 'esewa_base_url'],
            [
                'value' => $request->esewa_base_url
            ]
        );
        Setting::updateOrCreate(
            ['key' => 'esewa_secret'],
            ['value' => Crypt::encryptString($request->esewa_secret)]
        );

        return response()->json([
            'success' => true,
            'message' => 'Payment settings updated successfully.'
        ]);
    }
}
