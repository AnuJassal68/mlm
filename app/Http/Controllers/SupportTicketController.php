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

        // Other code for handling messages and ticket details
        // ...

        // Load the view with the data
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
        // Define an array of messages to remove from the HTTP_REFERER
        $msgs = ["&msg=activated", "&msg=deactivated", "&msg=deleted", "&msg=added", "&msg=updated"];
        $HTTP_REFERER = str_ireplace($msgs, "", $request->server('HTTP_REFERER'));

        // Get the current timestamp using Laravel's Carbon library
        $insertdt = now();

        // Check if the 'send' button is clicked
        if ($request->has('send')) {
            try {
                // Create a new Ticketing instance
                $ticket = new Ticketing();

                // Populate the ticket properties
                $ticket->userId = session('user_id');
                $ticket->subject = $request->input('subject');
                $ticket->message = $request->input('message');
                $ticket->type = 1;
                $ticket->created_at = $insertdt;
                $ticket->isSolved = 0;

                // Save the new ticket to the database
                $ticket->save();

                // Retrieve the newly created ticket
                $tinfo = Ticketing::where('ticketId', $ticket->ticketId)->orderBy('ticketId', 'desc')->first();

                // Prepare image upload information (initially empty)
                $uploadImg = json_encode([]);

                // Create a new TicketMessage instance
                $ticketMessage = new TicketMessage();

                // Populate the ticket message properties
                $ticketMessage->userId = $tinfo->userId;
                $ticketMessage->ticketId = $tinfo->ticketId;
                $ticketMessage->message = $request->input('message');
                $ticketMessage->image = $uploadImg;
                $ticketMessage->created_at = $insertdt;

                // Save the ticket message to the database
                $ticketMessage->save();

                // Redirect with a success message
                return redirect('support-ticket')->with('success', 'New ticket has been added!');
            } catch (\Exception $e) {
                // Handle any unexpected exceptions that may occur during ticket creation
                return redirect()->back()->with('error', 'An error occurred while adding a new ticket: ' . $e->getMessage());
            }
        }

        // Handle other parts of the code if necessary

        // Redirect to the support ticket page (replace 'support-ticket' with the actual route or view name)
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
        // Validate the request data
        $request->validate([
            'message' => 'required|string',
            'tid' => 'required|numeric',
        ]);

        $message = $request->input('message');
        $tid = $request->input('tid');

        // Check if the ticket exists
        $ticket = DB::table('tbl_ticketing')->where('ticketId', $tid)->first();

        if (!$ticket) {
            // Ticket not found, handle the error (e.g., redirect back with a message)
            return redirect()->back()->with('error', 'Ticket not found.');
        }

        // Insert the message into the database
        DB::table('tbl_ticket_messgae')->insert([
            'userId' => session::get('user_id'), // Assuming you're using authentication
            'ticketId' => $tid,
            'message' => $message,
            'image' => json_encode([]), // Empty JSON array
            'created_at' => now(), // Current timestamp
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Reply sent successfully.');
    }
}
