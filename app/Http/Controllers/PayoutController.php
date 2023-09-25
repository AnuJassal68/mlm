<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Replace with the actual User model namespace
use App\Models\Deposit; // Replace with the actual Deposit model namespace
use App\Models\Income; // Replace with the actual Income model namespace
use App\Models\Spent;
use Illuminate\Support\Facades\Response;
class PayoutController extends Controller
{
    //

    public function payoutindex(Request $request){

        
    
        $q = $request->input('q', '');
     $results = Spent::join('tbl_user AS usr', 'tbl_spent.userid', '=', 'usr.id')
            ->where('usr.firstname', 'LIKE', '%' . $q . '%')
            ->orWhere('usr.middlename', 'LIKE', '%' . $q . '%')
            ->orWhere('usr.lastname', 'LIKE', '%' . $q . '%')
            ->orWhere('usr.did', 'LIKE', $q . '%')
            ->orWhere('usr.loginid', 'LIKE', $q . '%')
            ->orWhere('usr.emailid', 'LIKE', $q . '%')
            ->orWhere('usr.mobile', 'LIKE', $q . '%')
            ->select('tbl_spent.*', 'usr.id', 'usr.loginid AS loginid', 'usr.firstname', 'usr.lastname')
            ->orderBy('tbl_spent.id', 'DESC')->get();
          
        return view('admin.paid-list',compact('results','q'));
    }

    public function search(Request $request)
    {
        return $this->payoutindex($request);
    }

 


    // ...
    
  
public function excel(Request $request)
{
    $q = $request->input('q', '');

    $results = Spent::join('tbl_user AS usr', 'tbl_spent.userid', '=', 'usr.id')
        ->where('usr.firstname', 'LIKE', '%' . $q . '%')
        ->orWhere('usr.middlename', 'LIKE', '%' . $q . '%')
        ->orWhere('usr.lastname', 'LIKE', '%' . $q . '%')
        ->orWhere('usr.did', 'LIKE', $q . '%')
        ->orWhere('usr.loginid', 'LIKE', $q . '%')
        ->orWhere('usr.emailid', 'LIKE', $q . '%')
        ->orWhere('usr.mobile', 'LIKE', $q . '%')
        ->select('tbl_spent.*', 'usr.id', 'usr.loginid AS loginid', 'usr.firstname', 'usr.lastname')
        ->orderBy('tbl_spent.id', 'DESC')
        ->get();

    $data = [];
    foreach ($results as $row) {
        $data[] = [
            'Date' => date('d/m/Y G:i', $row->createdate), // Format the date as "15/08/2017 9:16"
            'User Info' => strtoupper($row->username), // Remove HTML tags from "User Info"
            'Username' => $row->loginid,
            'Bit Account' => $row->bankdetails,
            'Withdrawal' => $row->processamt,
            'Deduction' => $row->tds,
            'Re-Invest' => $row->admincharges,
            'Net Withdrawal' => $row->chargedamount,
        ];
    }

    $fileName = 'payouts.csv';
    $headerRow = array_keys($data[0]);

    $callback = function () use ($data, $headerRow) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $headerRow);

        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    };

    return Response::stream($callback, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ]);
}
    public function reset()
    {
        return redirect()->route('paid-list');
    }
}
