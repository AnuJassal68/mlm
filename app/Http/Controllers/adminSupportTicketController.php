<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ticketing;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\helpers;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class adminSupportTicketController extends Controller
{
    /**
    * Get support tickets with date-based filtering.
    *
    * @param  Request  $request
    * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    */
    public function supportticket(Request $request)
    {
        $cps = [];
        $msg = ['&msg=activated', '&msg=deactivated', '&msg=deleted', '&msg=added', '&msg=updated'];
        $HTTP_REFERER = str_ireplace($msg, '', $request->server('HTTP_REFERER'));
        $timenow = time();
        $ipadd = $request->ip();
        $modes = [1 => "Technical", 2 => "Admin", 3 => "eBank", 4 => "shop"];

        $q = $request->input('q', '');
        $fo = $request->input('fo', '');
        $md = $request->input('md', 0);
        $df = $request->input('df', '');
        $dt = $request->input('dt', '');
        $o = $request->input('o', 'asc');
        $ob = $request->input('ob', 'rdate');

        $query = DB::table('tbl_ticketing as t')
            ->join('tbl_user as u', 't.userId', '=', 'u.id')
            ->where('t.isSolved', 0);

        if ($q) {
            if ($fo === 'username') {
                $query->where('u.loginid', $q);
            } elseif ($fo === 'network') {
                $refinfo = DB::table('tbl_user')->where('loginid', $q)->first();
                if ($refinfo) {
                    $query->whereRaw('(u.id LIKE ? OR u.loginid = ?)', ["%{$refinfo->id},%", $q]);
                } else {
                    $query->where('u.loginid', '0');
                }
            } else {
                $query->where(function ($query) use ($q) {
                    $query->where('u.firstname', 'like', "%{$q}%")
                        ->orWhere('t.subject', 'like', "%{$q}%")
                        ->orWhere('u.loginid', 'like', "%{$q}%")
                        ->orWhere('t.ticketId', '=', $q);
                });
            }
        }

        if ($md > 0) {
            $query->where('t.type', $md);
        }

        if ($df) {
            $ndf = date('Y/m/d', strtotime($df));
            $query->whereDate('t.created_at', '>=', $ndf);
        }

        if ($dt) {
            $ndt = date('Y/m/d', strtotime($dt));
            $query->whereDate('t.created_at', '<=', $ndt)
                ->whereTime('t.created_at', '<=', '23:59:59');
        }

        switch ($ob) {
            case "rdate":
                $query->orderBy('t.created_at', $o);
                break;
            case "name":
                $query->orderBy('u.firstname', $o);
                break;
            case "account":
                $query->orderBy('t.subject', $o);
                break;
            case "mode":
                $query->orderBy('t.type', $o);
                break;
            case "deduction":
                $query->orderBy('t.deduction', $o);
                break;
            case "amount":
                $query->orderBy('t.status', $o);
                break;
            case "trid":
                $query->orderBy('t.ticketId', $o);
                break;
            default:
                $query->orderBy('t.updated_at', $o);
        }

        $rinfo = $query->select('u.id', 'u.loginid', 'u.firstname', 'u.emailid', 't.ticketId', 't.type as mode', 't.subject', 't.message', DB::raw("DATE_FORMAT(t.created_at, '%d/%m/%y %T') AS requestedDate"), DB::raw("DATE_FORMAT(t.updated_at, '%d/%m/%y %T') AS replyDate"))
            ->get();

        $currenttotal = count($rinfo);
        $tickets = Ticketing::whereBetween('created_at', [$df, $dt])->get();
        $qry = ''; //
        return view('admin.support-ticket', compact('tickets', 'df', 'dt', 'rinfo', 'cps', 'HTTP_REFERER', 'timenow', 'ipadd', 'modes', 'q', 'md', 'o', 'ob', 'df', 'dt', 'fo', 'query', 'currenttotal', 'qry'));
    }

/**
 * Get support tickets with date-based filtering.
 *
 * @param  Request  $request
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
    public function supportclosed(Request $request)
    {
        $q = $request->input('q', '');
        $fo = $request->input('fo', '');
        $md = $request->input('md', 0);
        $df = $request->input('df', '');
        $dt = $request->input('dt', '');
        $o = $request->input('o', 'desc');
        $ob = $request->input('ob', 'rdate');

        // Build your query logic here
        $query = DB::table('tbl_ticketing AS t')
            ->join('tbl_user AS u', 't.userId', '=', 'u.id')
            ->where('t.isSolved', 1);

      
            if ($q) {
                if ($fo === 'username') {
                    $query->where('u.loginid', $q);
                } elseif ($fo === 'network') {
                    $refinfo = DB::table('tbl_user')->where('loginid', $q)->first();
                    if ($refinfo) {
                        $query->whereRaw('(u.id LIKE ? OR u.loginid = ?)', ["%{$refinfo->id},%", $q]);
                    } else {
                        $query->where('u.loginid', '0');
                    }
                } else {
                    $query->where(function ($query) use ($q) {
                        $query->where('u.firstname', 'like', "%{$q}%")
                            ->orWhere('t.subject', 'like', "%{$q}%")
                            ->orWhere('u.loginid', 'like', "%{$q}%")
                            ->orWhere('t.ticketId', '=', $q);
                    });
                }
            }
    
            if ($md > 0) {
                $query->where('t.type', $md);
            }
    
            if ($df) {
                $ndf = date('Y/m/d', strtotime($df));
                $query->whereDate('t.created_at', '>=', $ndf);
            }
    
            if ($dt) {
                $ndt = date('Y/m/d', strtotime($dt));
                $query->whereDate('t.created_at', '<=', $ndt)
                    ->whereTime('t.created_at', '<=', '23:59:59');
            }
        switch ($ob) {
            case 'rdate':
                $orderBy = 't.created_at';
                break;
            case 'name':
                $orderBy = 'u.firstname';
                break;
            case 'account':
                $orderBy = 't.subject';
                break;
            case 'mode':
                $orderBy = 't.type';
                break;
            case 'deduction':
                $orderBy = 't.deduction';
                break;
            case 'amount':
                $orderBy = 't.status';
                break;
            case 'trid':
                $orderBy = 't.ticketId';
                break;
            default:
                $orderBy = 't.updated_at';
        }


        $query->orderBy($orderBy, $o);

        $rinfo = $query->select('u.id', 'u.loginid', 'u.firstname', 'u.emailid', 't.ticketId', 't.type as mode', 't.subject', 't.message', DB::raw("DATE_FORMAT(t.created_at, '%d/%m/%y %T') AS requestedDate"), DB::raw("DATE_FORMAT(t.updated_at, '%d/%m/%y %T') AS replyDate"))
            ->get();

        $currenttotal = count($rinfo); // Calculate current total

        return view('admin.support_closed', compact('rinfo', 'q', 'fo', 'md', 'df', 'dt', 'o', 'ob', 'currenttotal'));
    }


    //get closed ticket data

    public function getClosedTicketData($ticketId)
    {
    $ticket = Ticketing::findOrFail($ticketId);

        // Retrieve the user associated with the ticket
        $user = $ticket->user;

        // Retrieve ticket messages for this ticket
        $ticketMessages = TicketMessage::where('ticketId', $ticketId)->orderBy('created_at', 'asc')->get();

        $ret = [];
        foreach ($ticketMessages as $message) {
              $ret[] = [
                'ticketMessageId' => $message->ticketMessageId,
                'message' => $message->message,
                'userId' => $message->userId,
                'created_at' => $message->created_at,
            ];
        }

        return response()->json([
            'user' => $user->firstname . ' ' . $user->lastname, // Customize according to your user model
            'ret' => $ret,
        ]);
    }
    /**
 * Display a listing of the support tickets with date-based filtering.
 *
 * @param  Request  $request
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
public function getTicketDetails(Request $request, $ticketId)
{
    if ($request->ajax()) {
        $tinfo = TicketMessage::where('ticketId', $ticketId)
            ->orderBy('ticketMessageId', 'asc')
            ->get();
        
        if (count($tinfo) > 0) {
            $uinfo = getquery("tbl_user", " AND id = '".$tinfo[0]->userId."'", "id,firstname,loginid", "N", "Ya");
            
            if (count($uinfo) > 0) {
                $user = $uinfo[0]['firstname'].' ('.$uinfo[0]['loginid'].')';
                return response()->json(['ret' => $tinfo, 'user' => $user]);
            }
        }
        
        return response()->json(['error' => 'User not found']);
    }

    return response()->json(['error' => 'Invalid request']);
}

public function updateTicketMessage(Request $request, $id)
{
    if ($request->ajax()) {
        // Validate the request data
        $request->validate([
            'msg' => 'required', // Add any additional validation rules you need
        ]);

        // Update the ticket message
     $ticketMessage = TicketMessage::find($id);

        if ($ticketMessage) {
            $ticketMessage->message = $request->input('msg');
            $ticketMessage->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Ticket message not found']);
    }

    return response()->json(['error' => 'Invalid request']);
}



    //user info details page function
    public function edit(Request $request, $id)
    {

        // Fetch user data based on $id and pass it to the view
        $binfo = DB::table('tbl_user')
        ->where('id', $id)
        ->first();// Replace with your logic to fetch user info
        $mode = $request->query('mode');
        if (!$binfo) {
            return redirect()->route('profile.edit')->with('msg', 'norecord');
        }
        $dobs = explode('/', $binfo->dob);
        $regisdate = date("d-m-y G:i:s", $binfo->createdate);
        $coninfo = getquery("tbl_country_state_city", " AND parentid = 0");
        // dd($coninfo);
        if ($binfo->packageid == 1) {
            $pkgdate = $binfo->packagedate > 0 ? date("d-m-y G:i:s", $binfo->packagedate) : $regisdate;
        } else {
            $pkgdate = ":: NONE ::";
        }
        return view('admin.profile', compact('binfo', 'regisdate', 'pkgdate', 'coninfo', 'dobs', 'mode'));
    }
    public function submitClient(Request $request, $id)
    {
        $agents = User::findOrFail($id);
        $agents->firstname = $request->input('firstname');
        $agents->dob = $request->input('dob_dd')."/".$request->input('dob_mm')."/".$request->input('dob_yy');
        $agents->accountno = $request->input('accountno');
        $agents->address = $request->input('address');
        $agents->landmark = $request->input('landmark');
        $agents->country = $request->input('country');
        $agents->city = $request->input('city');
        $agents->state = $request->input('state');
        $agents->pincode = $request->input('pincode');
        $agents->emailid = $request->input('email');
        $agents->mobile_code = $request->input('mobile_code');
        $agents->mobile = $request->input('mobile');
        $agents->fixedline_code = $request->input('fixedline_code');
        $agents->fixedline = $request->input('fixedline');
        $agents->bActive = $request->input('bactive');
        $agents->loginpassword = $request->input('loginpasswors');
        $agents->tremarks = $request->input('tremarks');
        $agents->save();

        // Create a log entry
        // if($agents) {
        //     $log = [
        //         'title' => $request->input('firstname') . ' ' . $request->input('middlename') . ' ' . $request->input('lastname') . ' (' . $agents->did . ')',
        //         'link' => route('profile.edit', ['id' => $agents->id]),
        //         'section' => 'Manage User',
        //     ];


        // }
        // else{
        //     return "anu";
        // }


        // Redirect back with an error message if form validation fails
        return redirect()->route('userlist')->with('success', 'Updated successfully');
    }


    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Delete the user
        $user->delete();

        return redirect()->route('userlist')->with('success', 'User deleted successfully.');
    }
    //end of user info function


    //reply ticket module
    public function processTicket(Request $request)
    {
        $timenow = now();
        // Assuming 'ticketId' and 'tmessage' are the input field names
        $ticketMessage = new TicketMessage();
        $ticketMessage->ticketId = $request->input('ticketId');
        $ticketMessage->userId = $request->userId;
        $ticketMessage->message = $request->input('tmessage');
        if ($ticketMessage) {
            // Update ticket status as solved
            Ticketing::where('ticketId', $ticketMessage->ticketId)->update([
                'updated_at' => $timenow,
                'isSolved' => 1
            ]);
        } elseif ($ticketMessage == 'pending') {
            // Update ticket status as pending
            Ticketing::where('ticketId', $ticketMessage->ticketId)->update([
                'isSolved' => 2
            ]);
        } elseif ($ticketMessage == 'assign') {
            $mode = $request->input('mode'); // Assuming 'mode' is the input field name


            Ticketing::where('ticketId', $ticketMessage->ticketId)->update([
                'type' => $mode
            ]);
        }
        $ticketMessage->save();

        return response()->json(['success' => true]);
    }

    //move to pending ticket function
    public function moveToPending(Request $request)
    {
        $ticketId = $request->input('ticketId');

        // Update ticket status as pending
        Ticketing::where('ticketId', $ticketId)->update([
            'isSolved' => 2
        ]);

        return response()->json(['success' => true]);
    }
   /**
 * Display a listing of the support tickets with date-based filtering.
 *
 * @param  Request  $request
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
    public function supportpending(Request $request)
    {
        $q = $request->input('q', '');
        $fo = $request->input('fo', '');
        $md = $request->input('md', 0);
        $df = $request->input('df', '');
        $dt = $request->input('dt', '');
        $ob = $request->input('ob', 'updated_at');
        $o = $request->input('o', 'desc');

        $query = Ticketing::query(); // Adjust the model class name

      
        if ($q) {
            if ($fo === 'username') {
                $query->where('u.loginid', $q);
            } elseif ($fo === 'network') {
                $refinfo = DB::table('tbl_user')->where('loginid', $q)->first();
                if ($refinfo) {
                    $query->whereRaw('(u.id LIKE ? OR u.loginid = ?)', ["%{$refinfo->id},%", $q]);
                } else {
                    $query->where('u.loginid', '0');
                }
            } else {
                $query->where(function ($query) use ($q) {
                    $query->where('u.firstname', 'like', "%{$q}%")
                        ->orWhere('t.subject', 'like', "%{$q}%")
                        ->orWhere('u.loginid', 'like', "%{$q}%")
                        ->orWhere('t.ticketId', '=', $q);
                });
            }
        }

        if ($md > 0) {
            $query->where('t.type', $md);
        }

        if ($df) {
            $ndf = date('Y/m/d', strtotime($df));
            $query->whereDate('t.created_at', '>=', $ndf);
        }

        if ($dt) {
            $ndt = date('Y/m/d', strtotime($dt));
            $query->whereDate('t.created_at', '<=', $ndt)
                ->whereTime('t.created_at', '<=', '23:59:59');
        }
        switch ($ob) {
            case 'rdate':
                $orderColumn = 't.created_at';
                break;
            case 'name':
                $orderColumn = 'u.firstname';
                break;
            case 'account':
                $orderColumn = 't.subject';
                break;
            case 'mode':
                $orderColumn = 't.type';
                break;
            default:
                $orderColumn = 't.updated_at';
        }
        $query = DB::table('tbl_ticketing AS t')
        ->join('tbl_user AS u', 't.userId', '=', 'u.id')
        ->where('t.isSolved', 2)
        ->orderBy($orderColumn, $o);

        $rinfo = $query->select('u.id', 'u.loginid', 'u.firstname', 'u.emailid', 't.ticketId', 't.type as mode', 't.subject', 't.message', 't.created_at as requestedDate', 't.updated_at as replyDate')
        ->orderBy($orderColumn, $o)
        ->get();


        $modes = [
            1 => "Technical",
            2 => "Admin",
            3 => "eBank",
            4 => "shop",
            ];

        return view('admin.support-pending', compact('rinfo', 'modes', 'q', 'fo', 'md', 'df', 'dt', 'ob', 'o'));
    }

  
}

