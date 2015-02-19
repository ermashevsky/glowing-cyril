<?php
ini_set('memory_limit','128M');
use Jenssegers\Mongodb\Model as Eloquent;

class Logs extends Eloquent{
    
    protected $collection = 'Logs';
    
    public static function getAll(){
        $logFile = 'laravel.log';

        Log::useDailyFiles(storage_path().'/logs/'.$logFile);
        $array_fields = array(
            'host',
            'setup_time_time',
            'setup_time_date'
        );
        $logs = Logs::all($array_fields);
        return $logs;
        
    }
    
}

