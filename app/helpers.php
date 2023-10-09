<?php

/**
 * Helper function to get balance information for the user.
 *
 * @param \App\Models\User $user
 * @param array $sconfig
 * @return array
 */
// app/helpers.php

use Illuminate\Support\Arr;
use App\Models\Deposit;
use App\Models\Income;
use App\Models\Spent;
use App\Models\SpentExtra;
use App\Models\ProfitData;
use App\Models\User;
// use BlockIo\BlockIo;
use GuzzleHttp\Client;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Collection;
use Blockavel\LaraBlockIo\LaraBlockIoFacade;
use App\Models\LaraBlockIo;


function balance_info($userid)
{
    $timenow = time();
    $uinfo = User::where('id', $userid)->where('bdormant', 'Y')->first(['packagedate']);
    $raise_per = 4;
    $today = $tdday = mktime(23, 59, 59, date('n'), date('j'), date('Y'));

    if ($uinfo && $uinfo->packagedate > 0) {
        $pkdt = $uinfo->packagedate;
        $tdday = mktime(23, 59, 59, date('n', $pkdt), date('j', $pkdt), date('Y', $pkdt));
        $ntdday = mktime(23, 59, 59, date('n'), date('j'), date('Y'));
        $apkdt = $tdday;
    }

    $rinfo = Deposit::where('userid', $userid)
        ->where('bActive', 'Y')
        ->where('deposit_type', '!=', 'Re-Invest')
        ->where('createdate', '<=', $timenow)
        ->selectRaw('SUM(deposit) AS totinv, SUM(p_return) as finc')
        ->first();

    $finc = $rinfo->finc * 1;
    $totinv = $rinfo->totinv * 1;

    $reinvst = Deposit::where('userid', $userid)
        ->where('deposit_type', 'Re-Investment')
        ->selectRaw('SUM(deposit) AS totreinv')
        ->first();

    $iinfo = Income::where('userid', $userid)->selectRaw('SUM(income) AS totinc')->first();
    $sinfo = Spent::where('userid', $userid)->selectRaw('SUM(processamt) AS totinc')->first();
    $seinfo = SpentExtra::where('userid', $userid)->selectRaw('SUM(processamt) AS totinc')->first();
    $profit = ProfitData::where('uid', $userid)->selectRaw('SUM(sharing_incentive) AS totinc')->first();

    $inc = $iinfo->totinc + $finc;

    $ret['inc'] = round($inc, 2) + $profit->totinc;

    $inc = round(($inc - ($sinfo->totinc + $seinfo->totinc)), 2);
    $inc = $inc + $profit->totinc;

    $balinc = $inc - $reinvst->totreinv;
    $ret['toti'] = $totinv;
    $ret['dinc'] = $finc;
    $ret['binc'] = round($balinc, 2);
    $ret['wid'] = round($sinfo->totinc, 2);

    // Access the configuration settings using the Config facade
    $service_tax = Config::get('sconfig.service_tax');
    $re_invest = Config::get('sconfig.re_invest');

    $ret['stax'] = $stax = round(($inc * $service_tax) / 100, 2);
    $ret['rein'] = $rein = round((($inc - $stax) * $re_invest) / 100, 2);

    $ret['neta'] = round(($inc - ($stax + $rein)), 2);
    $ret['psharing'] = round($profit->totinc, 2);

    $updates = ["aboutme" => json_encode($ret)];

    User::where('id', $userid)->update($updates);

    return $ret;
}




function bitexchange($currency = 'USD')
{
    $url = 'https://bitpay.com/api/rates/' . $currency;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15"));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    $info = json_decode($result, true);
    // dd($info );
    $rate = $info['rate'];
    return $rate;
}


function api_data()
{
    // Define your API data here
    $apiKey = '971c-018a-1473-1192';
    $pin = 'apidots9779150641';

    return ['apikey' => $apiKey, 'pin' => $pin];
}

function DeleteList($table, $id, $parameters, $echo = '')
{
    $comma_separated = implode(",", $id);
    $query = "DELETE FROM " . $table . " WHERE " . $parameters . " IN (" . $comma_separated . ")";

    if ($echo == 1) {
        echo $query;
        exit;
    } else {
        DB::statement($query);
    }
}



