<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Serving the route...
     * Route::get('/user/{username}')
     */
    public function getProfile($username)
    {
        $user = User::where('username', $username)->first();
        $authUserIsFriend = Auth::user()->isFriendsWith($user);

        if (!$user) {
            abort(404);
        }

        $statuses = $user->statuses()
        ->notReply()
        ->orderBy('created_at', 'desc')
        ->paginate(3);
        
        return view('profile.index')
            ->with('user', $user)
            ->with('statuses', $statuses)
            ->with('authUserIsFriend', $authUserIsFriend);
    }

    public function getEdit()
    {
        return view('profile.edit');
    }

    /**
     * Serving the route:
     * Route::post('/profile/edit')
     */
    public function postEdit(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'alpha|max:50',
            'last_name' => 'alpha|max:50',
            'location' => 'max:20',
            'email' => [
                'required','email','max:225',
                Rule::unique('users')->ignore( Auth::user()->id),
            ],
            'username' =>[
                'required','alpha_dash','max:20',
                Rule::unique('users')->ignore(Auth::user()->id)
            ],
            ]);

        Auth::user()->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'location' => $request->input('location'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
        ]);


        return redirect()
            ->route('profile.edit')
            ->with('info', 'Your profile has been successfully updated.');

    }

    public function getUpdatePassword(){
        return view('profile.password');
    }

    public function postUpdatePassword(Request $request)
    {
        $this->validate($request, [
        
            'password' => 'required|confirmed|min:6',
            ]);

        Auth::user()->update([

            'password' => bcrypt($request->input('password')),
        ]);


        return redirect()
            ->route('home')
            ->with('info', 'Your password has been successfully updated.');

    }

  

}
