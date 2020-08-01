<?php

namespace App\Http\Controllers;

use App\FileContent;
use Illuminate\Support\Facades\Cache;
use ZipStream\File;

class reportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function generateReport(){
        // I know we must use repository but no mush time
        $report = array();
        $report['count'] = Cache::get('count', function () {
            return FileContent::query()->count();
        });
        $report['phone_type'] = Cache::get('phone_type', function () {
            return  FileContent::groupBy('phone_type')
                ->selectRaw('count(*) as total, phone_type')
                ->get();
        });
        $report['salary_gt_1000'] = Cache::get('salary_gt_1000', function () {
            return  FileContent::where('salary','>','1000')
                ->count();
        });


        return response()->json($report);
    }
}
