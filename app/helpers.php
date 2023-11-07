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
use PrevailExcel\Nowpayments\Facades\Nowpayments;
use PrevailExcel\Nowpayments\Models\Logger;


// use App\Models\LaraBlockIo;


function balance_info($userid)
{
  
    $timenow = time();
    $uinfo = User::where('id', $userid)->where('bdormant', 'N')->first(['packagedate']);

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
  
  
    $balinc = $inc - $reinvst->totreinv ;
    $ret['toti'] = $totinv;
    $ret['dinc'] = $finc;
    $ret['binc'] = round($balinc, 2);
   

    $ret['wid'] = round($sinfo->totinc, 2);

    // Access the configuration settings using the Config facade
    $service_tax = Config::get('sconfig.service_tax');
 
    $re_invest = Config::get('sconfig.re_invest');
// dd( $re_invest);
    $ret['stax'] = $stax = round(($inc * $service_tax) / 100, 2);
    
    $ret['rein'] = $rein = round((($inc - $stax) * $re_invest) / 100, 2);
   
    $ret['neta'] = round(($inc - ($stax + $rein)), 2);
  
    $ret['psharing'] = round($profit->totinc, 2);
   
    $updates = ["aboutme" => json_encode($ret)];
    
    User::where('id', $userid)->update($updates);
  
    return $ret;
}

// function api_data()
// {
//     // Define your API data here
//     $apiKey = '346b-ffb3-e51b-4e02';
//     $pin = 'apidots9779150641';

//     return ['apikey' => $apiKey, 'pin' => $pin];
// }

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

// function bitexchange($currency = 'USD')
// {
 
// $tok = token();
// $token = json_decode($tok,true);
// $usetoken =$token['token'];
// // dd($token['token']);
//     $curl = curl_init();
    
//     curl_setopt_array($curl, array(
//       CURLOPT_URL => 'https://api-sandbox.nowpayments.io/v1/conversion',
//       CURLOPT_RETURNTRANSFER => true,
//       CURLOPT_ENCODING => '',
//       CURLOPT_MAXREDIRS => 10,
//       CURLOPT_TIMEOUT => 0,
//       CURLOPT_FOLLOWLOCATION => true,
//       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//       CURLOPT_CUSTOMREQUEST => 'GET',
//       CURLOPT_POSTFIELDS =>'{
//         "amount": "50",
//         "from_currency": "btc",
//         "to_currency": "USD"
//     }',
//     CURLOPT_HTTPHEADER => array(
//         'Authorization: Bearer ' . $usetoken,
//         'Content-Type: application/json'
//     ),
    
//     ));
    
//     $response = curl_exec($curl);
//     // dd( $response);
//     curl_close($curl);
   
    
//     return $response;
    
// }
function token(){
    

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api-sandbox.nowpayments.io/v1/auth',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "email": "anuj.apidots@gmail.com",
    "password": "Anu@api0023" 
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

return $response;

}