function dowithdrawal($uinfo, $ret, $sconfig)
{
    $sconfig = config('sconfig');
    $timenow = time();

    // Include the required dependencies
   
    // Insert data into the 'tbl_spent' table
    $inserts = [
        "userid" => $uinfo->id,
        "tds" => $ret['stax'],
        "admincharges" => $ret['rein'],
        "bankdetails" => $uinfo->accountno,
        "processamt" => request('netamount'), // Use Laravel's request() function to access post data
        "chargedamount" => $ret['neta'],
        "ipaddress" => request()->ip(), // Use Laravel's request()->ip() function to get the client's IP address
        "createdate" => $timenow
    ];
   
    $nid = InsertQry("tbl_spent", $inserts);

    // Get API data
    $api = api_data();
    $apiKey = $api['apikey'];
    $pin = $api['pin'];

    $version = 2; // the API version

    // Create the Guzzle HTTP client with the configuration options as an array
    $config = [
        'base_uri' => 'https://block.io/api/v2/',
        'timeout' => 10,
        // Add any additional headers here if needed
    ];

    $block_io = new Client($config); // Use the $config array here

    $rate = bitexchange();

    $amount = round(($ret['neta'] / $rate), 5);

    $amount = round((request('netamount') / $rate), 5);

    try {
        $getinfo = $block_io->withdraw(array('amounts' => $amount, 'to_addresses' => $uinfo[0]->accountno));

        // Print the result for debugging (optional)
        // print_r($getinfo);

        $vard = print_r($getinfo, true);
        $vard = strtolower($vard);

        $updates = ['trandetails' => $amount, 'convert_date' => $timenow, 'remarks' => $vard];
        UpdateQry("tbl_spent", $updates, "id = '" . $nid . "'");

        $ret = 'success';
    } catch (Exception $e) {
        $error_log = $e->getMessage();
        DeleteList("tbl_spent", [$nid], "id");
        $ret = 'error';
    }
    return $ret;
}



// app/Helpers/CustomHelpers.php

function InsertQry($table, $insert, $log = '')
{
    $query = 'INSERT INTO ' . $table . ' (';
    foreach ($insert as $key => $value) {
        $query .= "$key,";
    }
    $query = substr($query, 0, -1) . ') VALUES (';
    foreach ($insert as $key => $value) {
        $query .= "'$value',";
    }
    $query = substr($query, 0, -1) . ')';

    DB::beginTransaction();
    try {
        $result = DB::insert($query);
        $nid = DB::getPdo()->lastInsertId();

        if ($table != 'tbl_avtivity_log' && Auth::check() && !empty($log)) {
            $timenow = time();
            $ipadd = Request::ip();
            $link = $log['link'];
            if ($log['ID'] == 'ADD') {
                $link = $log['link'] . $nid;
            }
            $insertLog = array(
                "title" => $log['title'],
                "href" => $link,
                "section" => $log['section'],
                "createdate" => $timenow,
                "userid" => Auth::user()->id,
                "pageid" => $nid,
                "page" => $table,
                "action" => "New Record",
                "ipaddress" => $ipadd,
            );
            DB::table('tbl_avtivity_log')->insert($insertLog);
        }

        DB::commit();
        return $nid;
    } catch (Exception $e) {
        DB::rollback();
        throw $e;
    }
}


    function generate_block_io_address($nid)
    {
        $api = api_data();
        $apiKey = $api['apikey'];
        $pin = $api['pin'];

        $version = 2; // the API version

        $client = new Client([
            'base_uri' => 'https://block.io/api/v2/bitcoinspurchase',
            'timeout' => 10,
            'headers' => [
                // Add any additional headers here if needed
            ],
        ]);

        $oid = random_number(4);

        $label = $oid . $nid;
        $getNewAddressInfo = "";
        $ret = array();
        $error_log = "";

        $response = $client->get('https://api.block.io/v2/bitcoinspurchase', [
            'query' => [
                'label' => $label,
                'apiKey' => $apiKey,
                'pin' => $pin,
                'version' => $version,
            ],
        ]);

        dd( $response);
        
        if ($response->getStatusCode() !== 200) {
            // Handle non-200 status code here
            echo "API request failed with status code: " . $response->getStatusCode();
        } else {
            $data = json_decode($response->getBody());
            // Process the response data
            $ret['address'] = $data->data->address;
            $ret['label'] = $data->data->label;
        }
        
    echo'0000';
        $updates = ["address" => $ret['address'], "label" => $ret['label'], 'error_log' => $error_log];

        UpdateQry("tbl_deposit", $updates, " id = '" . $nid . "'");
    }



