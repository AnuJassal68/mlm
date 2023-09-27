<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class StateController extends Controller
{
    //
    /**
     * Display the state management view.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function stateindex(Request $request, $id)
    {
        $pid = $request->id;

        // Retrieve information about the parent entity with ID $pid
        $pinfo = getquery("tbl_country_state_city", " AND id = '" . $pid . "'");

        $cps = [];

        // If there is no information about the parent entity, redirect to the 'countries.index' route
        if (!$pinfo) {
            return redirect()->route('countries.index');
        }

        // Define an array of messages to remove from the HTTP_REFERER
        $emsg = ["&msg=activated", "&msg=deactivated", "&msg=deleted", "&msg=added", "&msg=updated"];

        // Extract the HTTP_REFERER and remove messages
        $HTTP_REFERER = str_ireplace($emsg, "", $request->server('HTTP_REFERER'));

        $table = "tbl_country_state_city";
        $timenow = time();
        $etype = 'success';
        $ipadd = $request->ip();

        // Retrieve state records with parent ID $pid and order them by ID in ascending order
        $rinfo = getquery("tbl_country_state_city", "AND parentid = '".$pid."' ORDER BY id ASC", "*", "Y");

        $fullset = 'Y';

        // Render the 'admin.states' view with the specified variables
        return view('admin.states', compact('pinfo', 'rinfo', 'emsg', 'etype', 'pid', 'fullset', 'cps'));
    }


    /**
     * Add a new state record to the 'tbl_country_state_city' table.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function stateadd(Request $request, $id)
    {
        // Retrieve input data from the request
        $title = $request->input('title');
        $ba = $request->has('bactive') ? 'Y' : 'N';
        $pid = $request->id;

        // Create an array with data for insertion
        $insert = [
            'etype' => 'State',
            'parentid' => $pid,
            'title' => $title,
            'bActive' => $ba,
        ];

        // Insert the data into the 'tbl_country_state_city' table
        DB::table('tbl_country_state_city')->insert($insert);

        // Retrieve the ID of the inserted record
        $inid = DB::table('tbl_country_state_city')->where('id', $pid)->get();

        // Add an activity log if needed
        $log = [
            'title' => 'Your title here',
            'href' => '?pg=states&pid=' . $pid . '&mode=addedit&id=' . $inid,
            'action' => 'ADD',
            'section' => 'Manage States',
        ];
        InsertQry('tbl_avtivity_log', $log);

        // Redirect to the 'states' route with a success message
        return redirect()->route('states', ['id' => $pid])->with('success', 'Record has been added');
    }

    /**
 * Display a list of cities based on a specified parent ID.
 *
 * @param  Request $request
 * @param  int $id
 * @return \Illuminate\Contracts\View\View
 */
    public function cities(Request $request, $id)
    {
        $pid = $request->id;
        $rinfo = Country::where('parentid', $pid)
            ->orderBy('id', 'asc')
            ->get();
        return view('admin.cities', compact('rinfo', 'pid'));
    }
    /**
     * Handle status updates and deletions for Country records.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function statestatus(Request $request)
    {
        $data = $request->all();

        if (!array_key_exists("checkbox", $data)) {
            return redirect()->back();
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
                return redirect()->back()->with('success', 'Inactive successfully');
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
                return redirect()->back()->with('success', 'Active successfully');
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
                return redirect()->back()->with('success', 'Deleted successfully');
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
                return redirect()->back();
            }
        }
    }


    /**
     * Add a new city to a country.
     *
     * This function validates incoming request data, creates a new City instance, fills it with data,
     * and saves the new city to the database.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addcities(Request $request, $id)
    {
        try {
  
            $pid = $request->id;

            $city = new Country();
            $city->etype = 'City';
            $city->parentid = $pid;
            $city->title = $request->input('title');
            $city->code = $request->input('code');
            $city->pincodes = $request->input('pincodes');
            $city->bActive = $request->has('bactive') ? 'Y' : 'N'; 
            $city->save();

            return redirect()->route('cities', ['id' => $pid])->with('success', 'City record has been added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    /**
     * Update the status of cities in bulk.
     *
     * This function allows for bulk actions to change the status of cities (Active, Inactive, Delete).
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function citiesstatus(Request $request)
    {
        try {
            $data = $request->all();

            if (!array_key_exists("checkbox", $data)) {
                return redirect()->back()->with('error', 'No items selected.');
            } else {
                // Active data
                if ($data['bulk'] == "Inactive") {
                    $change = Country::whereIn('id', $data['checkbox'])->get();
                    foreach ($change as $value) {
                        if ($value['bActive'] == 'Y') {
                            $value['bActive'] = 'N';
                            $value->update();
                        }
                    }
                    return redirect()->back()->with('success', 'Selected cities set to Inactive successfully.');
                }
                // Inactive the data
                elseif ($data['bulk'] == "Active") {
                    $change = Country::whereIn('id', $data['checkbox'])->get();
                    foreach ($change as $value) {
                        if ($value['bActive'] == 'N') {
                            $value['bActive'] = 'Y';
                            $value->update();
                        }
                    }
                    return redirect()->back()->with('success', 'Selected cities set to Active successfully.');
                }
                // delete the bulk records and single records
                elseif ($data['bulk'] == "Delete") {
                    $change = Country::whereIn('id', $data['checkbox'])->get();
                    foreach ($change as $value) {
                        if ($value['bActive'] == 'Active' || $value['bActive'] == 'Inactive') {
                            $value['bActive'] = null;
                            $value->delete();
                        }
                    }
                    return redirect()->back()->with('success', 'Selected cities deleted successfully.');
                } else {
                    $change = Country::whereIn('id', $data['checkbox'])->get();
                    foreach ($change as $value) {
                        if ($value['bActive'] == 1) {
                            $value['bActive'] = null;
                            $value->update();
                        }
                    }
                }

                if ($request->ajax()) {
                    return response()->json(['success' => true, 'message' => 'Operation performed successfully!']);
                } else {
                    return redirect()->back()->with('success', 'Operation performed successfully.');
                }
            }
        } catch (\Exception $e) {
            // Handle any unexpected exceptions that may occur during the operation
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Update a city's information.
     *
     * This function handles the updating of city information based on the provided data.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function citiesupdate(Request $request, $id)
    {
        try {
        
           $city = Country::find($id);  
            if (!$city) {
                return redirect()->back()->with('error', 'City not found.');
            }
    
            $countryData = [
                'title' => $request->input('title'),
                'code' => $request->input('code'),
                'pincodes' => $request->input('pincodes'),
                'bActive' => $request->has('bactive') ? 'Y' : 'N',
            ];
            if (!empty($countryData)) {               
                $updatedRows = Country::
                    where('id', $id) 
                    ->update($countryData);            
              if ($updatedRows > 0) {
                    return redirect()->back()->with('success', 'City updated successfully.');
                } else {
                   
                    return redirect()->back()->with('error', 'City update failed. No changes were made.');
                }
            } else {
               
                return redirect()->back()->with('error', 'Invalid data. City update failed.');
            }
        } catch (\Exception $e) {
           
            return redirect()->back()->with('error', 'An error occurred while updating the city: ' . $e->getMessage());
        }
    }
 
   /**
 * Update a state record.
 *
 * @param  Request $request
 * @param  int $id
 * @return \Illuminate\Http\RedirectResponse
 */
public function stateupdate(Request $request, $id)
{
    try {
     
        $validatedData = $request->validate([
            'title' => 'required|string',
        ]);

       $state = Country::find($id);
        if (!$state) {
          
            return redirect()->back()->with('error', 'State not found.');
        }     
        DB::table('tbl_country_state_city')
        ->where('id',$state->id)
        ->update([
            'title' => $validatedData['title'],
            'bActive' => $request->has('bactive') ? 'Y' : 'N',
        ]);

        return redirect()->back()->with('success', 'State updated successfully.');
    } catch (\Exception $e) {

        return redirect()->back()->with('error', 'An error occurred while updating the state: ' . $e->getMessage());
    }
}

}
