<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Replace with the actual User model namespace
use App\Models\Deposit; // Replace with the actual Deposit model namespace
use App\Models\Income; // Replace with the actual Income model namespace
use App\Models\Spent;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class PayoutController extends Controller
{
  /**
 * Display the payout index page with search results.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\View\View
 */

    public function payoutindex(Request $request)
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
               ->select('tbl_spent.*','tbl_spent.id as spentid', 'usr.id', 'usr.loginid AS loginid', 'usr.firstname', 'usr.lastname')
               ->orderBy('tbl_spent.id', 'DESC')->get();

        return view('admin.paid-list', compact('results', 'q'));
    }
/**
 * Handle the search request by redirecting to the payout index page.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
    public function search(Request $request)
    {
        return $this->payoutindex($request);
    }
/**
 * Export search results to an Excel CSV file.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Symfony\Component\HttpFoundation\StreamedResponse
 */
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
    /**
 * Reset the search and redirect to the payout index page.
 *
 * @return \Illuminate\Http\RedirectResponse
 */
    public function reset()
    {
        return redirect()->route('paid-list');
    }
    
    //payment function 
    public function pay(Request $request, $id) {
        
        $data = Spent::find($id);

        if ($data) {
            $action = $request->input('action');
    
            if ($action === 'pay') {
                if ($data['status'] == 0) {           
                    $update = Spent::where('id', $data['id'])->update(['status' => '1']);
                  
                    return redirect('payoutindex')->with('success', 'Amount updated successfully');
                }
            } elseif ($action === 'cancel') {
                $des = $request->input('description');
                if ($data['status'] == 0) {
                  
                    $update1 = Spent::where('id', $data['id'])->update(['status' => '2', 'description' => $des,'processamt'=> '0','tds'=> '0','admincharges'=> '0','chargedamount'=> '0','trandetails'=> '0']);
           
                    return redirect('payoutindex')->with('success', 'Payment canceled successfully');
                }
            }
        }
    
        return redirect()->route('paid-list')->with('error', 'No data found for the provided ID');
    }
    
    
     
    
    
    

    
    

}
