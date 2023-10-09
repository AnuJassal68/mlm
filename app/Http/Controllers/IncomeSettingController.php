<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class IncomeSettingController extends Controller
{
    /**
     * Display the income settings form.
     *
     * @return \Illuminate\View\View
     */
    public function showIncomeSettingsForm()
    {
        
        $settings = Setting::where('settype', 'incomesetting')->get();
        $fullset = 'Y'; 
        
        return view('admin.incomesetting', compact('settings', 'fullset'));
    }

    /**
     * Update income settings based on the form input.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateIncomeSettings(Request $request)
    {
 
        $dlog = 0;
        $tbl = '';
        foreach ($request->except(['_token', 'submitclient']) as $key => $value) {
            if (!strpos($key, 'old_')) {

                $setting = Setting::where('setname', $key)->first();

                if ($setting && $setting->setvalue != $value && $value !== 'Update') {

                    $setting->update(['setvalue' => $value]);

                    $dlog = 1;
                    $ky = str_replace(array('-', '_'), ' ', $key);
                    $ky = ucwords($ky);
                    $tbl .= '<tr><td>' . ($ky) . '</td><td>' . $setting->setvalue . '</td><td>' . $value . '</td></tr>';
                }
            }
        }

        if ($dlog > 0) {
            $timenow = time();
            $ipadd = $request->ip();
            $insert = [
                "title" => 'Income Settings Change',
                "href" => url('?pg=income-setting'),
                "section" => 'Income Settings',
                "createdate" => $timenow,
                "userid" => session('ADMIN.ID'),
                "pageid" => 2,
                "page" => 'tbl_setting',
                "action" => $tbl,
                "ipaddress" => $ipadd,
            ];

         
            ActivityLog::create($insert);
        }

        return redirect()->route('income-setting')->with('success', 'Income Setting has been Updated!');
    }
}
