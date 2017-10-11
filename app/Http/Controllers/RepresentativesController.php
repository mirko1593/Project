<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{User, Representative, Role};

class RepresentativesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('view-representatives');

        return Representative::all();
    }

    public function store(Request $request)
    {
        $request->validate(array_merge(
            User::validates(),
            ['email' => 'required']
        ));

        $user = User::create($request->only(['name', 'phone', 'password']));

        $user->actAs(Role::representative());

        return redirect()->to('/representatives');
    }
}