// Additional helper functions (if any) can be defined here

function random_number($numchar) {
    $str = "123456789";
    $start = mt_rand(1, (strlen($str)-$numchar));
    $string = str_shuffle($str);
    $password = substr($string, $start, $numchar);
    return $password;
}


function re_activate_investment($inid, $sconfig, $timenow = "")
{
  return  $sconfig = config('sconfig');
    $dinfo = Deposit::where('id', $inid)
        ->where('bActive', 'N')
        ->where('deposit_type', 'Re-Investment')
        ->select('userid', 'deposit')
        ->get();

    if ($dinfo->isNotEmpty()) {
        $refper = explode(",", $sconfig->referral_payment);
        $maxlvl = count($refper);
        if (empty($timenow)) {
            $timenow = time();
        }

        Deposit::where('id', $inid)
            ->where('bActive', 'N')
            ->where('deposit_type', 'Re-Investment')
            ->update([
                'createdate' => $timenow,
                'bActive' => 'Y'
            ]);

        $tdinfo = Deposit::where('userid', $dinfo[0]->userid)
            ->where('bActive', 'Y')
            ->where('deposit_type', 'Re-Investment')
            ->selectRaw('SUM(deposit) as totdep')
            ->first();

        User::where('id', $dinfo[0]->userid)
            ->update([
                'packageid' => '1',
                'mentor_income' => $tdinfo->totdep
            ]);

        $dstat = User::where('userid', $dinfo[0]->userid)
            ->select('parentid', 'levelid', 'unlimited')
            ->get();

        $nids = [0];
        $refid = 0;
        $mn = 0;
        foreach ($dstat as $stat) {
            if ($stat->levelid == 1) {
                $refid = $stat->parentid;
            }
            if ($mn < 3) {
                $paidUser = Deposit::where('userid', $stat->parentid)
                    ->where('bActive', 'Y')
                    ->where('deposit_type', 'Re-Investment')
                    ->get();

                if ($paidUser->isNotEmpty()) {
                    $per = $refper[$mn];
                    $ric = ($dinfo[0]->deposit * $per) / 100;

                    if ($stat->unlimited > 0) {
                        $nids[] = $stat->parentid;
                    }
                    if ($ric > 0) {
                        $inserts = [
                            "userid" => $stat->parentid,
                            "byuserid" => $dinfo[0]->userid,
                            "propertyid" => $inid,
                            "income" => $ric,
                            "incometype" => "Level " . $stat->levelid,
                            "incomelog" => '{"deposit":"' . $dinfo[0]->deposit . '","level":"' . $stat->levelid . '","percentage":"' . $per . '"}',
                            "createdate" => $timenow,
                            "activedate" => $timenow
                        ];
                        // InsertQry("tbl_income", $inserts);
                    }
                    $mn++;
                }
            } else {
                break;
            }
        }

        $per = '0.5';
        foreach ($dstat as $stat) {
            $ric = ($dinfo[0]->deposit * $per) / 100;
            $paidUser = Deposit::where('userid', $stat->parentid)
                ->where('bActive', 'Y')
                ->where('deposit_type', 'Re-Investment')
                ->get();

            if ($paidUser->isNotEmpty()) {
                if ($stat->unlimited > 0 && !in_array($stat->parentid, $nids)) {
                    $inserts = [
                        "userid" => $stat->parentid,
                        "byuserid" => $dinfo[0]->userid,
                        "propertyid" => $inid,
                        "income" => $ric,
                        "incometype" => "Level " . $stat->levelid,
                        "incomelog" => '{"deposit":"' . $dinfo[0]->deposit . '","level":"' . $stat->levelid . '","percentage":"' . $per . '"}',
                        "createdate" => $timenow,
                        "activedate" => $timenow
                    ];
                    // InsertQry("tbl_income", $inserts);
                }
            }
        }

        $paidUser = Deposit::where('userid', $stat->parentid)
            ->where('bActive', 'Y')
            ->where('deposit_type', 'Re-Investment')
            ->get();

        if ($paidUser->isNotEmpty()) {
            $con_amt1 = 100;
            $con_amt2 = 500;
            $cond_dir = 5;
            $lnt = $knt = 0;
            $uinfo = User::where('referalid', $refid)
                ->select('id', 'mentor_income')
                ->get();

            foreach ($uinfo as $user) {
                if ($user->mentor_income >= $con_amt1) {
                    $knt += 1;
                }

                if ($user->mentor_income >= $con_amt2) {
                    $lnt += 1;
                }
            }

            if ($knt >= $cond_dir) {
                User::where('id', $refid)
                    ->where('bmentor', 'N')
                    ->update([
                        'unlimited' => 1,
                        'coursedate' => $timenow,
                        'bmentor' => 'Y'
                    ]);
            }

            if ($lnt >= $cond_dir) {
                User::where('id', $refid)
                    ->where('bdormant', 'N')
                    ->update([
                        'packagedate' => $timenow,
                        'bdormant' => 'Y'
                    ]);
            }
        }
    }
}

