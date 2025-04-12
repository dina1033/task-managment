<?php
namespace App\Filament\Pages\Auth;

use App\Enums\UserType;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                // Hidden field for admin type
                \Filament\Forms\Components\Hidden::make('type')
                    ->default(UserType::ADMIN)
                    ->dehydrateStateUsing(fn ($state) => $state->value)
            ]);
    }
}