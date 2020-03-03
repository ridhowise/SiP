<?php

namespace App\Http\Controllers;

use App\Http\Models\AdminModel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Auth;

class passwordController extends BaseController
{
    public function password()
    {
        $bio = AdminModel::paginate(4);
        // $bio = passwordModel::get();
        return view('password', ['bio' => $bio]);
    }

    public function getInput()
{
    return view('inputpassword');
}

public function simpanpassword(Request $request)
{
    $file = Input::file('foto');
    $valfile = array('image' => $file);
    $valrules = array('image' => 'image');

    $validator = Validator::make($valfile, $valrules);
    if ($validator->fails()) {
        return redirect()->action('passwordController@password')->with('style', 'danger')->with('alert', 'Gagal Di Upload ! ')->with('msg', 'Cek Tipe File');
    }else{
        $destinationPath = 'uploads';
        $size = $file->getSize();
        $extension = $file->getClientOriginalExtension();
        $fileName = $file->getClientOriginalName();
        $upload_success = $file->move($destinationPath, $fileName);


        if ($upload_success){

          if (! function_exists('bcrypt')) {
    /**
     * Hash the given value against the bcrypt algorithm.
     *
     * @param  string  $value
     * @param  array  $options
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return app('hash')->driver('bcrypt')->make($value, $options);
    }
}
            $bio = new AdminModel;
            $bio->name = $request->input('name');
            $bio->jabatan = $request->input('jabatan');
            $bio->email = $request->input('email');
            $bio->password = bcrypt ($request->input('password'));
            $bio->foto = $fileName;

            $bio->save();

            return redirect()->action('passwordController@password')->with('style', 'success')->with('alert', 'Berhasil Disimpan ! ')->with('msg', 'Data Disimpan Di Database');


}
}
}
public function showChangePasswordForm(){
    return view('auth.changepassword');
}
public function __construct()
    {
        $this->middleware('auth');
    }
    public function changePassword(Request $request){

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $bio = Auth::user();
        $bio->password = bcrypt($request->get('new-password'));
        $bio->save();

        return redirect()->back()->with("success","Password changed successfully !");

    }

public function getEdit($id)
{
    return view('editpassword', ['password' => passwordModel::findOrFail($id)]);
}

public function ubahpassword(Request $request)
{

    $bio     = new passwordModel;
    $id     = $request->input('id');
    $bio     = passwordModel::find($id);

    $bio->name = $request->input('name');
    $bio->jabatan = $request-> input('jabatan');
    // $bio->jabatan = $request-> input('password');

    $bio->save();

    return redirect()->action('passwordController@password')->with('style', 'success')->with('alert', 'Berhasil Diubah ! ')->with('msg', 'Data Diubah Di Database');


}

public function getDelete($id)
{
    $bio     = new passwordModel;
    $bio     = passwordModel::find($id);
    $bio->delete();

    return redirect()->action('passwordController@password')->with('style', 'success')->with('alert', 'Berhasil Dihapus ! ')->with('msg', 'Data Dihapus Di Database');
}

}



?>
