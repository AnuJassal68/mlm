<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Notification;
class NotificationController extends Controller
{
    /**
 * Display the notification index page.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\View\View
 */
    public function notificationindex(Request $request)
    {
        $emsg = Session::get('emsg');
        $etype = Session::get('etype');
        Session::remove('emsg');
        Session::remove('etype');

        $limit = 50;
        $rinfo = DB::table('tbl_notification')
            ->orderBy('id', 'desc')
            ->get();
            $fullset = 'Y'; 
            $q = $request->query('q');
            $f = $request->query('f');
        return view('admin.notification', compact('rinfo', 'limit', 'emsg', 'etype','fullset','q','f'));
    }
/**
 * Display the notification index page.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\View\View
 */
    public function showAddEditForm($id = null)
    {
        $binfo = null;
        if ($id) {
            $binfo = DB::table('tbl_notification')
                ->where('id', $id)
                ->first();
        }
        // dd( $binfo);
        $fullset = 'Y'; 
        return view('admin.editnotification', compact('binfo','fullset'));
    }
/**
 * Update or create a notification record.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int|null  $id
 * @return \Illuminate\Http\RedirectResponse
 */
    public function update(Request $request, $id = null)
    {
        $request->validate([
          
            'subject' => 'required',
            'content' => 'required',
        ]);

        $ba = $request->has('bactive') ? 'Y' : 'N';

        if ($id) {
            $notification = Notification::findOrFail($id);
            $notification->title = $request->title;
            $notification->subject = $request->subject;
            $notification->notification = $request->content;
            $notification->bActive = $ba;
            $notification->save();
        } else {
            Notification::create([
                'title' => $request->title,
                'subject' => $request->subject,
                'notification' => $request->content,
                'bActive' => $ba,
            ]);
        }

        return redirect()->route('notifications.index')->with('message', $id ? 'Record has been updated!' : 'New record has been added!');
    }
/**
 * Activate selected notifications.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
    public function activateSelected(Request $request)
    {
        $selectedIds = $request->input('del');

        if (!empty($selectedIds)) {
            DB::table('tbl_notification')
                ->whereIn('id', $selectedIds)
                ->update(['bActive' => 'Y']);

            Session::flash('emsg', 'Record(s) has been activated!');
            Session::flash('etype', 'success');
        } else {
            Session::flash('emsg', 'Please select at least one record to activate!');
            Session::flash('etype', 'warning');
        }

        return redirect()->route('notifications.index');
    }
/**
 * deactivate selected notifications.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
    public function deactivateSelected(Request $request)
    {
        $selectedIds = $request->input('del');

        if (!empty($selectedIds)) {
            DB::table('tbl_notification')
                ->whereIn('id', $selectedIds)
                ->update(['bActive' => 'N']);

            Session::flash('emsg', 'Record(s) has been deactivated!');
            Session::flash('etype', 'warning');
        } else {
            Session::flash('emsg', 'Please select at least one record to deactivate!');
            Session::flash('etype', 'warning');
        }

        return redirect()->route('notifications.index');
    }
/**
 * delete selected notifications.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
    public function deleteSelected(Request $request)
    {
        $selectedIds = $request->input('del');

        if (!empty($selectedIds)) {
            DB::table('tbl_notification')
                ->whereIn('id', $selectedIds)
                ->delete();

            Session::flash('emsg', 'Record(s) has been deleted!');
            Session::flash('etype', 'danger');
        } else {
            Session::flash('emsg', 'Please select at least one record to delete!');
            Session::flash('etype', 'warning');
        }

        return redirect()->route('notifications.index');
    }
}
