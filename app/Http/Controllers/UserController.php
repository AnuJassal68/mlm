<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Log; // Replace with the actual Log model namespace
use App\Models\User; // Replace with the actual User model namespace
use App\Models\Deposit; // Replace with the actual Deposit model namespace
use App\Models\Income; // Replace with the actual Income model namespace
use App\Models\Spent; // Replace with the actual Spent model namespace
use App\Models\Profitdata; // Replace with the actual Profitdata model namespace
use Carbon\Carbon;
use App\helpers;
use App\Models\DailyRoi;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    //
    public function dashboard()
    {
        // Check if the user is authenticated
        if (!Session::has('user_id')) {
            // Redirect to the login page or perform any other action for unauthenticated users
            return redirect('log-in');
        }

        // Get the user ID from the session
        $uid = Session::get('user_id');
        // Part 2: Re-Investment
        // Assuming the user ID is stored in the 'USER' session variable
        $loginfo = Log::where('userid', $uid)->orderBy('id', 'DESC')->limit(2)->get();
        $sconfig = config('sconfig');
        $ret = balance_info($uid, $sconfig); // You need to define the balance_info function or replace it with the equivalent logic in your Laravel app.


        if (!$uid) {
            // Handle the case when the user is not logged in or session data is not available
            return redirect('login'); // Redirect to login page or any other page as per your application's logic.
        }

        $loginfo = Log::where('userid', $uid)->orderBy('id', 'DESC')->limit(2)->get();
        $ret = balance_info($uid, $sconfig);
        $uinfo = User::where('id', $uid)->first(['referalid', 'firstname', 'middlename', 'lastname', 'emailid', 'loginid', 'address', 'city', 'state', 'country', 'pincode', 'mobile', 'accountno', 'createdate']);
        $rinfo = User::where('id', $uinfo->referalid)->first(['firstname', 'middlename', 'lastname', 'loginid']);

        $timenow = time();
        $usrinfo = User::where('referalid', $uid)->orderBy('id', 'DESC')->get();
        $drefid = $usrinfo->pluck('id')->toArray();

        $tids = [0];
        $ords = [0];
        $tdeposit = Deposit::where('userid', $uid)->where('bActive', 'Y')->where('deposit_type', '!=', 'Re-Invest')->get(['id', 'label']);
        foreach ($tdeposit as $deposit) {
            $tids[] = $deposit->id;
            $ords[$deposit->id] = $deposit->label;
        }

        $trinfo = $this->getTransactionInfo($uid, $tids);


        // Return the dashboard view with the retrieved data
        return view('user.dashboard', compact('loginfo', 'ret', 'uinfo', 'rinfo', 'timenow', 'drefid', 'ords', 'trinfo'));
    }

    private function getTransactionInfo($uid, $tids)
    {


        $trinfo = DB::select("
        (SELECT depositid as id, createdate, 'credit' AS ctype, 'Daily Incentive' AS tinfo, amount AS credit, 0 AS debit, 'roi' AS tarea FROM tbl_daily_roi WHERE userid = '$uid' AND depositid IN (" . implode(",", $tids) . "))
        UNION
        (SELECT label as id, createdate, 'debit' as ctype, 'Re-Investment' AS tinfo, 0 AS credit, deposit AS debit, 'reroi' AS tarea FROM tbl_deposit WHERE userid = '$uid' AND deposit_type = 'Re-Investment')
        UNION
        (SELECT us.firstname as id, inc.createdate, 'credit' as ctype, inc.incometype AS tinfo, inc.income as credit, 0 as debit, 'income' AS tarea FROM tbl_income inc JOIN tbl_user us ON inc.byuserid = us.id WHERE userid = '$uid')
        UNION
        (SELECT id, createdate, 'debit' AS ctype, bankdetails AS tinfo, 0 as credit, processamt as debit, 'spent' AS tarea FROM tbl_spent WHERE userid = '$uid')
        UNION
        (SELECT id, createdate, 'credit' AS ctype, description AS tinfo, sharing_incentive AS credit, 0 AS debit, 'sharing' AS tarea FROM tbl_profitdata WHERE uid = '$uid')
        ORDER BY createdate ASC
    ");
        return $trinfo;
    }

    //edit profile page
    // public function editprofile(){
    //     return view('user.edit-profile');
    // }


    //update profile()
    public function updateProfile(Request $request)
    {
        if ($request->has('updateprofile')) {
            $nid =  Session::get('user_id');
            $family = json_encode($request->input('family'));

            $cids = [
                $request->input('c_country') * 1,
                $request->input('c_state') * 1,
                $request->input('c_city') * 1,
            ];

            $cony = [];
            $cinfo = DB::table('tbl_country_state_city')->whereIn('id', $cids)->get();
            foreach ($cinfo as $c) {
                $cony[$c->id] = $c->title;
            }
            $c_city_key = $request->input('c_city');
            $c_city_value = isset($cony[$c_city_key]) ? $cony[$c_city_key] : null;
            $c_state_key = $request->input('c_state');
            $c_state_value = isset($cony[$c_state_key]) ? $cony[$c_state_key] : null;

            $c_country_key = $request->input('c_country');
            $c_country_value = isset($cony[$c_country_key]) ? $cony[$c_country_key] : null;

            $update = [
                "fixedline_code" => $request->input('fixedline_code'),
                "fixedline" => $request->input('fixedline'),
                "bloodgroup" => $request->input('bloodgroup'),
                "adhaar_card" => $request->input('adhaar_card'),
                "qualification" => $request->input('qualification'),
                "currentjob" => $request->input('currentjob'),
                "work_experience" => $request->input('work_experience'),
                "accountno" => $request->input('accountno'),
                "dob" => $request->input('dob'),
                "firstname" => $request->input('firstname'),
                "lastname" => $request->input('lastname'),
                "mobile" => $request->input('mobile'),
                "emailid" => $request->input('email'),
                "c_address" => $request->input('c_address'),
                "c_city" => $c_city_value,
                "city" => $request->input('city'),
                "c_state" => $c_state_value,
                "state" => $request->input('state'),
                "c_pincode" => $request->input('c_pincode'),
                "pincode" => $request->input('pincode'),
                "c_country" => $c_country_value,
                "country" => $request->input('country'),
                "aboutme" => $request->input('aboutme'),
                "family_info" => $family,
                "pan" => $request->input('pan'),
            ];

            UpdateQry("tbl_user", $update, "id = '".$nid."'");

            $destdocs = "uploads/";
            $extcheck = [".jpg", ".jpeg", ".gif", ".png"];
            return redirect('updateProfile')->with('success', 'Record(s) has been updated successfully!');
        }
        $userId = Session::get('user_id');
        $uinfo = DB::table('tbl_user')->where('id', $userId)->get();
        $family = json_decode($uinfo[0]->family_info, true);

        $emsg = session('emsg');
        $etype = session('etype');

        return view('user.edit-profile', compact('uinfo', 'family', 'emsg', 'etype'));
    }

    public function profile(Request $request)
    {
        $uid = $request->session()->get('user_id');
        if (!empty($request->id)) {
            $id = base64_decode($request->id);
            $ctoken = md5($id . "salty");
            $token = $request->token;
            if (!empty($request->token)) {
                $uid = $id;
            }
        }
        $uinfo = DB::table('tbl_user')->where('id', $uid)->first();
        // $family = json_decode($uinfo->family_info, true);

        $ruinfo = DB::table('tbl_user')->where('id', $uinfo->referalid)
          ->select('loginid', 'firstname', 'middlename', 'lastname')->first();

        return view('user.profile', compact('uinfo', 'ruinfo', 'uid'));
    }

    //serach filter
  



}
