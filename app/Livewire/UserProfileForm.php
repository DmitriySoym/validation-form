<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use App\Models\UserProfile;

class UserProfileForm extends Component
{
    use WithFileUploads;

    public $firstName = '';
    public $lastName = '';
    public $patronymic = '';
    public $birthDate = '';
    public $email = '';
    public $phones = [['country_code' => '+7', 'number' => '']];
    public $maritalStatus = '';
    public $aboutMe = '';
    public $files = [];
    public $rulesAccepted = false;

    public $isSaved = false;
    public $isFormValid = false;

    protected function rules()
    {
        return [
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'patronymic' => 'nullable|string|max:100',
            'birthDate' => 'required|date|before:today',
            'email' => 'nullable|email|required_without_all:phones.0.number',
            'phones.*.country_code' => 'required|in:+375,+7',
            'phones.*.number' => 'nullable|required_if:email,null|regex:/^\d{10}$/',
            'maritalStatus' => 'required|string|in:Холост/не замужем,Женат/замужем,В разводе,Вдовец/вдова',
            'aboutMe' => 'nullable|string|max:1000',
            'files.*' => 'required|file|max:5120|mimes:jpg,png,pdf',
            'files' => 'required|array|max:5',
            'rulesAccepted' => 'accepted',
        ];
    }

    protected $messages = [
        'email.required_without_all' => 'Необходимо указать Email или Телефон.',
        'phones.*.number.required_if' => 'Необходимо указать Email или Телефон.',
        'phones.*.number.regex' => 'Телефон должен состоять из 10 цифр (без кода страны).',
        'files.*.max' => 'Размер файла не должен превышать 5МБ.',
        'files.max' => 'Можно загрузить не более 5 файлов.',
        'rulesAccepted.accepted' => 'Вы должны принять правила.',
    ];
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        $this->checkFormValidity();
    }

    public function checkFormValidity()
    {
        $validator = Validator::make($this->all(), $this->rules());
        $this->isFormValid = !$validator->fails();
    }

    public function addPhoneNumber()
    {
        if (count($this->phones) < 5) {
            $this->phones[] = ['country_code' => '+7', 'number' => ''];
        }
    }

    public function removePhoneNumber($index)
    {
        unset($this->phones[$index]);
        $this->phones = array_values($this->phones);
        $this->checkFormValidity();
    }

    public function save()
    {
        $validatedData = $this->validate();

        $filePaths = [];
        foreach ($this->files as $file) {
            $filePaths[] = $file->store('user_files', 'public');
        }

        $profileData = [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'patronymic' => $this->patronymic,
            'birth_date' => $this->birthDate,
            'email' => $this->email,
            'phones' => $this->phones,
            'marital_status' => $this->maritalStatus,
            'about_me' => $this->aboutMe,
            'files' => $filePaths,
        ];

        UserProfile::create($profileData);

        $this->isSaved = true;
    }

    public function render()
    {
        return view('livewire.user-profile-form')->layout('components.layout.app');
    }
}