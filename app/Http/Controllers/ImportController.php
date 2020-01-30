<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use DateTime;
use Illuminate\Support\Facades\DB;

use App\Ett as EttModel;
use App\Rtt as RttModel;
use App\Ettrtt as EttrttModel;

class ImportController extends Controller
{
    
    const ZONEFROM   = 'zone_from';
    const ZONETO     = 'zone_to';
    const ETT        = 'e_travel_time';
    const TRAVELTIME = 'travel_time';
    
    const DISPATCHED = 'despatched_at';
    const DELIVERED  = 'delivered_at';
    
    const ETTFILE    = 'ettFile.csv';
    const RTTFILE    = 'rttFile.csv';
    
    
    /*
    * Estimated time taken collection provided by carrier 
    */
    private $ettCollection;
    
    /*
    * Real time taken collection  
    */
    private $rttCollection;

    /*
    * Simply return import screen for user to upload the files
    */
   public function importFiles()
   {
       return view('import.results');
   }
    
    /*
    * Upload CSV files to /public/uploads
    * Also import them to database
    */
   public function uploadFiles(Request $request)
   {
       //truncate tables to import new csv files;
       EttModel::truncate();
       RttModel::truncate();
       EttrttModel::truncate();
       
       // get files
       $ettFile = $request->file('ett');
       $rttFile = $request->file('rtt');
       
       // Save CSV files in this path
       $savePath = public_path('/uploads/');
       
       $ettFile->move($savePath, ImportController::ETTFILE);
       $rttFile->move($savePath, ImportController::RTTFILE);
       
       // import to DB, etts table      
       $this->ettCollection = (new FastExcel)->import($savePath.ImportController::ETTFILE, function ($line) {
            return EttModel::create([
                'zone_from' => $line['zone_from'],
                'zone_to' => $line['zone_to'],
                'travel_time' => $line['travel_time']
            ]);
       });
       
       // import to DB, rtts table 
       $this->rttCollection = (new FastExcel)->import($savePath.ImportController::RTTFILE, function ($line) {
            return RttModel::create([
                'zone_from' => $line['zone_from'],
                'zone_to' => $line['zone_to'],
                'dispached_at' => $line['despatched_at'],
                'delivered_at' => $line['delivered_at']
            ]);
       });
       
          
       return view('import.results',
                   ['successMsg' => 'CSV files uploaded in the server and also imported to database.']
                   );
   }
    
    /*
    * Parse CSV files and return results as json 
    * for ajax call
    */
    public function processFiles()
    {
        
        $tableResultes =[];
        
        $ettCollection = EttModel::all()->sortBy('zone_from')->sortBy('zone_to');
      
       //loop through ETTS collection
       foreach($ettCollection as $key => $ett){
         
           $ettFrom =trim(str_replace('"', "", $ett[ImportController::ZONEFROM]));
           
           $ettTo   =trim(str_replace('"', "", $ett[ImportController::ZONETO]));
           
           $ettDays =trim(str_replace('"', "", $ett[ImportController::TRAVELTIME]));

           $tempArr = array(
                ImportController::ZONEFROM   => $ettFrom, 
                ImportController::ZONETO     => $ettTo, 
                ImportController::ETT        => $ettDays,
                ImportController::TRAVELTIME => $this->calculateMean($ettFrom, $ettTo, $ettDays)
           );
           
           $tableResultes[] = $tempArr ;
       }
        
       EttrttModel::truncate();
        
       // import to DB, ettrtts table   
        foreach($tableResultes as $res){
            EttrttModel::create([
                'zone_from' => $res['zone_from'],
                'zone_to'   => $res['zone_to'],
                'ett'       => $res['e_travel_time'],
                'mean_rtt'  => $res['travel_time']
            ]);
        }        
        
        $data = DB::table('ettrtts')->paginate(30);
        
        return view('import.meanrtt',['data' => $data] );
    
    }

    /*
    * Calculate all peer mean RTTS values to ETTS table
    */
    private function calculateMean($ettFrom, $ettTo, $ettDays)
    {
        // All delivered_at - dispatched_at is saved in this array
        $durations = [];
        
        // Fetch only the rows from RTTS table that have same 
        // zone_from and zone_to with ETTS table
        $Rttcollection = RttModel::where('zone_from', '=', $ettFrom)
            ->where('zone_to', '=', $ettTo)->get();
        
        // Loop through the return collection and calculatio the mean value
        foreach($Rttcollection as $col){
            
            $durations[] = $this->calDateTimeDifference( trim(str_replace('"', "", $col->dispached_at)), trim(str_replace('"', "", $col->delivered_at))  );
        }
        if(count($durations) === 0){
            return 'nil';
        }
        return round(array_sum($durations) / count($durations), 1);
    }
    
    /*
    * This function calculates the date difference of
    * dispatched and delevered
    */
    private function calDateTimeDifference($dispatchedAt, $deliveredAt){
        $dispatched = new DateTime(date('Y-m-d', strtotime($dispatchedAt)));
        $delivered  = new DateTime(date('Y-m-d', strtotime($deliveredAt)));
        return $delivered->diff($dispatched)->days; 
    }
}
