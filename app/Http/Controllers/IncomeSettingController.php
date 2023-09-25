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
        // Retrieve income settings data
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
        // Initialize flag to track if any settings were updated
        $dlog = 0;

        // Initialize table to store changes for the activity log
        $tbl = '';

        // Iterate through the form input to update income settings
        foreach ($request->except(['_token', 'submitclient']) as $key => $value) {
            if (!strpos($key, 'old_')) {
                // Find the income setting by its name
                $setting = Setting::where('setname', $key)->first();

                // Check if the setting exists and its value has changed
                if ($setting && $setting->setvalue != $value && $value !== 'Update') {
                    // Update the income setting's value
                    $setting->update(['setvalue' => $value]);

                    // Update the flag and log table
                    $dlog = 1;
                    $ky = str_replace(array('-', '_'), ' ', $key);
                    $ky = ucwords($ky);
                    $tbl .= '<tr><td>' . ($ky) . '</td><td>' . $setting->setvalue . '</td><td>' . $value . '</td></tr>';
                }
            }
        }

        // If income settings were updated, create an activity log entry
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

            // Create an activity log entry
            ActivityLog::create($insert);
        }

        return redirect()->route('income-setting')->with('emsg', 'Income Setting has been Updated!')->with('etype', 'success');
    }
}