function bitexchange ($currency = 'USD') {
	$url = 'https://bitpay.com/api/rates/'.$currency;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") ); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result= curl_exec ($ch);
	curl_close ($ch);
	$info = json_decode($result, true);
	
	$rate = $info['rate'];
 
	return $rate;
}
function dowithdrawal($uinfo, $ret, $sconfig,$request)
{
   $data = $request->all();

    $sconfig = config('sconfig');
   
    $timenow = time();


 
    $inserts = [
        "userid" => $uinfo->id,
        "tds" => $ret['stax'],
        "admincharges" => $ret['rein'],
        "bankdetails" => $uinfo->accountno,
        "processamt" => request('netamount'), 
        "chargedamount" => $ret['neta'],
        "ipaddress" => request()->ip(),
        "createdate" => $timenow
    ];
  
    $nid = InsertQry("tbl_spent", $inserts);
    $response = bitexchange();
  
   

    $amount = round(($ret['neta'] / $response), 5);

    $amount = round((request('netamount') / $response), 5);
  
//   dd($amount);
    try {
        $vard = print_r($amount, true);
        $vard = strtolower($vard);
        $updates = ['trandetails' => $amount, 'convert_date' => $timenow, 'remarks' => $vard];
        UpdateQry("tbl_spent", $updates, "id = '" . $nid . "'");
        $ret = 'success';
    } catch (Exception $e) {
        print_r('hjlk');exit;
        $error_log = $e->getMessage();
        DeleteList("tbl_spent", [$nid], "id");
        $ret = 'error';
    }
    return $ret;
}




   function InsertQry($table, $insert, $log = '') {
      // app/Helpers/DatabaseHelper.php


        $columns = implode(', ', array_keys($insert));
       
        $values = "'" . implode("', '", $insert) . "'";
       
        $query = "INSERT INTO $table ($columns) VALUES ($values)";

        try {
           
            DB::table($table)->insert($insert);
            
            $nid = DB::getPdo()->lastInsertId();
           
        } catch (\Exception $e) {
            throw new \Exception("Record was not inserted: " . $e->getMessage());
        }
        
        if ($table != 'tbl_avtivity_log' && auth()->check() && !empty($log)) {
            $timenow = now();
            $ipadd = request()->ip();
            $link = $log['link'];

            if ($log['ID'] == 'ADD') {
                $link = $log['link'] . $nid;
            }

            $insertLog = [
                "title" => $log['title'],
                "href" => $link,
                "section" => $log['section'],
                "createdate" => $timenow,
                "userid" => auth()->id(),
                "pageid" => $nid,
                "page" => $table,
                "action" => "New Record",
                "ipaddress" => $ipadd,
            ];

            DB::table('tbl_avtivity_log')->insert($insertLog);
        }

        return $nid;
  

    }




// Additional helper functions (if any) can be defined here

function random_number($numchar) {
    $str = "123456789";
    $start = mt_rand(1, (strlen($str)-$numchar));
    $string = str_shuffle($str);
    $password = substr($string, $start, $numchar);
    return $password;
}




