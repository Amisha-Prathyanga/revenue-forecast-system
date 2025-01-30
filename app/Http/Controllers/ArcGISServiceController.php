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
        $apiData = $arcGISService->fetchData([]);
    
        // Get approvals from the database, keyed by objectid
        $approvalData = Approval::all()->keyBy('objectid');
    
        // Get current user
        $user = auth()->user();
        
        // Merge approval status with the API data
        $mergedData = collect($apiData)->map(function ($item) use ($approvalData) {
            $objectid = $item['attributes']['objectid'];
            $approval = $approvalData->get($objectid);
            
            $item['ds_approved'] = $approval->ds_approved ?? false;
            $item['district_approved'] = $approval->district_approved ?? false;
            $item['national_approved'] = $approval->national_approved ?? false;
            
            return $item;
        });
    
        // Filter by user role and DSD
        $userRole = $user->usertype;
        $userDSD = $user->dsd_n;
    
        // First filter by DSD if user has one assigned (except for National users)
        if ($userDSD && $userRole !== 'National') {
            $mergedData = $mergedData->filter(function ($item) use ($userDSD) {
                return $item['attributes']['DSD_N'] === $userDSD;
            });
        }
    
        // Then apply role-based filtering
        if ($userRole === 'DS') {
            $visibleData = $mergedData;
        } elseif ($userRole === 'District') {
            $visibleData = $mergedData->filter(function ($item) {
                return $item['ds_approved'] == true;
            });
        } elseif ($userRole === 'National') {
            $visibleData = $mergedData->filter(function ($item) {
                return $item['district_approved'] == true;
            });
        } else {
            $visibleData = collect();
        }
    
        return view('arcGis.indPubDamage', [
            'mergedData' => $visibleData,
            'mapData' => $visibleData->map(function ($item) {
                return [
                    'id' => $item['attributes']['objectid'],
                    'x' => number_format((float)$item['geometry']['x'], 10, '.', ''), // Ensure full precision
                    'y' => number_format((float)$item['geometry']['y'], 10, '.', ''), // Ensure full precisiontem['geometry']['y'],
                    'district' => $item['attributes']['DISTRICT_N'],
                    'dsd' => $item['attributes']['DSD_N'],
                    'description' => $item['attributes']['disas_event'],
                ];
            })->values()->toArray()
        ]);
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




