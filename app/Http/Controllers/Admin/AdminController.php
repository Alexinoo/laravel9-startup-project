<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function profile()
    {
        $id = Auth::user()->id;

        $user = User::find($id);

        return view('Admin.Profile.index',compact('user'));
    }

    public function EditProfile($id)
    {
        $user = User::find($id);

        return view('Admin.profile.edit', compact('user'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required']
        ],[
            'username.required' => 'Username cannot be blank'
        ]);
        $user = User::find(Auth::user()->id);
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;

        //LOGIC - Delete from destination and upload a new image

        if ($request->hasfile('profile_image')) {

            $destination = 'upload/admin_images/' . $user->profile_image;

            // /Check if image exists in the destination folder
            if (File::exists($destination)) {

                // IF SO - DELETE
                File::delete($destination);
            }

            //PROCEED WITH THE UPLOAD
            $file = $request->file('profile_image');
            // get file extension
            $filename = time() . '.' . $file->getClientOriginalExtension();
            //Use move() to upload the file in the uploads folder
            //Takes 2 parameters - ( location , filename )
            $file->move('upload/admin_images/', $filename);
            //Save the filename in the db
            $user->profile_image = $filename;
        }

        $user->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.profile')->with($notification);
    }

    // Change Password
    public function ChangePassword(){

        return view('Admin.Password.change-password');
    }


    // Update Password
    public function UpdatePassword(Request $request){

       
        $data = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => ['required','same:new_password']
        ]);

        $vHashedPassword = Auth::user()->password;       

        // If $request->current_password ===  $vHashedPassword

        if (Hash::check($request->current_password,  $vHashedPassword)) {

            $user = User::find(Auth::id());
            $user->password = bcrypt($request->new_password);
            $user->save();

            // Destroy session /logout
            Auth::logout();

            $notification = array(
                'message' => 'Password changed successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('login')->with($notification);
        } else {

            $notification = array(
                'message' => 'Current Password is invalid',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }
    }



    // Logout Functionality

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'User Logout successfully',
            'alert-type' => 'success'
        );

        return redirect('/login')->with($notification);
    }
}
