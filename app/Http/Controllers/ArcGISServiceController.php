<?php

namespace App\Http\Controllers;
use App\Services\ArcGISService;
use Illuminate\Support\Collection;
use App\Models\Approval;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArcGISServiceController extends Controller
{
    public function fetchData(ArcGISService $arcGISService) {
        // Fetch data from API
        $apiData = $arcGISService->fetchData([]); // Fetch all data
    
        // Get approvals from the database, keyed by objectid
        $approvalData = Approval::all()->keyBy('objectid');
    
        // Merge approval status with the API data
        $mergedData = collect($apiData)->map(function ($item) use ($approvalData) {
            $objectid = $item['attributes']['objectid']; // Accessing objectid from attributes
    
            // Retrieve approval status from the database using objectid
            $approval = $approvalData->get($objectid);
    
            // If no approval data exists, initialize default approval status
            $item['ds_approved'] = $approval->ds_approved ?? false;
            $item['district_approved'] = $approval->district_approved ?? false;
            $item['national_approved'] = $approval->national_approved ?? false;
    
            return $item;
        });
    
        // Log merged data to see it
        \Log::info('Merged Data:', $mergedData->toArray());
    
        // Filter the data based on the approval flow
        $userRole = auth()->user()->usertype;
    
        if ($userRole === 'DS') {
            // DS can see everything, so no filtering needed
            $visibleData = $mergedData;
        } elseif ($userRole === 'District') {
            // District can only see data approved by DS
            $visibleData = $mergedData->filter(function ($item) {
                return $item['ds_approved'] == true;
            });
        } elseif ($userRole === 'National') {
            // National can only see data approved by District
            $visibleData = $mergedData->filter(function ($item) {
                return $item['district_approved'] == true;
            });
        } else {
            $visibleData = collect(); // If no valid user role, no data should be shown
        }
    
        // Log filtered data
        \Log::info('Filtered Data:', $visibleData->toArray());
        \Log::info('User Role:', [auth()->user()->usertype]);
    
        // Pass the filtered data to the view
        return view('arcGis.indPubDamage', ['mergedData' => $visibleData]);
    }

    

    


    public function updateApproval(Request $request, $objectid)
{
    $approval = Approval::firstOrNew(['objectid' => $objectid]);
    $level = auth()->user()->usertype; // DS, District, National

    if ($level === 'DS') {
        $approval->ds_approved = true;
    } elseif ($level === 'District' && $approval->ds_approved) {
        $approval->district_approved = true;
    } elseif ($level === 'National' && $approval->district_approved) {
        $approval->national_approved = true;
    }

    $approval->save();

    return redirect()->back()->with('status', 'Approval updated!');
}

}




