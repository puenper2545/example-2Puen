<?php
use App\Http\Controllers;
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index (Request $request){
        $users = User::get();
        return view ('users.index',compact('users'));
    }
    public function create()
    {
        return view('users.create');
    }
    public function insert(Request $request)
    {
        $request->all();

        $request->validate([
            'username'=>['required','unique:users','max:10'],
            'password'=>['required','min:8'],
            'fullname'=>['required'],
            'role'=>'required',

        ],[
            'username.required'=>'กรุณาป้อน Username',
            'username.max'=>'Username ต้องไม่เกิน 10 อักษร',
            'password.required'=>'กรุณาป้อน Passsword',
            'password.min'=>'Password ต้อง 8 ตัวขึ้นไป',
            'fullname.required'=>'กรุณาป้อน ชื่อ-สกุล',
            'role.required'=>'กรุณาป้อน สิทธใช้งาน'
        ]);
        //dd($request-> all());
       // $request->username;
       $users = new User;

        $users->username = $request->username;
        $users->password = Hash::make($request->password);
        $users->fullname = $request->fullname;
        $users->role = $request->role;
       $checkOk = $users->save();
       if ($checkOk){
        Alert::success('Success','บันทึกข้อมูลสำเร็จ');

        return redirect()->route('users');

       }else{
        Alert::error('Error','ไม่สามารถบันทึกข้อมูลได้');

        return back();
       }
    }


       public function edit(Request $request,$id)
{
    $users =User::where('id',$id)->first();
    return view('users.edit',compact('users'));
}
public function update(Request $request)
{
$users =User::where('id',$request->id)->first();

$users->username = $request->username;
$users->fullname = $request->fullname;
$users->role = $request->role;
$users->save();

Alert::success('Success','อัพเดตข้อมูลสำดร็จ');
return redirect()->route('users');
}
public function delete(Request $request,$id)
{
    $users =User::find($id);

    if($users)
    {
        $users->delete();
        Alert::success('Success','ลบข้อมูลสำเร็จ');
        return redirect()->back();
    }else{
        Alert::error('Error','ไม่สามารถลบข้อมูลได้');
        return redirect()->back();
    }
}
}

