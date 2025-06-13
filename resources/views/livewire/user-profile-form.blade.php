<div>
    @if ($isSaved)
    <div class="alert alert-success text-center">
        <h2>Успешно</h2>
        <p>Ваши данные были успешно отправлены.</p>
    </div>
    @else
    {{-- Prevent submission on Enter key press within the form --}}
    <form wire:submit.prevent="save" wire:keydown.enter.prevent>
        <div class="card">
            <div class="card-header">
                <h3>Анкета пользователя</h3>
            </div>
            <div class="card-body">
                {{-- Name Fields --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="firstName" class="form-label">Имя <span class="text-danger">*</span></label>
                        <input type="text" id="firstName" class="form-control @error('firstName') is-invalid @enderror"
                            wire:model.live="firstName">
                        @error('firstName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="lastName" class="form-label">Фамилия <span class="text-danger">*</span></label>
                        <input type="text" id="lastName" class="form-control @error('lastName') is-invalid @enderror"
                            wire:model.live="lastName">
                        @error('lastName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="patronymic" class="form-label">Отчество</label>
                        <input type="text" id="patronymic"
                            class="form-control @error('patronymic') is-invalid @enderror" wire:model.live="patronymic">
                        @error('patronymic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Birth Date & Email --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="birthDate" class="form-label">Дата рождения <span
                                class="text-danger">*</span></label>
                        <input type="date" id="birthDate" class="form-control @error('birthDate') is-invalid @enderror"
                            wire:model.live="birthDate">
                        @error('birthDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"
                            wire:model.live="email" placeholder="user@example.com">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Phone Numbers --}}
                <div class="mb-3">
                    <label class="form-label">Телефон(ы)</label>
                    @foreach($phones as $index => $phone)
                    <div class="input-group mb-2">
                        <select class="form-select @error('phones.'.$index.'.country_code') is-invalid @enderror"
                            style="max-width: 100px;" wire:model.live="phones.{{ $index }}.country_code">
                            <option value="+7">+7</option>
                            <option value="+375">+375</option>
                        </select>
                        <input type="tel" class="form-control @error('phones.'.$index.'.number') is-invalid @enderror"
                            wire:model.live="phones.{{ $index }}.number" placeholder="1234567890">
                        @if(count($phones) > 1)
                        <button class="btn btn-outline-danger" type="button"
                            wire:click="removePhoneNumber({{ $index }})">-</button>
                        @endif
                    </div>
                    @error('phones.'.$index.'.number') <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    @endforeach
                    @if(count($phones) < 5) <button class="btn btn-outline-primary btn-sm" type="button"
                        wire:click="addPhoneNumber">+</button>
                        @endif
                </div>

                {{-- Marital Status & About Me --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="maritalStatus" class="form-label">Семейное положение <span
                                class="text-danger">*</span></label>
                        <select id="maritalStatus" class="form-select @error('maritalStatus') is-invalid @enderror"
                            wire:model.live="maritalStatus">
                            <option value="">Выберите...</option>
                            <option value="Холост/не замужем">Холост/не замужем</option>
                            <option value="Женат/замужем">Женат/замужем</option>
                            <option value="В разводе">В разводе</option>
                            <option value="Вдовец/вдова">Вдовец/вдова</option>
                        </select>
                        @error('maritalStatus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="aboutMe" class="form-label">О себе</label>
                        <textarea id="aboutMe"
                            class="form-control resizable-vertical @error('aboutMe') is-invalid @enderror" rows="3"
                            wire:model.live="aboutMe"></textarea>
                        @error('aboutMe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- File Upload --}}
                <div class="mb-3">
                    <label for="files" class="form-label">Файлы (до 5 шт., max 5MB, jpg, png, pdf) <span
                            class="text-danger">*</span></label>
                    <input type="file" id="files" class="form-control @error('files.*') is-invalid @enderror"
                        wire:model.live="files" multiple>
                    @error('files.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @error('files') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    {{-- Loading indicator --}}
                    <div wire:loading wire:target="files" class="form-text">Загрузка файлов...</div>
                </div>

                {{-- Rules Checkbox --}}
                <div class="form-check mb-3">
                    <input class="form-check-input @error('rulesAccepted') is-invalid @enderror" type="checkbox"
                        id="rulesAccepted" wire:model.live="rulesAccepted">
                    <label class="form-check-label" for="rulesAccepted">
                        Я ознакомился c правилами <span class="text-danger">*</span>
                    </label>
                    @error('rulesAccepted') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary" @if(!$isFormValid) disabled @endif>
                    <span wire:loading.remove wire:target="save">Отправить</span>
                    <span wire:loading wire:target="save">Отправка...</span>
                </button>
            </div>
        </div>
    </form>
    @endif
</div>