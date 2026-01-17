<?php

namespace App\Jobs;

use App\Models\IP;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SearchIP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ips;
    private $informations;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ips)
    {
        $this->ips = $ips;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info($this->ips);

        foreach ($this->ips as $ip) {
            $ip_region = file_get_contents("http://ip-api.com/json/$ip?fields=status,message,country,countryCode,region,regionName,city,district,zip,lat,lon,query");
            $get_results =  json_decode($ip_region);

            if ($get_results->status != 'success') {
                Log::error("Não foi possível identificar o ip $ip (SearchIP)");
                Log::error($get_results->status);
                Log::error($get_results->message);
                continue;
            }

            if(IP::where('ip', $ip)->exists()) {
                continue;
            }

            $this->informations[] = [
                'ip' => $get_results->query,
                'country' => $get_results->country,
                'country_code' => $get_results->countryCode,
                'region' => $get_results->regionName,
                'region_code' => $get_results->region,
                'city' => $get_results->city,
                'district' => $get_results->district,
                'cep' => $get_results->zip,
                'latitude' => $get_results->lat,
                'longitude' => $get_results->lon
            ];
        }

        if($this->informations) {
            IP::insert($this->informations);
        }
    }
}
