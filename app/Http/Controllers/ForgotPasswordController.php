<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
// use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\imagetable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Mail;
use DB;
use App\User;
use Carbon\Carbon;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        
        $logo = imagetable::
                     select('img_path')
                     ->where('table_name','=','logo')
                     ->first();
             
        $favicon = imagetable::
                     select('img_path')
                     ->where('table_name','=','favicon')
                     ->first();  

        View()->share('logo',$logo);
        View()->share('favicon',$favicon);
        
    }
    public function showForgetPasswordForm()
    {
        return view('auth.passwords.email');
    }
    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
              'email' => 'required|email|exists:users',
        ]);
  
    $token = Str::random(64);
  
    DB::table('password_resets')->insert([
        'email' => $request->email, 
        'token' => $token, 
        'created_at' => Carbon::now()
    ]);
    $emailData = DB::table('resetpasswords')->first();
	$logo = imagetable::select('img_path')->where('table_name','=','logo')->first();
    $data = [
        'details'=>[
            'Email' =>$request->email,
            'token'=>$token,
            'heading'=>$emailData->Heading,
            'content'=>$emailData->content,
            'Logo'=>$logo->img_path,
            'WebsiteName'=>config('services.website.name'),
            'companyEmail'=>DB::table('m_flag')->where('id',218)->first()->flag_value,
            'companyPhone'=>DB::table('m_flag')->where('id',59)->first()->flag_value,
                    
        ]
                
    ];   
    Mail::send('email.resetPassword', $data, function($message) use($data){
            $message->from('davidharry15235@gmail.com', "Kim's Gold Dust");
    
            $message->to($data['details']['Email'])->subject($data['details']['heading']);
    });
  
    return back()->with('message', 'We have e-mailed your password reset link!');
    }
    public function showResetPasswordForm($token) { 
        // dd($token);
        return view('auth.passwords.forgetPasswordLink', ['token' => $token]);
    }
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);
  
        $updatePassword = DB::table('password_resets')
                ->where([
                        'email' => $request->email, 
                        'token' => $request->token
                ])->first();
  
        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }
  
        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);
 
        DB::table('password_resets')->where(['email'=> $request->email])->delete();
        
        return redirect()->route('signin')->with('success', 'Password Change Successfully.');
        // return redirect->route('signin')->with('message', 'Your password has been changed!');
      }


}