// app/Helpers/CustomHelpers.php


    function getRealIpAddr()
    {
        if (!empty(request()->server('HTTP_CLIENT_IP'))) {   // Check if the IP is from shared internet
            $ip = request()->server('HTTP_CLIENT_IP');
        } elseif (!empty(request()->server('HTTP_X_FORWARDED_FOR'))) {   // Check if the IP is passed from a proxy
            $ip = request()->server('HTTP_X_FORWARDED_FOR');
        } else {
            $ip = request()->server('REMOTE_ADDR');
        }

        return $ip;
    }
//alertbox
function alert_box($type = 'info')
{
    $msg = Session::get('alert_msg');

    if (!empty($msg)) {
        echo '<div class="alert alert-' . $type . '">' . $msg . '</div>';
    }
}
//update function
function updateQry($table, $update, $parameters, $log = '')
{
    $query = 'UPDATE ' . $table . ' SET ';
    $bindings = [];
    $keys = [];

    foreach ($update as $key => $value) {
        $query .= "$key = ?,";
        $keys[] = $key;
        $bindings[] = $value;
    }

    $query = substr($query, 0, -1) . ' WHERE ' . $parameters;

    // Perform the update query using Eloquent
    $log = is_string($log) ? ['counter' => $log] : $log;
    if (Arr::has($log, 'counter') && $log['counter'] !== '0') {
        // Perform the update query using Eloquent
        DB::update($query, $bindings);
        
        // Log activity if required
        // ...
    }

    // Log activity if required
    if (!in_array($table, ['tbl_avtivity_log', 'tbl_income']) && session('ADMIN.ID') > 0 && $log !== '') {
        $affectedRows = DB::select('SELECT ' . implode(',', $keys) . ' FROM ' . $table . ' WHERE ' . $parameters);

        $timenow = time();
        $ipaddr	 = getRealIpAddr();

        foreach ($affectedRows as $row) {
            $tbl = '<table class="table table-bordered wid90">';
            $cnt = 0;

            foreach ($keys as $k) {
                if ($k !== 'id') {
                    $or = $row->$k;
                    $nr = $update[$k];

                    if ($or !== $nr && !empty($or) && !empty($nr)) {
                        $k = str_replace(['-', '_'], ' ', $k);
                        $k = ucwords($k);

                        $tbl .= '<tr><td>' . $k . '</td><td>' . e($or) . '</td><td>' . e($nr) . '</td></tr>';
                        $cnt++;
                    }
                }
            }

            $tbl .= '</table>';

            if ($cnt > 0) {
                $insert = [
                    'title' => $log['title'],
                    'href' => $log['link'],
                    'section' => $log['section'],
                    'createdate' => $timenow,
                    'counter' => ($log['counter'] === '0' ? 0 : 2),
                    'userid' => session('ADMIN.ID'),
                    'pageid' => $row->id,
                    'page' => $table,
                    'action' => $tbl,
                    'sqlquery' => $query,
                    'ipaddress' => $ipaddr,
                ];

                ActivityLog::create($insert);
            }
        }
    }

    return true;
}

