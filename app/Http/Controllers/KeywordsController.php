<?php

namespace App\Http\Controllers;

use App\Interfaces\Domain\IKeywordsRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class KeywordsController extends Controller
{
    protected $_KeywordsRepository;

    public function __construct(IKeywordsRepository $key)
    {
        $this->_KeywordsRepository = $key;
    }

    public function get_all_golden()
    {
        return $this->_KeywordsRepository->get_all_golden();
    }

    public function get_all_fixed()
    {
        return $this->_KeywordsRepository->get_all_fixed();
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'points' => 'required|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $key = $this->_KeywordsRepository->add_keyword($request->name, $request->points, "golden");
        if ($key == false) {
            return response()->json("the keyword is already reserved", 400);
        }
        return response()->json($key);
    }

    public function get_keyword_data(Request $request)
    {
        return $this->_KeywordsRepository->get_keyword_data($request->name);
    }


    public function delete(Request $request)
    {

        return $this->_KeywordsRepository->delete_keyword($request->name);
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'points' => 'required|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        return $this->_KeywordsRepository->update_points($request->name, $request->points);
    }
}
