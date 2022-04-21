<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
//Unknow
class CustomAuthController extends Controller
{
public function index()
{
return view('auth.login');
}
public function customLogin(Request $request)
{
$request->validate([
'email' => 'required',
'password' => 'required',
]);
//$credentials = $request->only('email', 'password');
$email = $request->get('email');
$password = $request->get('password');
$login_typpe = filter_var($email, FILTER_VALIDATE_EMAIL)?'email' : 'username';
// if (Auth::attempt($credentials)) {
if (Auth::attempt([$login_typpe =>$email, 'password'=>$password])) {
return redirect()->intended('dashboard')
->withSuccess('Signed in');
}
return redirect("login")->withSuccess('Login details are not valid');
}
public function registration()
{
return view('auth.registration');
}
public function customRegistration(Request $request)
{
$request->validate([
'username' => 'required',
'email' => 'required|email|unique:users',
'password' => 'required|min:6',
'phone' => 'required',
'first_name' => 'required',
'last_name' => 'required',
]);
$data = $request->all();
$check = $this->create($data);
return redirect("dashboard")->withSuccess('You have signed-in');
}
public function create(array $data)
{
return User::create([
'username' => $data['username'],
'email' => $data['email'],
'password' => Hash::make($data['password']),
'phone' => $data['phone'],
'first_name' => $data['first_name'],
'last_name' => $data['last_name'],
]);
}
public function dashboard()
{
if(Auth::check()){
return view('dashboard');
}
return redirect("login")->withSuccess('You are not allowed to access');
}
public function signOut() {
Session::flush();
Auth::logout();
return Redirect('login');
}
}