function activate_investment($inid, $sconfig, $timenow = "")
{
    $dinfo = Deposit::where('id', $inid)
        ->where('bActive', 'N')
        ->select('userid', 'deposit')
        ->first();

    if ($dinfo) {
        $refper = explode(",", $sconfig->referral_payment);
        $refext = count($refper) - 1;
        $maxlvl = count($refper);

        if (empty($timenow)) {
            $timenow = time();
        }

        Deposit::where('id', $inid)
            ->update(['createdate' => $timenow, 'bActive' => 'Y']);

        $tdinfo = Deposit::where('userid', $dinfo->userid)
            ->where('bActive', 'Y')
            ->select(DB::raw('SUM(deposit) AS totdep'))
            ->first();

        User::where('id', $dinfo->userid)
            ->update(['packageid' => '1', 'mentor_income' => $tdinfo->totdep]);

        $dstat = DB::table("tbl_dstat")
            ->select("parentid", "levelid", "unlimited")
            ->where("userid", $dinfo->userid)
            ->get();

        $nids = [0];
        $refid = 0;

        // CIRCULATE INCOME UPWARDS UPTO GIVEN LEVEL
        $mn = 0;
        foreach ($dstat as $stat) {
            if ($stat->levelid == 1) {
                $refid = $stat->parentid;
            }
            if ($mn < 5) {

                $paidUser = Deposit::where('userid', $stat->parentid)
                    ->where('bActive', 'Y')
                    ->first();

                if ($paidUser) {
                    $per = $refper[$mn];
                    $ric = ($dinfo->deposit * $per) / 100;

                    if ($stat->unlimited > 0) {
                        $nids[] = $stat->parentid;
                    }
                    if ($ric > 0) {
                        $inserts = [
                            "userid" => $stat->parentid,
                            "byuserid" => $dinfo->userid,
                            "propertyid" => $inid,
                            "income" => $ric,
                            "incometype" => "Level " . ($stat->levelid),
                            "incomelog" => '{"deposit":"' . $dinfo->deposit . '","level":"' . $stat->levelid . '","percentage":"' . $per . '"}',
                            "createdate" => $timenow,
                            "activedate" => $timenow
                        ];
                        Income::insert($inserts);
                    }
                    $mn++;
                }
            } else {
                break;
            }
        }

        $per = 0.5;
        foreach ($dstat as $stat) {
            $ric = ($dinfo->deposit * $per) / 100;
            $paidUser = Deposit::where('userid', $stat->parentid)
                ->where('bActive', 'Y')
                ->first();
        
            if ($paidUser && $stat->unlimited > 0 && !in_array($stat->parentid, $nids)) {
                $inserts = [
                    "userid" => $stat->parentid,
                    "byuserid" => $dinfo->userid,
                    "propertyid" => $inid,
                    "income" => $ric,
                    "incometype" => "Level " . ($stat->levelid),
                    "incomelog" => '{"deposit":"' . $dinfo->deposit . '","level":"' . $stat->levelid . '","percentage":"' . $per . '"}',
                    "createdate" => $timenow,
                    "activedate" => $timenow
                ];
        
                Income::insert($inserts);
            }
        }
        
        // CHECK REFERRAL FOR 5 x 100/500 DIRECTS
        $paidUser = Deposit::where('userid', $dstat[$i]->parentid)
            ->where('bActive', 'Y')
            ->first();
        
        if ($paidUser) {
            $con_amt1 = 100;
            $con_amt2 = 500;
            $cond_dir = 5;
            $lnt = $knt = 0;
        
            $uinfo = User::where('referalid', $refid)
                ->select('id', 'mentor_income')
                ->get();
        
            foreach ($uinfo as $user) {
                // UNLIMITED CONDITION
                if ($user->mentor_income >= $con_amt1) {
                    $knt += 1;
                }
        
                // RAISE TO 4%
                if ($user->mentor_income >= $con_amt2) {
                    $lnt += 1;
                }
            }
        
            if ($knt >= $cond_dir) {
                // MARK PARENT AS UNLIMITED
                DB::table("tbl_dstat")
                    ->where('parentid', $refid)
                    ->where('unlimited', '0')
                    ->update(['unlimited' => 1]);
        
                // UPDATE USER INFO
                User::where('id', $refid)
                    ->where('bmentor', 'N')
                    ->update(['coursedate' => $timenow, 'bmentor' => 'Y']);
            }
        
            // MARK RAISE TO 4%
            if ($lnt >= $cond_dir) {
                // UPDATE USER INFO
                User::where('id', $refid)
                    ->where('bdormant', 'N')
                    ->update(['packagedate' => $timenow, 'bdormant' => 'Y']);
            }
        }
        

    }
}



