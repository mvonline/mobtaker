<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Mail\reportEmail;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send()
    {
        $dbReport = app('App\Http\Controllers\ReportController')->generateReport();
        $rep = (json_decode(json_encode($dbReport))->original);
        $report = new \stdClass();
        $report->count= $rep->count;

        Mail::to("masoud.vafaei@gmail.com")->send(new reportEmail($report));
    }
}
