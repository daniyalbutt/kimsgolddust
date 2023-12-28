<?php

namespace App\Http\Controllers\Auth;

use App\Profile;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Session;
use Mail;
use App\imagetable;
use DB;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/'; 

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator, 'registerForm');
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);
        
        Session::flash('message', 'New Account Created Successfully'); 
        Session::flash('alert-class', 'alert-success'); 
        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {       
        
        $emailData = DB::table('newaccounts')->first();
        $logo = imagetable::select('img_path')->where('table_name','=','logo')->first();
        $dataTwo = [
        'details'=>[
            
                'Email' =>$data['email'],
                'Name'=>$data['name'],
                'heading'=>$emailData->heading,
                'content'=>$emailData->content,
                'Logo'=>$logo->img_path,
                'WebsiteName'=>config('services.website.name'),
                'companyEmail'=>DB::table('m_flag')->where('id',218)->first()->flag_value,
                'companyPhone'=>DB::table('m_flag')->where('id',59)->first()->flag_value,
                    
            ]     
        ];
        
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            
            Mail::send('account-email', $dataTwo, function($message) use ($dataTwo) {
            
            $message->from('tony@websitesvalley.com', $dataTwo['details']['WebsiteName']);
    
            $message->to($dataTwo['details']['Email'])->subject($dataTwo['details']['heading']);
            
            }),
        ]);
       
    }

    protected function registered(Request $request, $user)
    {
        if($user->profile == null){
            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->localisation = $request->localisation;
            $profile->dob = $request->dob;
            $profile->save();
        }
        activity($user->name)
            ->performedOn($user)
            ->causedBy($user)
            ->log('Registered');
        $user->assignRole('user');
    }
}