function check_block_io_address($nid)
{
 $dinfo = DB::table("tbl_deposit")
        ->select("deposit", "label")
        ->where("id", $nid)
        ->where("bActive", "N")
        ->where("deposit_type", "!=", "Re-Invest")
        ->first();

    if ($dinfo) {
      $api = api_data();
        $apiKey = $api['apikey'];
        $pin = $api['pin'];

        $version = 2; // the API version

        // Create a Guzzle Client instance
        //    $client = new LaraBlockIo([
        //     'base_uri' => 'https://block.io/api/v2/', // Update with the correct base URL
        //     'timeout' => 10,
        // ]);
// return $use = LaraBlockIo::test();
// dd($use);
        $oid = random_number(4);
        $label = $oid . $nid;

        $getNewAddressInfo = "";
        $ret = array();

        try {
            // Send a GET request using Guzzle Client
            $response = $client->get('your_api_endpoint_here', [ // Replace 'your_api_endpoint_here' with the actual API endpoint
                'query' => [
                    'labels' => $dinfo->label,
                    'apiKey' => $apiKey,
                    'pin' => $pin,
                    'version' => $version,
                ],
            ]);
dd( $response);
            // Parse the response JSON
            $data = json_decode($response->getBody());

            $ret['available_balance'] = $data->data->balances[0]->available_balance;
            $ret['pending_received_balance'] = $data->data->balances[0]->pending_received_balance;

            if ($ret['pending_received_balance'] > 0) {
                $rate = bitexchange();
                if ($rate > 0) {
                    $deposit = $ret['pending_received_balance'] * $rate;
                    $r_deposit = $dinfo->deposit - 1;
                    if ($deposit >= $r_deposit) {
                        $deposit = $dinfo->deposit;
                    }

                    $udeposit = $deposit;

                    if ($udeposit >= 10 && $udeposit <= 99) {
                        $daily_percentage = 2.5;
                    } else if ($udeposit >= 100 && $udeposit <= 999) {
                        $daily_percentage = 3;
                    } else if ($udeposit >= 1000) {
                        $daily_percentage = 4;
                    }

                    // Update the record in the database using DB facade
                    DB::table("tbl_deposit")
                        ->where("id", $nid)
                        ->update([
                            "usd_exchange" => $rate,
                            "p_deposit" => $dinfo->deposit,
                            'deposit' => $deposit,
                            'daily_percentage' => $daily_percentage,
                        ]);

                    $ret = 'success';
                } else {
                    $ret = 'error';
                }
            } else {
                $ret = 'wait';
            }
        } catch (\Exception $e) {
            $error_log = $e->getMessage();

            // Update the error_log in the database using DB facade
            DB::table("tbl_deposit")
                ->where("id", $nid)
                ->update(['error_log' => $error_log]);

            $ret = 'error';
        }
    }

    return $ret;
}



function createresult($result, $fetch = "", $debug = "N")
{
    $data = [];
    $numRows = count($result);
    
    if ($numRows > 0) {
        $columns = array_keys((array) $result[0]);
        
        foreach ($result as $row) {
            $rowData = [];
            foreach ($columns as $column) {
                $rowData[$column] = $row->$column;
            }
            $data[] = $rowData;
        }
    }
    
    $collection = new Collection($data);

    if ($debug == 'Y') {
        // You can log the collection data for debugging purposes
        logger($collection);
    }

    return $collection;
}


function getquery($table, $showtype, $fetch = "*", $paging = "N", $debug = "N")
{
  $query = DB::table($table)
        ->select(DB::raw($fetch))
        ->whereRaw('1 ' . $showtype);

    if ($debug == 'Y') {
        $query->toSql();
        
    }
    // echo $query->toSql();
    //     exit;
   $result= $query->get();
    return createresult($result,$fetch,$debug = "Y");
    
}


?>
