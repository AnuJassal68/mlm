<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use Illuminate\Support\Facades\DB;
use App\Models\Income;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use App\Models\DailyRoi;
use App\Models\spent;
use App\Models\Ticketing;
use App\Models\TicketMessage;
use GuzzleHttp\Client;
use App\Models\LaraBlockIo;
use PrevailExcel\Nowpayments\Facades\Nowpayments;
use PrevailExcel\Nowpayments\Models\Logger;

// use Blockavel\LaraBlockIo;
class DepositController extends Controller
{
    /**
    * Display the deposit page.
    *
    * This function renders the deposit page view, allowing users to make deposits.
    *
    * @return \Illuminate\View\View
    */
    public function index()
    {
        return view('user.deposit');
    }

    /**
     * Handle user reinvestment requests.
     *
     * This function allows users to make reinvestments, and it processes reinvestment requests.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function reinvestment(Request $request)
    {

        try {

            $sconfig = config('sconfig.investment');


            $mindeposit = min($sconfig);

            $balinf = balance_info($request->session()->get('user_id'), $sconfig);

            $cbal = $balinf['binc'];

            if ($request->input('deposit') >= 10 && $request->input('deposit') <= 99) {
                $daily_percentage = 2.5;
            } elseif ($request->input('deposit') >= 100 && $request->input('deposit') <= 999) {
                $daily_percentage = 3;
            } elseif ($request->input('deposit') >= 1000) {
                $daily_percentage = 4;
            }

            if ($request->has('deposit')) {


                $etype = "danger";
                if ($request->input('deposit') >= $mindeposit) {
                    
                    if ($request->input('deposit') > $cbal) {

                        return redirect()->route('re-investment', ['msg' => 'insufficient']);
                    } else {

                        $timenow = time();
                        $inserts = [
                            "userid" => $request->session()->get('user_id'),
                            "createdate" => $timenow,
                            "deposit" => $request->input('deposit'),
                            "daily_percentage" => $daily_percentage,
                            "deposit_type" => 'Re-Investment'
                        ];

                        $deposit = Deposit::create($inserts);
                        $nid = $deposit->id;
                        //  dd( $deposit->id);
                        $re =  re_activate_investment($deposit->id, $sconfig);

                        $res = $this->apidata($request);
                        $resArray = json_decode($res, true);

                        $address = $resArray['pay_address'];

                        $label = $resArray['payment_id'];
                        $error_log = "";
                        $updates = array("address" => $address, "label" => $label, 'error_log' => $error_log);

                        UpdateQry("tbl_deposit", $updates, " id = '" . $nid . "'");
                        return redirect()->route('my-investment', ['msg' => 're-invest', 'am' => $request->input('deposit')]);
                    }
                } else {
                    return redirect()->route('re-investment', ['msg' => 'blank']);
                }
            }

            $token = base64_decode($request->input('token'));

            $dinfo = Deposit::where('id', $token)
                ->where('deposit_type', 'Re-Investment')
                ->where('userid', $request->session()->get('user_id'))
                ->get();

            $emsg = null;
            $etype = null;

            if (empty($emsg)) {
                if ($request->input('msg') == 'wait') {
                    $emsg = 'Payment is under process, Please try again after some time.';
                    $etype = 'warning';
                } elseif ($request->input('msg') == 'error') {
                    $emsg = 'An error has occurred, Please try again after some time.';
                    $etype = 'error';
                } elseif ($request->input('msg') == 'blank') {
                    $emsg = 'Minimum Amount Deposit is $10.';
                    $etype = 'danger';
                } elseif ($request->input('msg') == 'insufficient') {
                    $emsg = 'Sorry, insufficient balance in your account.';
                    $etype = 'warning';
                }
            }

            $url = "https://blockchain.info/stats?format=json";
            $stats = Http::get($url)->json();

            $btcValue = $stats['market_price_usd'];
            $usdCost = $dinfo[0]->deposit ?? 0;

            $convertedCost = $usdCost / $btcValue;

            return view('user.re-investment', [
                'emsg' => $emsg,
                'etype' => $etype,
                'btcValue' => $btcValue,
                'usdCost' => $usdCost,
                'convertedCost' => $convertedCost,
                'cbal' => $cbal,
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            echo 'error';
            exit;
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the user's active investments.
     *
     * This function retrieves and displays a list of the user's active investments and related information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function myinvestment(Request $request)
    {
        $sconfig = [];
        $userId = Session::get('user_id');
        $rinfo = DB::table('tbl_deposit')
             ->where('userid', $userId)
             ->where('bActive', 'Y')
             ->where('deposit_type', '!=', 'Re-Invest')
             ->where('createdate', '<=', time())
             ->orderByDesc('createdate')
             ->get();


        $emsg = '';
        $etype = '';

        $offset = $_GET['offset'] ?? 0;
        $str = "pg=my-investment";

        return view('user.my-investment', compact('emsg', 'etype', 'rinfo', 'str', 'offset'));
    }
    /**
     * Display the incentive history for the current user.
     *
     * This function retrieves and displays a list of income records, including incentive history, for the current user.
     *
     * @return \Illuminate\View\View
     */
    public function incentive()
    {
        $limit = 40;
        $rinfo = DB::table('tbl_income as inc')
       ->join('tbl_user as us', 'inc.byuserid', '=', 'us.id')
       ->where('inc.userid', '=', session('user_id'))
       ->orderBy('inc.id', 'DESC')
       ->select('inc.incometype', 'inc.income', 'inc.createdate', 'inc.incomelog', 'us.firstname', 'us.middlename', 'us.lastname', 'us.loginid')
       ->get();

        return view('user.my-incentive', compact('rinfo'));
    }
    /**
     * Display a list of pending deposits for the current user.
     *
     * This function retrieves and displays a list of pending deposits related to investments made by the current user.
     *
     * @return \Illuminate\View\View
     */
    public function viewPendingDeposits()
    {
        try {

            $rinfo = DB::table('tbl_deposit')
                        ->where('userid', session('user_id'))
                        ->where('deposit_type', 'Invest')
                        ->where('bActive', 'N')
                        ->whereNotNull('address')
                        ->get();

            $url = "https://blockchain.info/stats?format=json";
            $response = Http::get($url);
            $stats = $response->json();
            $btcValue = $stats['market_price_usd'];
            $usdCost = 1;
            return view('user.my-deposits', compact('rinfo', 'btcValue', 'usdCost', 'stats'));
        } catch (\Exception $e) {

            return redirect()->route('my-deposits')->with('error', $e->getMessage());
        }
    }

