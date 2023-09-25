<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL; // Import the URL facade

class HomeController extends Controller
{
    //
    public function index()
    {
        return view('front.index');
    }

    public function howitworks()
    {
        return view('front.howitworks');
    }
    public function future()
    {
        return view('front.future');
    }
    public function concept()
    {
        return view('front.concept');
    }
    public function about()
    {
        return view('front.about');
    }
    public function faq()
    {
        return view('front.faq');
    }

  

    public function showHeader()
    {
        // Generate the referral link
        $referralLink = URL::to('/').'?ref='.$uinfo[0]->loginid;
    
        // Pass the referral link variable to the header view
        return view('includes.header', ['referralLink' => $referralLink]);
    }
    

}
