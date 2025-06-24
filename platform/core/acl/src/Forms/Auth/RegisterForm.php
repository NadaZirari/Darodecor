<?php

namespace Botble\ACL\Forms\Auth;

use Botble\ACL\Http\Requests\RegisterRequest;
use Botble\ACL\Models\User;
use Botble\ACL\Http\Controllers\Auth\RegisterController;

use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CheckboxField;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\PasswordField;
use Botble\Base\Forms\Fields\TextField;

class RegisterForm extends AuthForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setValidatorClass(RegisterRequest::class)
            ->setUrl(route('access.register.post'))
            ->heading(__('acl/auth.register.title'))
            ->add(
                'first_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('acl/auth.register.first_name'))
                    ->placeholder(__('acl/auth.register.placeholder.first_name'))
                    ->required()
                    ->attributes(['tabindex' => 1])
            )
            ->add(
                'last_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('acl/auth.register.last_name'))
                    ->placeholder(__('acl/auth.register.placeholder.last_name'))
                    ->required()
                    ->attributes(['tabindex' => 2])
            )
            ->add(
                'username',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('acl/auth.register.username'))
                    ->placeholder(__('acl/auth.register.placeholder.username'))
                    ->attributes(['tabindex' => 3])
            )
            ->add(
                'email',
                EmailField::class,
                TextFieldOption::make()
                    ->label(__('acl/auth.register.email'))
                    ->placeholder(__('acl/auth.register.placeholder.email'))
                    ->required()
                    ->attributes(['tabindex' => 4])
            )
            ->add(
                'password',
                PasswordField::class,
                TextFieldOption::make()
                    ->label(__('acl/auth.register.password'))
                    ->placeholder(__('acl/auth.register.placeholder.password'))
                    ->required()
                    ->attributes(['tabindex' => 5])
            )
            ->add(
                'password_confirmation',
                PasswordField::class,
                TextFieldOption::make()
                    ->label(__('acl/auth.register.password_confirmation'))
                    ->placeholder(__('acl/auth.register.placeholder.password_confirmation'))
                    ->required()
                    ->attributes(['tabindex' => 6])
            )
            ->submitButton(__('acl/auth.register.register'), 'ti ti-user-plus')
            ->add(
                'filters',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content(apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, User::class))
            );
    }
}
