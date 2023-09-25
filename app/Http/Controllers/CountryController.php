<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    //
    public function index(Request $request)
    {
        $emsg = ["&msg=activated", "&msg=deactivated", "&msg=deleted", "&msg=added", "&msg=updated"];
        $HTTP_REFERER = str_ireplace($emsg, "", $request->server('HTTP_REFERER'));
        $table = "tbl_country_state_city";
        $timenow = time();
        $etype = 'success';
        $ipadd = $request->ip();
        $countries =  getquery("tbl_country_state_city"," AND parentid = '0' ORDER BY id ASC","*","Y");;
       
        return view('admin.countryindex', compact('countries'));
    }

    public function addEdit($id = null)
    {
        $country = ($id) ? Country::find($id) : new Country();
        return view('admin.add', compact('country'));
    }

    public function save(Request $request, $id = null)
    {
        $request->validate([
            'title' => 'required',
            'code' => 'nullable',
        ]);

        $countryData = [
            'title' => $request->input('title'),
            'code' => $request->input('code'),
            'bActive' => $request->has('bactive') ? 'Y' : 'N',
        ];

        if ($id) {
            $country = Country::find($id);
            $country->update($countryData);
        } else {
            Country::create($countryData);
        }

        return redirect('/countries')->with('message', $id ? 'Record has been updated!' : 'New record has been added!');
    }

 /**
     *
     *
     * Active,inactive and delete all function
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function teams(Request $request)
     {
         $data = $request->all();
 
         if (!array_key_exists("checkbox", $data)) {
             return redirect('/countries');
         } else {
             // Active data
             if ($data['bulk'] == "Inactive") {
                 $change = Country::whereIn('id', $data['checkbox'])->get();
                 foreach ($change as $value) {
                     if ($value['bActive'] == 'Y') {
                         $value['bActive'] = 'N';
                     }
                     $value->update();
                 }
                 return redirect('/countries')->with('success', 'Inactive successfully');
             }
             // Inactive the data
             elseif ($data['bulk'] == "Active") {
                 $change = Country::whereIn('id', $data['checkbox'])->get();
                 foreach ($change as $value) {
                     if ($value['bActive'] == 'N') {
                         $value['bActive'] = 'Y';
                     }
                     $value->update();
                 }
                 return redirect('/countries')->with('success', 'Active successfully');
             }
             // delete the bulk records and single records
             elseif ($data['bulk'] == "Delete") {
                 $change = Country::whereIn('id', $data['checkbox'])->get();
                 foreach ($change as $value) {
                     if ($value['bActive'] == 'Active' || $value['bActive'] == 'Inactive') {
                         $value['bActive'] = null;
                     }
                     $value->delete();
                 }
                 return redirect('/countries')->with('success', 'Deleted successfully');
             } else {
                 $change = Country::whereIn('id', $data['checkbox'])->get();
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
                 return redirect('/countries');
             }
         }
     }
     
}
