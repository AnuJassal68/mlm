<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function settingindex()
    {
        $emsg = null;
        $etype = null;
        if (request()->has('msg') && request('msg') === 'updated') {
            $emsg = "Setting has been Updated!";
            $etype = "success";
        }
        $setinfo = Setting::where('settype', 'setting')->get();
        $fullset = 'Y';

        return view('admin.settingpage', compact('emsg', 'etype', 'setinfo', 'fullset'));
    }

    /**
     * Update the settings based on the form input.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(Request $request)
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
                "title" => 'Settings Change',
                "href" => url('?pg=setting'),
                "section" => 'Settings',
                "createdate" => $timenow,
                "userid" => Auth::guard('admin')->user()->id,
                "pageid" => 1,
                "page" => 'tbl_setting',
                "action" => $tbl,
                "ipaddress" => $ipadd,
            ];

            ActivityLog::create($insert);
        }

        return redirect()->route('setting-index')->with('emsg', 'Setting has been Updated!')->with('etype', 'success');
    }
}
