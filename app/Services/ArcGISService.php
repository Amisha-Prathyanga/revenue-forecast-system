<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class ArcGISService
{
    protected $serviceUrl = 'https://services-ap1.arcgis.com/ASXHwH2eR1FRQr12/arcgis/rest/services/service_bcca095a64bd4e8db0032906c066692b/FeatureServer/0/query';

    public function fetchData(array $filters = [])
    {
        $queryParams = array_merge([
            'f' => 'json',
            'where' => '1=1', // Default: fetch all data
            'outFields' => '*',
        ], $filters);

        $response = Http::get($this->serviceUrl, $queryParams);

        if ($response->successful()) {
            $data = $response->json();
            return $data['features'] ?? [];
        }

        return [];
    }
}
