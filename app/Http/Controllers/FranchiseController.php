<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\helpers;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class FranchiseController extends Controller
{


/**
 * Display a list of users based on search filters.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Contracts\View\View
 */
    public function userlist(Request $request)
    {
        $timenow = time();
    
        $q = $request->input('q');
        $fo = $request->input('fo');
        $qry = $setqry = '';
        
        if ($fo == 'deg-1') {
            $qry .= $setqry .= " AND (tbl_user.rp_designation = '1') ";
        } elseif ($fo == 'deg-2') {
            $qry .= $setqry .= " AND (tbl_user.rp_designation = '2') ";
        } elseif ($fo == 'deg-3') {
            $qry .= $setqry .= " AND (tbl_user.rp_designation = '3') ";
        }
        
        if ($q != "") {
            if (empty($fo)) {
                $qry .= $setqry .= " AND (tbl_user.firstname LIKE '%" . $q . "%' OR tbl_user.middlename LIKE '%" . $q . "%' OR tbl_user.lastname LIKE '%" . $q . "%' OR tbl_user.did LIKE '" . $q . "%' OR tbl_user.loginid LIKE '" . $q . "%' OR tbl_user.emailid LIKE '" . $q . "%'  OR tbl_user.mobile LIKE '" . $q . "%' OR tbl_user.country LIKE '" . $q . "%' OR tbl_user.c_country LIKE '" . $q . "%' OR tbl_user.state LIKE '" . $q . "%' OR tbl_user.c_state LIKE '" . $q . "%' OR tbl_user.city LIKE '" . $q . "%') ";
            } elseif ($fo == 'designation') {
                $desi = array_search(strtolower($q), array_map('strtolower', $degs));
                $qry .= $setqry .= " AND (tbl_user.rp_designation = '" . $desi . "') ";
            } elseif ($fo == 'country') {
                $qry .= $setqry .= " AND (tbl_user.country LIKE '" . $q . "%' OR tbl_user.c_country LIKE '" . $q . "%' ) ";
            } elseif ($fo == 'city') {
                $qry .= $setqry .= " AND (tbl_user.city LIKE '" . $q . "%' OR tbl_user.c_city LIKE '" . $q . "%' ) ";
            } elseif ($fo == 'name') {
                $qry .= $setqry .= " AND (tbl_user.firstname LIKE '%" . $q . "%' OR tbl_user.middlename LIKE '%" . $q . "%' OR tbl_user.lastname LIKE '%" . $q . "%' ) ";
            } elseif ($fo == 'id') {
                $qry .= $setqry .= " AND (tbl_user.loginid LIKE '%" . $q . "%' OR tbl_user.did LIKE '%" . $q . "%' ) ";
            }
        }
        
        $pt = $f = $request->input('f');
        
        if (!empty($pt)) {
            if ($pt == 'ac_active') {
                $setqry .= " AND tbl_user.packageid > 0 ";
            } elseif ($pt == 'ac_inactive') {
                $setqry .= " AND tbl_user.packageid = 0 ";
            } elseif ($pt == 'lo_active') {
                $setqry .= " AND tbl_user.bActive = 'Y' ";
            } elseif ($pt == 'lo_inactive') {
                $setqry .= " AND tbl_user.bActive = 'N' ";
            } elseif ($pt == 'dormant') {
                $setqry .= " AND tbl_user.bdormant = 'Y' ";
            }
        }
        
 
        $degs = array(1 => "IR Consultant", 2 => "Ex Officer", 3 => "Manager");
           $qinfo = getquery("tbl_user", $setqry . "  ORDER BY  id DESC ", "tbl_user.id,tbl_user.referalid,tbl_user.did,tbl_user.loginid,tbl_user.firstname,tbl_user.middlename,tbl_user.lastname,tbl_user.bActive,tbl_user.createdate,tbl_user.aboutme,tbl_user.bemail", "Y", "Ya");
         $refinfo = $refids = array();

       $refids = []; 

        foreach ($qinfo as $result) {
            if ( isset($result['referalid'])) {
                $refids[] = $result['referalid'];
            }
        }
        
    
        if (!empty($refids)) {
           $rinfo = getquery("tbl_user", " AND id IN (" . implode(",", $refids) . ")", "id,firstname,middlename,lastname,loginid");
    
            for ($i = 0; $i < count($rinfo); $i++) {
                $refinfo[$rinfo[$i]['id']] = ucfirst($rinfo[$i]['firstname']) . " " . ucfirst($rinfo[$i]['middlename']) . " " . ucfirst($rinfo[$i]['lastname']) . " (" . $rinfo[$i]['loginid'] . ")";
            }
        }
    
        // Your remaining code...
    
        $fullset = 'Y';
  
        // $uinva = null; // Initialize $uinv to a default value

        // foreach ($qinfo as $result) {
        //     if (isset($result['aboutme'])) {
        //         $decodedData = json_decode($result['aboutme']);
        //         if ($decodedData !== null) {
        //             $uinva[] = $decodedData;
        //         }
        //     }
        // }
     
        // You can pass the data to the view
        return view('admin.userlist', compact('qinfo', 'refinfo',  'q', 'fo', 'f', 'qry', 'fullset'));
    }


    /**
    *
    *
    * Active,inactive and delete all function
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function agentsStatus(Request $request)
    {
        $data = $request->all();

        if (!array_key_exists("checkbox", $data)) {
            return redirect('userlist');
        } else {
            // Active data
            if ($data['bulk'] == "Inactive") {
                $change = User::whereIn('id', $data['checkbox'])->get();
                foreach ($change as $value) {
                    if ($value['bActive'] == 'Y') {
                        $value['bActive'] = 'N';
                    }
                    $value->update();
                }
                return redirect('userlist')->with('success', 'Inactive successfully');
            }
            // Inactive the data
            elseif ($data['bulk'] == "Active") {
                $change = User::whereIn('id', $data['checkbox'])->get();
                foreach ($change as $value) {
                    if ($value['bActive'] == 'N') {
                        $value['bActive'] = 'Y';
                    }
                    $value->update();
                }
                return redirect('userlist')->with('success', 'Active successfully');
            }
            // delete the bulk records and single records
            elseif ($data['bulk'] == "Delete") {
                $change = User::whereIn('id', $data['checkbox'])->get();
                foreach ($change as $value) {
                    if ($value['bActive'] == 'Active' || $value['bActive'] == 'Inactive') {
                        $value['bActive'] = null;
                    }
                    $value->delete();
                }
                return redirect('userlist')->with('success', 'Deleted successfully');
            } else {
                $change = User::whereIn('id', $data['checkbox'])->get();
                foreach ($change as $value) {
                    if ($value['bActive'] == 1) {
                        $value['bActive'] = null;
                    }
                    $value->update();
                }
            }

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Operation performed successfully!']);
            } else {
                return redirect('userlist');
            }
        }
    }

  /**
 * Handle user search and filter parameters, then redirect to the 'userlist' route.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function usersearch(Request $request)
{
    try {

        $q = $request->input('q');
        $fo = $request->input('fo');
        $f = $request->input('f');

        $urlParams = http_build_query([
            'q' => $q,
            'fo' => $fo,
            'f' => $f
        ]);

        return redirect()->route('userlist')->with('urlParams', $urlParams);

    } catch (\Exception $e) {
       
        return redirect()->route('userlist')->with('error', 'An error occurred while processing your request.');
    }
}

/**
 * Generate an Excel file based on search and filter parameters and return it as a response.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function generateExcel(Request $request)
{
    try {
      
        $q = $request->input('q');
        $fo = $request->input('fo');
        $f = $request->input('f');

     
        $franchiseData = User::select('createdate', 'loginid', 'firstname', 'middlename', 'lastname', 'referalid', 'dob', 'emailid', 'mobile', 'fixedline', 'address', 'city', 'state', 'pincode', 'country')
            ->where('tbl_user.firstname', 'LIKE', '%' . $q . '%')
            ->where('tbl_user.lastname', 'LIKE', '%' . $q . '%')
            ->where('tbl_user.pincode', 'LIKE', '%' . $q . '%')
            ->where('tbl_user.address', 'LIKE', '%' . $q . '%')
            ->where('tbl_user.mobile', 'LIKE', '%' . $q . '%')
            ->where('tbl_user.fixedline', 'LIKE', '%' . $q . '%')
            ->where('tbl_user.state', 'LIKE', '%' . $q . '%')
            ->where('tbl_user.city', 'LIKE', '%' . $q . '%')
            ->where('tbl_user.state', 'LIKE', '%' . $q . '%')
            ->where('tbl_user.country', 'LIKE', '%' . $q . '%')
         
            ->orderBy('tbl_user.createdate', 'asc')
            ->get();

        // Generate the Excel content manually
        $excelContent = "REGISTER DATE\tID\tFULL NAME\tREFERRAL INFO\tDATE OF BIRTH\tEMAIL ID\tMOBILE\tFIXED LINE\tPOSTAL ADDRESS\tCITY\tSTATE\tPINCODE\tCOUNTRY\n";

        foreach ($franchiseData as $franchise) {
            $createdate = \Carbon\Carbon::createFromTimestamp($franchise->createdate);

            $excelContent .= $createdate->format('d M, Y H:i:s') . "\t" .
                $franchise->loginid . "\t" .
                $franchise->firstname . ' ' . $franchise->middlename . ' ' . $franchise->lastname . "\t" .
                $franchise->referalid . "\t" .
                $franchise->dob . "\t" .
                $franchise->emailid . "\t" .
                $franchise->mobile . "\t" .
                $franchise->fixedline . "\t" .
                $franchise->address . "\t" .
                $franchise->city . "\t" .
                $franchise->state . "\t" .
                $franchise->pincode . "\t" .
                $franchise->country . "\n";
        }

        $fileName = 'franchise_data_' . date('Y-m-d_H:i:s') . '.xls';
        return response()->make($excelContent, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);

    } catch (\Exception $e) {
    
        return redirect()->route('userlist')->with('error', 'An error occurred while generating the Excel file.');
    }
}


}
