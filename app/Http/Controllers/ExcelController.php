<?php

namespace App\Http\Controllers;

use App\Exports\ExcelExport;
use App\FileContent;
use App\Imports\ExcelImport;
use App\Imports\UsersImport;
use App\Upload;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{

    use Importable;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

    }

    public function upload(Request $request){
        //validate incoming request
        $this->validate($request, [
            'file' => 'required|file|mimes:xlsx,xls',
        ]);
        $fileName = $request->file('file')->getClientOriginalName();
        $fileName = uniqid() . '_' . $fileName;
        $path = 'uploads' . DIRECTORY_SEPARATOR . 'user_files' . DIRECTORY_SEPARATOR . 'mobtaker' . DIRECTORY_SEPARATOR;

        $destinationPath =(env('PUBLIC_PATH', base_path('public')) . ($path ? '/' . $path : $path));
        File::makeDirectory($destinationPath, 0777, true, true);
        $request->file('file')->move($destinationPath, $fileName);
        try {
            $upload = new Upload();
            $upload->file = $fileName;
            $upload->path = $destinationPath. DIRECTORY_SEPARATOR . $fileName;
            $upload->save();
            $rows = $this->import($upload->path);
            foreach ($rows as $row){
                $feedback = array();
                $feedback= $this->rowValidate($row);
                $row[6] = $feedback;
                $file_content = $this->mapValues($row);
            }
            //return successful response
            return response()->json(['file' => $upload, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'File Upload Failed!'], 409);
        }

    }


    public function import($path)
    {
        return Excel::toArray(new ExcelImport, $path)[0];
//       return Excel::import(new ExcelImport, $path);
    }

    public function rowValidate(array  $row)
    {
        $response = array();
        $response[] = is_null($row[0]) ? 'name field is empty' : null;
        $response[] = is_null($row[1]) ? 'email field is empty' : null;
        $response[] = is_null($row[2]) ? 'phone field is empty' : null;
        $response[] = is_null($row[3]) ? 'age field is empty' : null;
        $response[] = is_null($row[4]) ? 'salary field is empty' : null;
        $response[] = is_null($row[5]) ? 'phone_type field is empty' : null;
        return array_values(array_filter($response));
    }

    public function mapValues($row){
        $file_content = new FileContent();

        $file_content->name= $row[0];
        $file_content->email= $row[1];
        $file_content->phone= $row[2];
        $file_content->age= $row[3];
        $file_content->salary= $row[4];
        $file_content->phone_type= $row[5];
        $file_content->feedback= implode(',',$row[6]);
        try {
            $file_content->save();
        } catch (\Exception $e){
            dd($e->getMessage());
        }

         return 0;

    }


    public function export()
    {
        return Excel::download(new ExcelExport(), 'info.xlsx');
    }


}
