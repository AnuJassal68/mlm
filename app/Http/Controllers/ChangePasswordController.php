<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Spent;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
 * Display a change password page.
 *
 *
 * @return \Illuminate\Contracts\View\View
 */
    public function showForm()
    {
        return view('admin.changepassword');
    }

    /**
    * Change the admin user's login password.
    *
    * @param  Request $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function changePassword(Request $request)
    {
        $emsg = '';
        $etype = 'danger';

        if ($request->has('changeloginpassword')) {
            if ($request->filled(['npassword', 'rnpassword'])) {
                $tlen = strlen($request->npassword);

                if ($tlen > 5) {
                    // Retrieve the admin user's login password
                    $admin = auth::guard('admin')->user();

                    if ($admin && $admin->loginpassword == $request->cpassword) {
                        if ($request->npassword == $request->rnpassword) {
                            // Update the login password
                            // Hash the new password before saving
                            $admin->loginpassword = bcrypt($request->rnpassword);

                            $admin->save();

                            return redirect()->route('changepassword', ['msg' => 'dn'])
                                ->with('emsg', 'Login Password has been changed successfully!')
                                ->with('etype', 'success');
                        } else {
                            $emsg = "Login Password and re-entered login password do not match.";
                        }
                    } else {
                        $emsg = "Invalid Current Password. Please enter a valid current password to continue!";
                    }
                } else {
                    $emsg = "Your login password must be 6 to 32 characters long!";
                }
            } else {
                $emsg = "All Fields are mandatory!";
            }
        }

        return redirect()->back()->with('emsg', $emsg)->with('etype', $etype);
    }
    /**
 * Display a login page.
 *
 *
 * @return \Illuminate\Contracts\View\View
 */
    public function adminlogin()
    {
        return view('admin.adminlogin');
    }

    /**
     * Process the admin login form data and authenticate the admin user.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
 
    
    public function adminlogindata(Request $request)
    {
        try {
            // Retrieve the admin user by 'loginid' from the request
            $admin = Admin::where('loginid', $request->input('loginid'))->first();
    
            if ($admin && Hash::check($request->input('loginpassword'), $admin->loginpassword)) {
                // Admin is authenticated, log in the admin
                Auth::guard('admin')->login($admin);
                return redirect('admindashboard'); // Redirect to the admin dashboard
            } else {
                // Authentication failed, redirect back with an error message
                return redirect()->back()->with('error', 'Your credentials do not match our records.');
            }
        } catch (\Exception $e) {
            // Handle any unexpected exceptions that may occur during the operation
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    


    /**
     * Log out the admin user.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        try {
            // Log out the admin user from the 'admin' guard
            Auth::guard('admin')->logout();

            // Invalidate the user's session
            $request->session()->invalidate();

            // Redirect to the admin login page
            return redirect('/adminlogin');
        } catch (\Exception $e) {
            // Handle any unexpected exceptions that may occur during the operation
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    /**
 * Display the admin dashboard view.
 *
 * @param  Request $request
 * @return \Illuminate\View\View
 */
    public function admindashboard(Request $request)
    {
        try {
            // Get the total number of users
            $uinfo = User::count();

            // Calculate and format the total deposit amount
            $formattedValue = Deposit::where('bActive', 'Y')->sum('deposit');
            $dinfo = number_format($formattedValue, 2);

            // Calculate and format the total spent amount
            $sinfos = Spent::sum('processamt');
            $sinfo = number_format($sinfos, 2);

            // Retrieve query parameters from the request
            $vw = $request->input('view');
            $q = $request->input('q');
            $a = $request->input('a');
            $s = $request->input('s');
            $df = $request->input('df');
            $dt = $request->input('dt');
            $limit = 5;

            // Initialize an empty array for query results
            $qinfo = [];

            // Create a query for ActivityLog where counter is '2'
            $query = ActivityLog::where('counter', '2');

            // Order the results by id in descending order
            $qinfo = $query->orderBy('id', 'desc')->get();

            // Pass the $qinfo variable and other parameters to the view
            return view('admin.admindashboard', compact('qinfo', 'vw', 'q', 'a', 's', 'df', 'dt', 'dinfo', 'sinfo', 'uinfo'));
        } catch (\Exception $e) {
            // Handle any unexpected exceptions that may occur during the operation
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


}
