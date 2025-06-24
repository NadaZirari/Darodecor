<?php

namespace Botble\ACL\Http\Controllers\Auth;
use Botble\ACL\Forms\Auth\RegisterForm;
use Botble\ACL\Http\Controllers\Controller;
use Botble\ACL\Models\User;
use Botble\ACL\Models\Role;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     */
    public function showRegistrationForm()
    {
        page_title()->setTitle(trans('acl/auth.register.title'));

  $form = RegisterForm::create();
    
    return $form->renderForm();    }


      public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new \Illuminate\Auth\Events\Registered($user = $this->create($request->all())));

        // ✅ CONNEXION AUTOMATIQUE APRÈS INSCRIPTION
        Auth::login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath())
                            ->with('success_msg', trans('acl/auth.register.success_and_login'));
    }


    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'username' => ['nullable', 'string', 'max:60', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'first_name.required' => trans('acl/auth.register.validation.first_name_required'),
            'last_name.required' => trans('acl/auth.register.validation.last_name_required'),
            'email.required' => trans('acl/auth.register.validation.email_required'),
            'email.unique' => trans('acl/auth.register.email_exist'),
            'username.unique' => trans('acl/auth.register.username_exist'),
            'password.required' => trans('acl/auth.register.validation.password_required'),
            'password.min' => trans('acl/auth.register.validation.password_min'),
            'password.confirmed' => trans('acl/auth.register.validation.password_confirmed'),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'super_user' => false,
            'manage_supers' => false,
        ]);

        // Assigner le rôle par défaut
        $defaultRole = Role::where('is_default', 1)->first();
        if ($defaultRole) {
            $user->roles()->attach($defaultRole->id);
        }

        return $user;
    }

       protected function registered(Request $request, $user)
    {
         return redirect('/admin')
        ->with('success_msg', trans('acl/auth.register.welcome', ['name' => $user->first_name]));
    }
}
