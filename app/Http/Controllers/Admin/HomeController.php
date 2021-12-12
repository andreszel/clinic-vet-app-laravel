<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        return view('admin.home');
    }

    public function testJquery()
    {
        return view('admin.testjquery');
    }

    public function createPDF()
    {
        $html = '<h1>Test</h1><p>Enim et nulla laboris voluptate qui Lorem anim ipsum. Mollit deserunt aute mollit velit nisi excepteur eiusmod consectetur. Excepteur nostrud reprehenderit amet occaecat eu deserunt deserunt ea velit fugiat velit.</p>';
        // load a HTML string, file or view name
        //$pdf = App::make('dompdf.wrapper');
        //$pdf->loadHTML($html);
        //return $pdf->stream();

        // use the facade
        $data = array();
        $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif', 'debugCss' => true, 'debugLayout' => true])->loadView('pdf.test', $data);

        return $pdf->download('test.pdf');

        // chain methods
        //return PDF::loadFile(public_path().'/myfile.html')->save('/path-to/my_stored_file.pdf')->stream('download.pdf');

        // You can change the orientation and paper size, and hide or show errors (by default, errors are shown when debug is on)
        //return PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->save('test2.pdf');
    }

    public function testAjax()
    {
        try {
            //throw new Exception();
            return response()->json([
                'status' => 'success',
                'message' => 'Rekord został usunięty!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wystąpił błąd!'
            ])->setStatusCode(500);
        }
    }
}