    /**
     * Display a list of direct referrals for the current user.
     *
     * This function retrieves and displays a list of direct referrals (users referred by the current user).
     *
     * @return \Illuminate\View\View
     */
    public function directReferrals()
    {
        try {
            $limit = 50;

            $rinfo = DB::table('tbl_user')
                ->where('referalid', session('user_id'))
                ->get();

            foreach ($rinfo as $user) {
                $userStatus = DB::table('tbl_deposit')
                       ->where('userid', $user->id)
                       ->where('bActive', 'Y')
                       ->orderBy('id', 'DESC')
                       ->get();

                $userinv = DB::table('tbl_deposit')
                    ->where('userid', $user->id)
                    ->where('bActive', 'Y')
                    ->select(DB::raw('SUM(deposit) AS totdeposit'))
                    ->first();

                $totinvs = $userinv ? $userinv->totdeposit : 0;
                $status = count($userStatus) ? 'Paid' : 'Unpaid';

                $user->total_investments = $totinvs;
                $user->status = $status;
            }
            return view('user.directs', compact('rinfo'));
        } catch (\Exception $e) {

            return redirect()->route('direct-referrals')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the user's network and related information.
     *
     * This function retrieves and displays information about the user's network, including user statuses and investments.
     *
     * @return \Illuminate\View\View
     */
    public function viewNetwork()
    {
        try {
            $dinfo = DB::table('tbl_dstat')
                ->where('parentid', session('user_id'))
                ->get();

            $uids = [];
            $levels = [];
            foreach ($dinfo as $info) {
                $uids[] = $info->userid;
                $levels[$info->userid] = $info->levelid;
            }

            $rinfo = [];
            if (!empty($uids)) {
                $cntinfo = DB::table('tbl_user')->whereIn('id', $uids)->count('id');

                $rinfo = DB::table('tbl_user')
                    ->whereIn('id', $uids)
                    ->get();

                $userStatus = DB::table('tbl_deposit')
                    ->whereIn('userid', $uids)
                    ->where('bActive', 'Y')
                    ->orderBy('id', 'DESC')
                    ->get();

                $userinv = DB::table('tbl_deposit')
                    ->whereIn('userid', $uids)
                    ->where('bActive', 'Y')
                    ->orderBy('id', 'DESC')
                    ->select(DB::raw('SUM(deposit) AS totdeposit, userid'))
                    ->groupBy('userid')
                    ->get();

                $userStatus = new Collection($userStatus);
                $userinv = new Collection($userinv);
            }

            return view('user.network', compact('rinfo', 'userStatus', 'userinv', 'levels'));
        } catch (\Exception $e) {

            return redirect()->route('network')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the user's daily incentives and earnings.
     *
     * This function retrieves and displays the user's daily incentives and earnings based on their investments.
     * It also handles success and reinvestment messages, if any.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function dailyincentive(Request $request)
    {
        try {
            $timenow = time();
            $limit = 50;
            $raise_per = 4;


            $limit_days = $sconfig->days_limit ?? 0;
            $apkdt = 0;

            $uinfo = DB::table('tbl_user')
                ->where('id', Session::get('user_id'))
                ->where('bdormant', 'Y')
                ->first();

            $today = $tdday = mktime(23, 59, 59, date('n'), date('j'), date('Y'));

            if ($uinfo && $uinfo->packagedate > 0) {
                // $pkdt   = $uinfo->packagedate;
                // $tdday  = mktime(23, 59, 59, date('n', $pkdt), date('j', $pkdt), date('Y', $pkdt));
                // $ntdday = mktime(23, 59, 59, date('n'), date('j'), date('Y'));
                // $apkdt  = $tdday;
            }

            $rinfo = DB::table('tbl_deposit')
                ->where('userid', Session::get('user_id'))
                ->where('bActive', 'Y')
                ->where('deposit_type', '!=', 'Re-Invest')
                ->where('createdate', '<=', $timenow)
                ->orderBy('createdate', 'desc')
                ->get();

            $emsg = null;
            $etype = null;

            if (empty($emsg)) {
                if ($request->query('msg') === 'success') {
                    $emsg = 'Investment has been successfully processed!';
                    $etype = 'success';
                } elseif ($request->query('msg') === 're-invest') {
                    $emsg = 'Your amount has been invested successfully for ' . $request->query('am');
                    $etype = 'success';
                }
            }


            return view('user.incentive', compact('rinfo', 'emsg', 'etype'));
        } catch (\Exception $e) {

            return redirect()->route('incentive')->with('error', $e->getMessage());
        }
    }
    //roi listing



    public function statement(Request $request)
    {

        $uid = $request->session()->get('user_id');
        // dd( $uid);
        $vid = base64_decode($request->input('vid'));//comenting due to static testing

        // The rest of your code remains unchanged

        $dinfo = Deposit::where('userid', $uid)
                        ->where('id', $vid)
                        ->get();

        if($dinfo->isEmpty()) {
            return redirect()->route('incentives');
        }

        $list = getquery("tbl_daily_roi", " AND depositid = '" . $vid . "' ORDER BY id desc");

        return view('user.roi', [
            'dinfo' => $dinfo,
            'list' => $list,
        ]);
    }




    /**
     * Display the user's level-based incentives and earnings.
     *
     * This function retrieves and displays the user's level-based incentives and earnings
     * by joining the 'tbl_income' and 'tbl_user' tables and selecting relevant information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function levelincentive(Request $request)
    {
        try {

            $rinfo = DB::table('tbl_income as inc')
                ->join('tbl_user as us', 'inc.byuserid', '=', 'us.id')
                ->where('userid', Session::get('user_id'))
                ->orderBy('inc.id', 'DESC')
                ->select('inc.incometype', 'inc.income', 'inc.createdate', 'inc.incomelog', 'us.firstname', 'us.middlename', 'us.lastname', 'us.loginid')
                ->get();
            // dd($rinfo);
            return view('user.level-incentive', compact('rinfo'));
        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Handle user requests for payments and withdrawals.
     *
     * This function processes user requests for payments and withdrawals, checks the balance,
     * and handles the withdrawal operation if requested.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */

    public function requestPayments(Request $request)
    {

        try {
            $sconfig = config('sconfig');


            $deposit = $mindeposit = $sconfig['minimum_deposit'];
         
            $mindeposit = $sconfig['minimum_withdraw'];
            
            $daily_percentage = $sconfig['daily_percentage'];
         
            $uinfo = User::where('id', Session::get('user_id'))->select('id', 'accountno')->first();
         
            $ret = balance_info(Session::get('user_id'), $sconfig);
           
            if (request()->isMethod('post')) {
                $etype = "danger";
                // dd($ret );
                if ($ret['binc'] >= $mindeposit && $ret['binc'] >= request('netamount')) {

                    $timenow = time();
                    $retr = doWithdrawal($uinfo, $ret, $sconfig, $request);

                    if($retr == 'success') {

                        return redirect('withdrawlist');
                    }

                } else {
                    throw new \Exception("Insufficient balance for withdrawal.");
                }
            }

            $msg = request('msg');
            $etype = '';
            $emsg = '';
            if ($msg == 'success') {
                $etype = 'success';
                $emsg = "Amount has been successfully transferred to your BTC Account!";
            } elseif ($msg == 'error') {
                $emsg = 'An error has occurred. Please try again after some time.';
                $etype = 'error';
            } elseif ($msg == 'warning') {
                $emsg = 'Sorry, insufficient balance in your account for this request.';
                $etype = 'warning';
            }
            $rate = 0;
            if ($ret['wid'] > 0) {
                $rate = bitexchange();
            }
            $url = "https://blockchain.info/stats?format=json";
            $stats = json_decode(file_get_contents($url), true);

            $btcValue = $stats['market_price_usd'];
            $usdCost = $ret['binc'];
            $convertedCost = $usdCost / $btcValue;



            return view('user.withdraw', compact('mindeposit', 'ret', 'emsg', 'etype', 'convertedCost', 'uinfo', 'stats'));
        } catch (\Exception $e) {

            return view('user.withdraw')->with('error', $e->getMessage());
        }
    }


//showing tds and adminchanrges before submit the data
public function getBalanceInfo(Request $request)
{
    $userid = $request->session()->get('user_id');
    
    $amount = $request->input('amount');
    
    $ret = balance_info($userid);
  // Call the balance_info function with the appropriate $userid
    $tds = $ret['stax'];
//    dd( $tds);
    $admincharges = $ret['rein'];
    
    return response()->json(['tds' => $tds, 'admincharges' => $admincharges]);
}
    //showing withdarwal listing

    public function withdrawlist(Request $request)
    {
        $q = $request->input('q', '');
        $results = Spent::join('tbl_user AS usr', 'tbl_spent.userid', '=', 'usr.id')
        ->orWhere('usr.did', 'LIKE', $q . '%')
        ->orWhere('usr.loginid', 'LIKE', $q . '%')
        ->select('tbl_spent.*', 'tbl_spent.id as spentid', 'usr.id', 'usr.loginid AS loginid', )
        ->orderBy('tbl_spent.createdate', 'DESC')->get();


        return view('user.withdrawallist', compact('results'));
    }

    /**
 * Display all daily ROI statements for a specific deposit.
 *
 * This function retrieves and displays all daily ROI statements associated with a particular deposit.
 *
 * @param  Request  $request
 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
 */
    public function allstatement(Request $request)
    {

        try {
            $uid = $request->session()->get('user_id');
            $vid = base64_decode($request->input('vid'));

            $dinfo = Deposit::where('userid', $uid)
                ->where('id', $vid)
                ->first();

            if (!$dinfo) {
                throw new \Exception("Invalid deposit ID.");
            }

            $list = DailyRoi::where('depositid', $vid)
                ->orderBy('id', 'desc')
                ->get();

            return view('user.all-statements', compact('list'));
        } catch (\Exception $e) {

            return redirect()->route('incentive')->with('error', 'Invalid deposit ID or an error occurred.');
        }
    }

    /**
     * Handle the submission of a user's deposit request.
     *
     * This function processes a user's deposit request, validates the deposit amount,
     * calculates the daily percentage based on the deposit amount, inserts the deposit
     * record into the database, and generates a Block.io address for the deposit.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */


    public function submitDeposit(Request $request)
    {
        // require_once('vendor/autoload.php');
        // $uid = session()->all();
        // dd($request);exit;
        $request = $request;
        try {
            $sconfig = config('sconfig');

            $invests = $sconfig['investment'];

            $mindeposit = min($invests);


            if ($request->deposit >= 10 && $request->deposit <= 99) {
                $daily_percentage = 2.5;
            } elseif ($request->deposit >= 100 && $request->deposit <= 999) {
                $daily_percentage = 3;
            } elseif ($request->deposit >= 1000) {
                $daily_percentage = 4;
            }

            $etype = "danger";
            if ($request->deposit >= $mindeposit) {
                $timenow = time();


                $inserts = [
                    "userid" => $request->session()->get('user_id'),
                    "createdate" => $timenow,
                    "deposit" => $request->deposit,
                    "daily_percentage" => $daily_percentage,
                    "deposit_type" => 'Invest',

                ];
                $nid = InsertQry("tbl_deposit", $inserts);

                if (!$nid) {
                    throw new \Exception("Failed to insert deposit record.");
                }

                $oid = random_number(4);

                $label = $oid . $nid;

                $ret = array();
                $error_log = "";
                $res = $this->apidata($request);

                $resArray = json_decode($res, true);

                $address = $resArray['pay_address'];

                $label = $resArray['payment_id'];

                $updates = array("address" => $address, "label" => $label, 'error_log' => $error_log);

                UpdateQry("tbl_deposit", $updates, " id = '" . $nid . "'");

                if ($res) {
                    return redirect()->route('processDeposit', ['token' => base64_encode($nid)])->with('success', 'Proccessing Payment');

                } else {
                    throw new \Exception("Failed to generate BlockIo address.");
                }
            } else {
                $emsg = "Minimum Amount Deposit is $" . $mindeposit;
                return redirect('deposit')->with('error', $emsg);
            }
        } catch (\Exception $e) {

            return redirect('processDeposit')->with('error', 'Something went wrong. Please try again.');
        }
    }


    public function apidata($request)
    {
        $jsonData = $request;

        $data = array(
            'price_amount' => $jsonData->deposit ?? 100,
            'price_currency' => $jsonData->price_currency ?? 'usd',
            'pay_amount' => $jsonData->deposit ?? 0,
            'pay_currency' =>  'LINK',
            'ipn_callback_url' => 'http://localhost/coinswings/processDeposit',
            'order_id' => $jsonData->order_id ?? uniqid(),
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-sandbox.nowpayments.io/v1/payment',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'x-api-key: 9WS3DBY-F8F4P16-KJB2FZY-3DJAJG0',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            echo 'Curl error: ' . curl_error($curl);
        } else {
            curl_close($curl);
            return $response;
        }


    }





    /**
     * Process a user's deposit and check its status.
     *
     * This function handles the processing of a user's deposit, checks the status of the deposit,
     * and provides feedback to the user based on the deposit status.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */

    //  public function callbackurl(){

    //  }
    public function processDeposit(Request $request)
    {

        $token = $request->get('token');
        $id =  $request->session()->get('user_id');
        try {
            $sconfig = config('sconfig');

            $invests = $sconfig['investment'];

            $mindeposit = min($invests);

            if ($request->has('check_deposit')) {

                $nid = $request->get('token');


                $ret = json_decode($this->getblance($request), true);
                // dd($ret);
                if (is_array($ret) && isset($ret['payment_status']) && $ret['payment_status'] == 'finished') {

                    $us = activate_investment($nid, $sconfig);
                    $inid = json_decode($nid);
                    // dd( $inid );
                    $timenow = time();

                    $lmt = 1 * 1;

                    if($lmt == 0) {
                        mysql_query("TRUNCATE tbl_daily_roi");
                    }
                    //  $id = 1;
                    $uinfo = User::orderBy('id', 'ASC')->where('id', $id)->get();
                    $limit_days = $sconfig['days_limit'];
                    $update = 1708300800;

                    $newdate = 1739836800;
                    $incdt =  strtotime(date("2017-06-30 23:59:59"));

                    $today = $tdday = mktime(23, 59, 59, date('n'), date('j'), date('Y'));

                    for($u = 0;$u < count($uinfo);$u++) {

                        $userid = $uinfo[0]['id'];//1

                        $ninc = 0;

                        $rinfo = getquery("tbl_deposit", " AND id = '" . $inid . "' AND bActive = 'Y' AND deposit_type != 'Re-Invest'  AND createdate <= '" . $timenow . "'", "*");



                        for($i = 0;$i < count($rinfo);$i++) {

                            $tinc = $ninc = $ndays = $days = $daily_per = $inc = 0;

                            $pkdt = strtotime("today", $rinfo[$i]['createdate']);

                            $pkdt = $pkdt + 86400 * 3;

                            $daily_per = $rinfo[$i]['daily_percentage'] * 1;

                            $dblamt = $rinfo[$i]['deposit'] * 2;

                            if($pkdt <= $update) {

                                $days 	= floor(($update - $pkdt) / 86400) + 1;//2.5


                                $days 	= 0;

                                $start = $pkdt;
                                $iter = 24 * 60 * 60;

                                $stsun = 0;

                                $qry = "INSERT INTO tbl_daily_roi (userid,depositid,roi,amount,createdate) values ";

                                for($ia = $start; $ia <= $update; $ia = $ia + $iter) {

                                    date("d.m.y", $incdt) . '-' . date("d.m.y", $ia) . '<br>';
                                    if((Date('D', $ia) == 'Sat' || Date('D', $ia) == 'Sun') and $ia > $incdt) {
                                        $stsun++;
                                    } else {

                                        $inc  = (($rinfo[$i]['deposit'] * $daily_per) / 100);

                                        $tinc += $inc;

                                        if($tinc < $dblamt) {
                                            $inc = $dblamt - $tinc;

                                        }

                                        if($inc > 0) {
                                            $days += 1;

                                            $qry .= "('" . $userid . "','" . $rinfo[$i]['id'] . "','" . $daily_per . "','" . $inc . "','" . $ia . "'),";


                                        }

                                    }

                                }
                                if ($days > 0) {

                                    $qry = rtrim($qry, ',');

                                    DB::beginTransaction(); // Begin a transaction

                                    try {
                                        DB::statement($qry); // Execute the query

                                        DB::commit(); // Commit the transaction

                                        echo "Query executed successfully";
                                    } catch (\Exception $e) {
                                        DB::rollback(); // Rollback the transaction if an exception occurred

                                        echo "Error executing query: " . $e->getMessage();
                                    }
                                    return redirect()->route('my-investment')->with('message', $ret);
                                }

                            }
                            #nudate

                            if($pkdt < $newdate) {
                                $nudate = $newdate;
                            } else {

                                $nudate = $pkdt;

                            }
                            #new per

                            if($rinfo[$i]['deposit'] >= 10000) {
                                $daily_per = 2.5;
                            } elseif($rinfo[$i]['deposit'] >= 3000) {
                                $daily_per = 2;
                            } else {
                                $daily_per = 1.25;
                            }
                            $iter = 24 * 60 * 60;

                            $stsun = 0;

                            $qry =  "INSERT INTO tbl_daily_roi (userid,depositid,roi,amount,createdate) values ";

                            for($ia = $nudate; $ia >= $today; $ia = $ia + $iter) {

                                if(Date('D', $ia) == 'Sat' || Date('D', $ia) == 'Sun') {

                                    $stsun++;

                                } else {
                                    $inc  = (($rinfo[$i]['deposit'] * $daily_per) / 100);

                                    $tinc += $inc;

                                    if($tinc > $dblamt) {
                                        $inc = $dblamt - $tinc;
                                    }

                                    if($inc > 0) {

                                        $ndays += 1;
                                        $qry .= "('" . $userid . "','" . $rinfo[$i]->id . "','" . $daily_per . "','" . $inc . "','" . $ia . "'),";

                                    }

                                }

                            }

                            if($ndays > 0) {

                                $qry = rtrim($qry, ',');
                                mysql_query($qry);
                            }
                        }
                    }
                    return redirect()->route('my-investment')->with('message', $ret);
                }

                return redirect()->route('btcvalue', ['token' => $request->get('token'), 'msg' => $ret]);
            }

            if (!empty($request->get('token'))) {
                $token = base64_decode($request->get('token'));

                $dinfo = getquery('tbl_deposit', ' AND id = "' . $token . '" AND userid = "' . session('user_id') . '" AND deposit_type != "Re-Invest" ');

                if (empty($dinfo)) {
                    throw new \Exception("Invalid deposit token.");
                }
            }

            $emsg = '';
            $etype = '';

            if ($request->get('msg') == 'wait') {
                $emsg = 'Payment is under process, Please try again after some time.';
                $etype = 'warning';
            } elseif ($request->get('msg') == 'error') {
                $emsg = 'An error has occurred, Please try again after some time.';
                $etype = 'error';
            }

            $url = "https://blockchain.info/stats?format=json";
            $stats = Http::get($url)->json();

            $btcValue = $stats['market_price_usd'];

            $usdCost = $dinfo[0]['deposit'];

            $convertedCost = $usdCost / $btcValue;


            // echo 'check';exit;
            return view('user.btcvalue', compact('dinfo', 'emsg', 'etype', 'convertedCost', 'stats'));
        } catch (\Exception $e) {
            // echo 'anuanu';exit;
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function getblance($request)
    {
        $id = $request->get('token');

        $data = Deposit::where('id', $id)->get('label');

        $value = $data[0]->label;
        //  dd($value);
        //         $jsonData = $request;
        //         $data = array(


        //             'order_id' => $jsonData->order_id ?? uniqid(),
        //         );

        // dd($data);
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api-sandbox.nowpayments.io/v1/payment/' . $value,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'x-api-key: 9WS3DBY-F8F4P16-KJB2FZY-3DJAJG0'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;

    }
}
