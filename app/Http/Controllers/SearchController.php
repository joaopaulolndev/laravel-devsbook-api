<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SearchController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function search(Request $request)
    {
        $array = ['error' => '', 'users' => []];

        $validator = Validator($request->only(['txt']), ['txt' => ['required', 'min:2']]);

        if ($validator->fails()) {
            $array['error'] = $validator->errors();
            return $this->jsonResponse($array, 400);
        }

        $txt = $request->input('txt');

        $userList = User::select('id', 'name', 'avatar')->where('name', 'like', '%' . $txt . '%')->get();

        foreach ($userList as $userItem) {
            $array['users'][] = [
                'id' => $userItem['id'],
                'name' => $userItem['name'],
                'avatar' => url('media/avatars/' . $userItem['avatar'])
            ];
        }

        return $this->jsonResponse($array);
    }
}
