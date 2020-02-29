<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Account;

class AccountsController extends Controller
{
    public function create(Request $request){
    	//validate request
    	 $this->validateRequest($request);
 
    	//create user
    	$user = $this->createUser();


        if(!$user){
        	return response()->json(['message'=>'something went wrong']);
        }

    	//create profile
    	$this->createProfile($user,$request);

    	//create account
    	$user_account = $this->createAccount($user,$request);

		if(!$user_account){
        	return response()->json(['message'=>'can\'t set this account up']);
        }
    	// return response
    	return response()->json(['success'=>'account created successfully','account_no'=>$user->account->account_no, 'account_name'=>$user->account->account_name,'account_type'=>$user->account->account_type,'available_balance'=>$user->account->available_balance],200);

    }

    public function credit(Request $request){
    	$request->validate([
    		'sender' => 'required|string',
    		'account_no' => 'required|string|min:10|max:10',
    		'amount' => 'required|numeric',
    	]);

    	$account = Account::where('account_no',$request->account_no)->first();
    	if(!$account){
    		return response()->json(['message'=>'sorry we cannot find a user with the privided account no/account name'],404);
    	}

    	$update = $account->update([
    		'available_balance'=> $request->amount
    	]);

    	return response()->json(['success'=>'account created successfully','account_no'=>$account->account_no, 'account_name'=>$account->account_name,'account_type'=>$account->account_type,'available_balance'=>$account->available_balance],200);
    }


    private function validateRequest($request){
    	$request->validate([
    		'first_name' => 'required|string|min:3|max:13',
    		'last_name' => 'required|string|min:3|max:13',
    		'middle_name' => 'required|string|min:3|max:13',
    		'phone' => 'required|string|min:11|max:11',
    		'email' => 'required|string|email|unique:users',
    		'password' => 'required|string|min:8|confirmed',
    		'pin' => 'required|string|min:4|max:4',
    		'gender' => 'required|string|min:1|max:1',
    		'account_type' => 'required|string',
    		'dob' => 'required|string',
    		'nationality' => 'required|string',
    		'state' => 'required|string',
    		'city' => 'required|string',
    		'marital_status' => 'required|string',
    		'employment_status' => 'required|string',
    		'state_of_origin' => 'required|string',
    	]);
    }
    private function createUser(){

    return User::create([
            'first_name' => request()->first_name,
            'last_name' => request()->last_name,
            'middle_name' => request()->middle_name,
            'email' => request()->email,
            'phone' => request()->phone,
            'password' => Hash::make(request()->password),
            'gender' => request()->gender,
            'pin' => request()->pin,
            'account_type' => request()->account_type
        ]);

    }
    private function createProfile($user,$request){
    	return $user->profile()->create([
            'nationality' => $request->nationality,
            'state' => $request->state,
            'city' => $request->city,
            'street' => $request->street,
            'dob' => $request->dob,
            'pin' => Hash::make($request->pin),
            'employment_status' => $request->employment_status,
            'marital_status' => $request->marital_status,
            'state_of_origin' => $request->state_of_origin
        ]);
    }

    private function createAccount($user,$request){
    	$tg = rand(50,7000)*time();
		$account_no = "002".sprintf("%0.7s",str_shuffle($tg));
		return $user->account()->create([
			'account_no' => $account_no,
			'account_name' => $user->firstname ." ". $user->lastname ." ".$user->middle_name,
			'account_type' => $request->account_type,
			'available_balance' => 0
		]);
    }
}
