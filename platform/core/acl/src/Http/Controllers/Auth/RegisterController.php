<?php

namespace Botble\ACL\Http\Controllers\Auth;
use Botble\ACL\Forms\Auth\RegisterForm;
use Illuminate\Routing\Controller;
use Botble\ACL\Models\User;
use Botble\ACL\Models\Role;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     */
    protected $redirectTo ;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->redirectTo = route('dashboard.index');
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

        Log::info('Données reçues pour inscription:', $request->all());
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
    \Log::info('=== DÉBUT CRÉATION UTILISATEUR ===');
    \Log::info('Données reçues:', $data);
    
    // Forcer l'enregistrement avec save() au lieu de create()
    $user = new User();
    $user->first_name = $data['first_name'];
    $user->last_name = $data['last_name'];
    $user->username = $data['username'] ?? null;
    $user->email = $data['email'];
    $user->password = Hash::make($data['password']);
    $user->super_user = false;
    $user->manage_supers = false;
   
    // DEBUG CRITIQUE
    $emailVerifiedAt = now();
    \Log::info('Valeur now() générée:', [
        'value' => $emailVerifiedAt,
        'string' => (string)$emailVerifiedAt,
        'type' => gettype($emailVerifiedAt)
    ]);
    
    $user->email_verified_at = $emailVerifiedAt;
    \Log::info('email_verified_at assigné:', [
        'value' => $user->email_verified_at,
        'string' => (string)$user->email_verified_at,
        'type' => gettype($user->email_verified_at)
    ]);
    
    $user->last_login = now();
    
    // Vérifiez les attributs AVANT save
    \Log::info('Attributs AVANT save:', $user->getAttributes());
    
    $saved = $user->save();
    \Log::info('Résultat save():', ['success' => $saved, 'user_id' => $user->id]);
    
    // Vérifiez les attributs APRÈS save
    \Log::info('Attributs APRÈS save:', $user->getAttributes());
    
    // Rechargez depuis la BDD
    $user->refresh();
    \Log::info('Attributs APRÈS refresh:', $user->getAttributes());
    
    // Vérification finale spécifique
    \Log::info('VÉRIFICATION FINALE email_verified_at:', [
        'value' => $user->email_verified_at,
        'is_null' => is_null($user->email_verified_at),
        'string' => $user->email_verified_at ? (string)$user->email_verified_at : 'NULL'
    ]);
    
    // Assigner le rôle par défaut
    $defaultRole = Role::where('is_default', 1)->first();
    if ($defaultRole) {
        $user->roles()->attach($defaultRole->id);
        \Log::info('Rôle assigné');
    }

    // Créer une activation
    try {
        $activation = $user->activations()->create([
            'completed' => true,
            'completed_at' => now(),
        ]);
        \Log::info('Activation créée:', ['activation_id' => $activation->id]);
    } catch (\Exception $e) {
        \Log::error('Erreur création activation:', ['error' => $e->getMessage()]);
    }
    
    // Vérifier le statut final
    $user->refresh();
    \Log::info('Statut final utilisateur:', [
        'id' => $user->id,
        'email' => $user->email,
        'email_verified_at' => $user->email_verified_at,
        'activated' => $user->activated,
        'activations_count' => $user->activations()->count()
    ]);
    
    return $user;
}

       protected function registered(Request $request, $user)
    {
  return redirect()->route('dashboard.index')
          ->with('success_msg', trans('acl/auth.register.welcome', ['name' => $user->first_name]));
    }
}
