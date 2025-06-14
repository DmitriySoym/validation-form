<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use App\Livewire\UserProfileForm;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

class UserProfileFormTest extends TestCase
{
    /** @test */
    public function form_renders_correctly()
    {
        Livewire::test(UserProfileForm::class)
            ->assertSee('Анкета пользователя');
    }

     /** @test */
    public function form_submits_successfully_with_valid_data()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg')->size(1024);

        Livewire::test(UserProfileForm::class)
            ->set('firstName', 'Иван')
            ->set('lastName', 'Иванов')
            ->set('birthDate', '1990-01-15')
            ->set('phones', [['country_code' => '+7', 'number' => '998887766']])
            ->set('maritalStatus', 'Женат/замужем')
            ->set('files', [$file])
            ->set('rulesAccepted', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('Успешно');

        $this->assertDatabaseHas('user_profiles', [
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => ''
        ]);
        
        Storage::disk('public')->assertExists('user_files/' . $file->hashName());
    }

    /** @test */
    public function first_name_is_required()
    {
        Livewire::test(UserProfileForm::class)
            ->set('firstName', '')
            ->call('save')
            ->assertHasErrors(['firstName' => 'required']);
    }

     /** @test */
    public function email_or_phone_is_required()
    {
        Livewire::test(UserProfileForm::class)
            ->set('email', '')
            ->set('phones', [['country_code' => '+7', 'number' => '']])
            ->call('save')
            ->assertHasErrors(['email' => 'required_without_all']);
    }

}