function re_activate_investment($nid, $sconfig, $timenow = "") {
    $dinfo = DB::table('tbl_deposit')
        ->where('id', $nid)
        ->where('bActive', 'N')
        ->where('deposit_type', 'Re-Investment')
        ->select('userid', 'deposit')
        ->get();
    //  dd($dinfo[0]->userid);
$sconfig = config('sconfig.referral_payment');

    if ($dinfo->count() > 0) {
        // echo 'hj';exit;
        $refper = implode(",", $sconfig);
        
      $array = explode(",", $refper);
      
        $refext = count($array);
        
       
    
        if (empty($timenow)) {
            $timenow = time();
        }

     $check =   DB::table('tbl_deposit')
            ->where('id', $nid)
            ->update(['createdate' => $timenow, 'bActive' => 'Y']);
           
             // Replace with the actual value of userid

            $tdinfo = DB::table('tbl_deposit')
                ->select(DB::raw('SUM(deposit) as totdep'))
                ->where('userid', $dinfo[0]->userid)
                ->where('bActive', 'Y')
                ->where('deposit_type', 'Re-Investment')
                ->get();
            
           
        $updates = ['packageid' => 1, 'mentor_income' => $tdinfo[0]->totdep];
       
        DB::table('tbl_user')
            ->where('id', $dinfo[0]->userid)
            ->update($updates);
            // dd($dinfo[0]->userid);
        $dstat = DB::table('tbl_dstat')
            ->where('userid', $dinfo[0]->userid)
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
                $paidUser = DB::table('tbl_deposit')
                    ->where('userid', $stat->parentid)
                    ->where('bActive', 'Y')
                    ->where('deposit_type', 'Re-Investment')
                    ->get();
                   
                if ($paidUser->count() > 0) {
                    $per = $refper[$mn];
                  
                    $ric = ($dinfo[0]->deposit * $per) / 100;
                    
                    if ($stat->unlimited > 0) {
                        $nids[] = $stat->parentid;
                    }

                    if ($ric > 0) {
                        $inserts = [
                            'userid' => $stat->parentid,
                            'byuserid' => $dinfo[0]->userid,
                            'propertyid' => $nid,
                            'income' => $ric,
                            'incometype' => 'Level ' . $stat->levelid,
                            'incomelog' => json_encode(['deposit' => $dinfo[0]->deposit, 'level' => $stat->levelid, 'percentage' => $per]),
                            'createdate' => $timenow,
                            'activedate' => $timenow,
                        ];
                       
                        // Insert into the 'tbl_income' table
                        DB::table('tbl_income')->insert($inserts);
                    }
                    $mn++;
                }
            } else {
                break;
            }
        }

        $per = 0.5;
        foreach ($dstat as $stat) {
            $ric = ($dinfo[0]->deposit * $per) / 100;
            $paidUser = DB::table('tbl_deposit')
                ->where('userid', $stat->parentid)
                ->where('bActive', 'Y')
                ->where('deposit_type', 'Re-Investment')
                ->get();
                
            if ($paidUser->count() > 0) {
                if ($stat->unlimited > 0 && !in_array($stat->parentid, $nids)) {
                    $inserts = [
                        'userid' => $stat->parentid,
                        'byuserid' => $dinfo[0]->userid,
                        'propertyid' => $inid,
                        'income' => $ric,
                        'incometype' => 'Level ' . $stat->levelid,
                        'incomelog' => json_encode(['deposit' => $dinfo[0]->deposit, 'level' => $stat->levelid, 'percentage' => $per]),
                        'createdate' => $timenow,
                        'activedate' => $timenow,
                    ];
                    dd($inserts);
                    // Insert into the 'tbl_income' table
                    DB::table('tbl_income')->insert($inserts);
                }
            }
        }

        $con_amt1 = 100;
        $con_amt2 = 500;
        $cond_dir = 5;
        $lnt = $knt = 0;

        $uinfo = DB::table('tbl_user')
            ->where('referalid', $refid)
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
            // MARK PARENT AS UNLIMITED
            DB::table('tbl_dstat')
                ->where('parentid', $refid)
                ->where('unlimited', 0)
                ->update(['unlimited' => 1]);

            // UPDATE USER INFO
            DB::table('tbl_user')
                ->where('id', $refid)
                ->where('bmentor', 'N')
                ->update(['coursedate' => $timenow, 'bmentor' => 'Y']);
        }

        if ($lnt >= $cond_dir) {
            // UPDATE USER INFO
            DB::table('tbl_user')
                ->where('id', $refid)
                ->where('bdormant', 'N')
                ->update(['packagedate' => $timenow, 'bdormant' => 'Y']);
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

    // Perform the update query using Eloquentgenerate_block_io_address
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


function activate_investment($nid, $sconfig, $timenow = "")
{
    // dd( $sconfig['referral_payment']);
    $dinfo = Deposit::where('id', $nid)
        ->where('bActive', 'N')
        ->select('userid', 'deposit')
        ->first();
        $refper =  $sconfig['referral_payment'];
       
        $refext = count($refper) - 1;
     
        $maxlvl = count($refper);
       

    if ($dinfo) {
        
       
        if (empty($timenow)) {
          
            $timenow = time();
           
        }
       
       $user = Deposit::where('id',$nid)
            ->update(['createdate' => $timenow, 'bActive' => 'Y']);
            
           
         
            // $tdinfo = Deposit::select("sum(`deposit`)")->where(['userid'=>1,'bActive'=>'Y'])->get();

            $totalDeposit = DB::table('tbl_deposit')
            ->select(DB::raw('SUM(deposit) as totdep'))
            ->where('userid', $dinfo->userid)
            ->where('bActive', 'Y')
            ->first();
            // dd($totalDeposit);
       
            $totalDepositArray = get_object_vars($totalDeposit);
            $totdepValue = reset($totalDepositArray);

      
      $chek =  User::where('id', $dinfo->userid)
            ->update(['packageid' => '1', 'mentor_income' => $totdepValue]);
           
        $dstat = DB::table("tbl_dstat")->
        select('parentid', 'levelid', 'unlimited')
        ->where('userid', $dinfo->userid)
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
                //  dd('rrr');
                    $per = $refper[$mn];
                  
                    $ric = ($dinfo->deposit * $per) / 100;
                   
                    if ($stat->unlimited > 0) {
                        
                        $nids[] = $stat->parentid;
                      
                    }
                    if ($ric > 0) { 
                       
                        $inserts = [
                            "userid" => $stat->parentid,
                            "byuserid" => $dinfo->userid,
                            "propertyid" => $nid,
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
                // dd('jjjjjj');
                $inserts = [
                    "userid" => $stat->parentid,
                    "byuserid" => $dinfo->userid,
                    "propertyid" => $nid,
                    "income" => $ric,
                    "incometype" => "Level " . ($stat->levelid),
                    "incomelog" => '{"deposit":"' . $dinfo->deposit . '","level":"' . $stat->levelid . '","percentage":"' . $per . '"}',
                    "createdate" => $timenow,
                    "activedate" => $timenow
                ];
            
                Income::insert($inserts);
            }
        }
        
        $parentid = $dstat->first()->parentid;
       
        // CHECK REFERRAL FOR 5 x 100/500 DIRECTS
        $paidUser = Deposit::where('userid', $parentid)
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
              $db =  DB::table("tbl_dstat")
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



function check_block_io_address ($nid) {
dd($nid);
	$dinfo = Deposit::select('deposit', 'label')
    ->where('id', $nid)
    ->where('bActive', 'N')
    ->where('deposit_type', '!=', 'Re-Invest')
    ->get();

dd($dinfo);
	if(count($dinfo)){
       
        //  echo RPATH . '\vendor\blockavel\lara-block-io\src\LaraBlockIo.php';exit;
        
		$api 	= api_data();
  
		$apiKey = $api['apikey'];
		$pin 	= $api['pin'];
	
		$version = 2; // the API version
     
		$blockio = new BlockIo(
             $apiKey,
            $pin,
            $version,
          
        );

		$oid = random_number(4);
		
		$label = $oid.$nid;
		$getNewAddressInfo = "";
		$ret = array();
		
	
   
		try {
            echo 'hhhh';
			$getNewAddressInfo = $blockio->get_address_balance(array('labels' => $dinfo[0]['label']));
		
			// dd($getNewAddressInfo);
			// $ret['available_balance']= $getNewAddressInfo->data->available_balance;
			$ret['available_balance']= $getNewAddressInfo->data->available_balance;
            // print_r($ret['available_balance']);exit;
			$ret['pending_received_balance'] 	= $getNewAddressInfo->data->pending_received_balance;
            print_r($ret['pending_received_balance']);exit;

      
			if($ret['pending_received_balance']>0){
			
				$rate= bitexchange();
                // print_r($rate);exit;
				if($rate > 0){
					$deposit 	= $ret['pending_received_balance'] * $rate;
                    // print_r($deposit);exit;
					$r_deposit 	= $dinfo[0]->deposit - 1;
					if($deposit>=$r_deposit){
						$deposit = $dinfo[0]->deposit;
					}
					
					$udeposit = $deposit;
					
					if($udeposit >= 10 && $udeposit <= 99){
						$daily_percentage = 2.5;
					}
					else if($udeposit >= 100 && $udeposit <= 999){
						$daily_percentage = 3;
					}
					else if($udeposit >= 1000){
						$daily_percentage = 4;
					}
					
					$updates = array("usd_exchange"=>$rate,"p_deposit"=>$dinfo[0]->deposit,'deposit'=>$deposit,'daily_percentage'=>$daily_percentage);
				
					UpdateQry("tbl_deposit",$updates," id = '".$nid."'");
					$ret = 'success';
				}
				else {
					$ret = 'error';echo 'yyy';
				}
			}
			else {
				if($ret['pending_received_balance']>0){
					$ret = 'wait';
				}
				else {
					
				}
				$ret = 'wait';	echo 'y777yy';
			}
			
			
		} catch (Exception $e) {
			$ret['address'] = 'error';
			$ret['label'] = $label;
			$error_log = $e->getMessage();
			print_r($error_log);exit;
			$updates = array('error_log'=>$error_log);
		
			UpdateQry("tbl_deposit",$updates," id = '".$nid."'");
			$ret = 'error';
		}
	}
	return $ret;echo '000';
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
