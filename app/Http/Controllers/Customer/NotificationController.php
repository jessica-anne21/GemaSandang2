<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Mail\TrendNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function sendEmailBlast($trendId)
    {
        $trend = DB::table('trends')->where('id', $trendId)->first();

        $customers = User::where('role', 'customer')->get();

        foreach ($customers as $customer) {
            Mail::to($customer->email)->send(new TrendNotification($trend));
            
            sleep(1);
        }

        return true;
    }
}