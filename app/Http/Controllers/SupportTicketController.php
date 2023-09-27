<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticketing;
use App\Models\User;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\helpers;

class SupportTicketController extends Controller
{
    //
    /**
     * Display the support ticket page and handle ticket submissions.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function supporticket(Request $request)
    {
        $cps = [];
        $msgs = ["&msg=activated", "&msg=deactivated", "&msg=deleted", "&msg=added", "&msg=updated"];
        $HTTP_REFERER = str_ireplace($msgs, "", $request->server('HTTP_REFERER'));
        $table = "tbl_admin";
        $timenow = time();
        $ipadd = $request->ip();
        $insertdt = now()->toDateTimeString();
        $limit = 50;
        $userId = session('user_id');
        $ticinfo = Ticketing::where('userId', $userId)
                    ->where('type', 1)
                    ->orderBy('ticketId', 'desc')
                    ->get();


        // Handle form submissions
        if ($request->has('send')) {
            // Process new ticket creation and database insertion
            $insert = [
                "userId" => $_SESSION['USER']['ID'],
                "subject" => $request->input('subject'),
                "message" => $request->input('message'),
                "type" => 1,
                "insertedAt" => $insertdt,
                "isSolved" => 0
            ];
            $inid = Ticketing::create($insert)->ticketId;

            $tinfo = Ticketing::where('ticketId', $inid)->orderBy('ticketId', 'desc')->first();
            dd($tinfo);
            $upload_img = json_encode([]);

            $tinsert = [
                "userId" => $tinfo->userId,
                "ticketId" => $tinfo->ticketId,
                "message" => $request->input('message'),
                "image" => $upload_img,
                "insertedAt" => $insertdt
            ];
            TicketMessage::create($tinsert);

            return redirect()->route('support-ticket', ['mode' => 'list', 'tid' => $request->input('tid'), 'sub' => $request->input('sub')])->with('success', 'New ticket has been added!');
        }

        if ($request->has('reply')) {
            // Process ticket reply and database insertion
            $upload_img = json_encode([]);

            $insert = [
                "userId" => $_SESSION['USER']['ID'],
                "ticketId" => $request->input('tid'),
                "message" => $request->input('message'),
                "image" => $upload_img,
                "insertedAt" => $insertdt
            ];
            TicketMessage::create($insert);

            return redirect()->route('support-ticket', ['mode' => 'list', 'tid' => $request->input('tid'), 'sub' => $request->input('sub')])
                ->with('success', 'Record(s) has been updated!');
        }

        return view('user.support-ticket', compact('cps', 'ticinfo'));
    }
    /**
     * Display thenew-ticket page .
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function newticket()
    {
        return view('user.new-ticket');
    }
    /**
     * Process the form submission to create and send a support ticket.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendSupportTicket(Request $request)
    {

        $msgs = ["&msg=activated", "&msg=deactivated", "&msg=deleted", "&msg=added", "&msg=updated"];
        $HTTP_REFERER = str_ireplace($msgs, "", $request->server('HTTP_REFERER'));
        $insertdt = now();
        if ($request->has('send')) {
            try {

                $ticket = new Ticketing();
                $ticket->userId = session('user_id');
                $ticket->subject = $request->input('subject');
                $ticket->message = $request->input('message');
                $ticket->type = 1;
                $ticket->created_at = $insertdt;
                $ticket->isSolved = 0;

                $ticket->save();
                $tinfo = Ticketing::where('ticketId', $ticket->ticketId)->orderBy('ticketId', 'desc')->first();
                $uploadImg = json_encode([]);
                $ticketMessage = new TicketMessage();
                $ticketMessage->userId = $tinfo->userId;
                $ticketMessage->ticketId = $tinfo->ticketId;
                $ticketMessage->message = $request->input('message');
                $ticketMessage->image = $uploadImg;
                $ticketMessage->created_at = $insertdt;
                $ticketMessage->save();

                return redirect('support-ticket')->with('success', 'New ticket has been added!');
            } catch (\Exception $e) {

                return redirect()->back()->with('error', 'An error occurred while adding a new ticket: ' . $e->getMessage());
            }
        }
        return redirect('support-ticket');
    }

    /**
     * Display the support feedback page with ticket details and messages.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function filesupport()
    {
        $mode = request()->query('mode');
        if ($mode) {
            $tid = request()->query('tid');
            $sub = request()->query('sub');
            $uticinfo = DB::select("SELECT * FROM tbl_ticket_messgae WHERE ticketId = ? ORDER BY ticketMessageId ASC", [$tid]);
            $userinfo = User::where('id', session::get('user_id'))->get();
            $tsinfo = DB::select("SELECT ticketId, isSolved FROM tbl_ticketing WHERE ticketId = ?", [$tid]);

            $repBtn = (!empty($tsinfo) && $tsinfo[0]->isSolved == 1) ? "" : "disabled";

            return view('user.supportfeedback', compact('mode', 'tid', 'sub', 'uticinfo', 'userinfo', 'repBtn'));
        }
        return redirect()->back()->with('error', 'something went wrong');
    }
    /**
     * Reply to a support ticket by adding a new message.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function replyToTicket(Request $request)
    {
   
        $request->validate([
            'message' => 'required|string',
            'tid' => 'required|numeric',
        ]);

        $message = $request->input('message');
        $tid = $request->input('tid');

        $ticket = Ticketing::where('ticketId', $tid)->first();
        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket not found.');
        }
        DB::table('tbl_ticket_messgae')->insert([
            'userId' => session::get('user_id'), 
            'ticketId' => $tid,
            'message' => $message,
            'image' => json_encode([]), 
            'created_at' => now(), 
        ]);
        return redirect()->back()->with('success', 'Reply sent successfully.');
    }
}
