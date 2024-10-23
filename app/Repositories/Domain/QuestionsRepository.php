<?php

namespace App\Repositories\Domain;

use App\Interfaces\Domain\IQuestionsRepository;
use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Ds\Set;

class QuestionsRepository implements IQuestionsRepository
{

    public function get_all_questions()
    {
        $questions = Question::all(); //paginate(10);
        return collect($questions);
    }




    public function add_question($request)
    {

        $date = date("Y-m-d");
        if ($request['question_date'] <= $date) {
            return "The date must be greater than or equal to : " . date('Y-m-d', strtotime('tomorrow'));
        }

        Question::create([
            'question_text' => $request['question_text'],
            'order' => $request['order'],
            'answer' => $request['answer'],
            'points' => ($request['order'] == 1) ? 400 : 500,
            'question_date' => $request['question_date'],
            'created_by' => $request['created_by']
        ]);

        $data = $this->get_questions_by_date($request['question_date']);
        return ($data);
    }

    public function add_file_questions($questions)
    {

        array_shift($questions); // to remove on row in excel file

        $d = $this->checkFileDate($questions);

        if (is_numeric($d)) {
            $d++;
            $msg = "question date does not exist in line " . $d;
            return response()->json([
                "error" => $msg,
            ], 400);
        }

        if ($d != "0000-00-00") {
            return response()->json([
                "error" => $d,
            ], 400);
        }


        $points = 400;
        foreach ($questions as $question) {

            $miliseconds = ($question[3] - (25567 + 2)) * 86400 * 1000;
            $seconds = $miliseconds / 1000;
            $question_date = date("Y-m-d", $seconds);

            $request = new Request();
            $request->merge([
                'question_text' => $question[0],
                'answer' => $question[1],
                'order' => $question[2],
                'question_date' => $question_date,
                'points' => $points,
                'created_by' => "content team",
            ]);

            $this->add_question($request);
            $points = ($points == 500) ? 400 : 500;
        }

        $data = $this->get_questions_by_date(date("Y-m-d"));
        return ($data);
    }




    public function get_questions_by_date($date)
    {
        $questions = Question::where('question_date', $date)->get();
        return response()->json($questions);
    }



    public function update_question($request)
    {

        $date = date("Y-m-d");
        $question = Question::where('id', $request->id)->first();


        if (!$question) {
            return response()->json('the question not found');
        } else if ($question->question_date <= $date) {
            return response()->json('can`t update this question, must be greater than today.');
        } else if ($request['question_date'] <= $date) {
            return response()->json('You cannot choose an older date.');
        } else {

            $question->question_text = $request['question_text'];
            $question->order = $request['order'];
            $question->answer = $request['answer'];
            $question->points = $request['points'];
            $question->question_date = $request['question_date'];
            $question->updated_by = $request['updated_by'];
            $question->save();
        }

        return "aaa";
        //return $this->get_questions_by_date($question->question_date);
    }


    public function delete_question($id)
    {

        $date = date("Y-m-d");
        $q = Question::where('id', $id)->first();
        if (!$q) {
            return response()->json('the question not found');
        } else if ($q->question_date <= $date) {
            return response()->json('can`t delete this question, must be greater than today.');
        } else {
            $q->delete();
        }

        //DB::statement("ALTER TABLE questions AUTO_INCREMENT = 1;");
        return $this->get_questions_by_date($date);
    }


    public function isFinal($id)
    {

        $question = Question::where('id', $id)->first();
        if ($question->order = 4) {
            return true;
        }
        return false;
    }


    public function check_answer($id, $answer)
    {

        $question = Question::where('id', $id)->first();

        if ($question->answer === $answer) {

            return true;
        }

        return false;
    }



    public function get_next_question($order)
    {
        $order = $order + 1;
        $next_question = Question::where([['order', '=', $order], ['is_active', '=', 1]])->first();
        return $next_question;
    }



    public function get_current_question($order)
    {
        $current_question = Question::where([['order', '=', $order], ['is_active', '=', 1]])->first();
        return $current_question;
    }



    public function checkFileDate($questions)
    {


        $dateSet = (Question::select('question_date')->get());
        $set = [];
        $setfile = [];
        $flag = 1;
        foreach ($dateSet as $d) {
            if ($flag == 1) {
                array_push($set, $d['question_date']);
                $flag = 2;
            } else {
                $flag = 1;
            }
        }


        $question_date1 = "";
        $flage = 1;
        $num = 1;
        $order1 = 0;
        $order2 = 0;

        foreach ($questions as $question) {


            if (($question[3]) === null || ($question[3]) == ''
                || !is_numeric($question[2]) || ($question[2]) === null || ($question[2]) == ''
                || !is_numeric($question[1]) || ($question[1]) === null || ($question[1]) == ''
                || ($question[0]) === null || ($question[0]) == ''
            ) {
                //echo "aa";
                return "the faild is empty in line " . ($num + 1);
            }

            if (is_numeric($question[3])) {
                $miliseconds = ($question[3] - (25567 + 2)) * 86400 * 1000;
                $seconds = $miliseconds / 1000;
                $question_date = date("Y-m-d", $seconds);
            } else {
                $question_date = strtotime($question[3]);
            }


            if (!$question_date) {
                return "The date format is wrong in line: " . ($num + 1);
            }
            if ($question[1] != 1 && $question[1] != 2) {
                return "The answer must be 1 or 2 in line: " . ($num + 1);
            }
            if ($question[2] != 1 && $question[2] != 2) {
                return "The order must be 1 or 2 in line: " . ($num + 1);
            }



            if ($flage == 1) {

                $order1 = $question[2];

                if ($question_date <= date("Y-m-d")) {
                    return "The date must be greater than or equal to : " . date('Y-m-d', strtotime('tomorrow'));
                } elseif (in_array($question_date, $set)) {
                    return "The date " . $question_date . " is already exists can`t add it.";
                } elseif (in_array($question_date, $setfile)) {
                    return "The date " . $question_date . " is duplicated in the file";
                } else {
                    array_push($setfile, $question_date);
                }
                $question_date1 = $question_date;
                $flage = 2;
            } else {
                $order2 = $question[2];

                if ($question_date != $question_date1) {
                    return "in date: " . $question_date1 . "  you have just one question, must be two";
                }
                if (($order2 == 1 && $order1 == 1) || ($order2 == 2 && $order1 == 2)) {
                    return "the order in line " . $num . " and " . ($num - 1) . " It doesn't have to be the same. ";
                }
                //echo '$order2 '.$order2.' $order1'. $order1;
                $flage = 1;
            }


            $num++;
        }
        return "0000-00-00";
    }
}
