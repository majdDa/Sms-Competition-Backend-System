<?php

namespace App\Http\Controllers;

use App\Interfaces\Domain\IQuestionsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
{

    protected $q;

    public function __construct(IQuestionsRepository $q)
    {
        $this->q = $q;
    }


    public function get_all_questions()
    {
        Log::channel('question')->info(date("Y-m-d h:i:sa"), ["get all question"]);
        return $this->q->get_all_questions();
    }

    public function add_question(Request $request)
    {
        Log::channel('question')->info(date("Y-m-d h:i:sa"), $request->all());
        return $this->q->add_question($request);
    }

    public function add_file_questions(Request $request)
    {


        $validator = Validator::make(
            [
                'xlsx_file'      => $request->xlsx_file,
                'extension' => strtolower($request->xlsx_file->getClientOriginalExtension()),
            ],
            [
                'xlsx_file'      => 'required',
                'extension'      => 'required | ends_with:xls,xlsx',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $file = $request->file('xlsx_file');
        $data = Excel::toArray([], $file);

        // Access the data from the first sheet
        $sheetData = $data[0];
        $title = $sheetData[0];
        if (!(strtolower(str_replace(' ', '', $title[0])) === "questionstext"
            && strtolower(str_replace(' ', '', $title[1])) === "answer"
            && strtolower(str_replace(' ', '', $title[2])) === "order"
            && strtolower(str_replace(' ', '', $title[3])) === "questionsdate"
        )) {
            return response()->json([
                'error' => 'file Excel not correct'
            ], 400);
        }

        $file->store('file_Excel_upload_for_questions', 'public'); // storage\app\public\file_Excel_upload_for_questions
        //return isset($sheetData[2][3]);
        Log::channel('question')->info(date("Y-m-d h:i:sa"), ["add_file_questions"]);
        return $this->q->add_file_questions($sheetData);
    }


    public function get_questions_by_date(Request $request)
    {
        $date = $request->date;

        Log::channel('question')->info(date("Y-m-d h:i:sa"), $request->all());
        return $this->q->get_questions_by_date($date);
    }


    public function update_question(Request $request)
    {

        Log::channel('question')->info(date("Y-m-d h:i:sa"), $request->all());
        $ques = $this->q->update_question($request);
        if ($ques) {

            return $ques;
        }

        return response()->json('Invalid Update');
    }


    public function delete_question(Request $request)
    {
        $id = $request->id;
        Log::channel('question')->info(date("Y-m-d h:i:sa"), $request->all());
        return $this->q->delete_question($id);
    }
}
