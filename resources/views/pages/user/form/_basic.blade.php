@php
    use App\Enums\Users\UserRoleEnum;

    /**
     * @var App\Models\User $user
     * @var Illuminate\Support\Collection $citiesCollection
     */

    $crumbs = [
        [
            'label' => __('users.profile'),
        ]
    ];
@endphp

@if(isUserClientManager())
    <input type="hidden" name="doctor[client_manager_id]" value="{{ getUserId() }}">
@endif

<div class="row">
    <div class="col-md-6">
        <div class="form-group local-forms">
            <x-form.input id="full_name"
                          class="floating"
                          type="text"
                          name="profile[full_name]"
                          :value="old('full_name', $user->profile->full_name)"
                          :label="__('users.full_name')"
                          :required="true"
            ></x-form.input>
        </div>

        <div class="form-group local-forms">
            <x-form.input id="email"
                          type="text"
                          name="email"
                          :value="old('email', $user->email)"
                          :label="__('users.email')"
                          :required="true"
                          placeholder="example@example.com"
            ></x-form.input>
        </div>

        <div class="form-group local-forms">
            <x-form.input id="phone"
                          type="text"
                          name="profile[phone]"
                          :value="old('phone', $user->profile->phone)"
                          :label="__('users.phone')"
                          :required="true"
                          data-input-mask="phone"
            ></x-form.input>
        </div>

        <div class="form-group local-forms">
            <x-form.select id="city-id"
                           class="placeholder js-states"
                           type="text"
                           name="profile[city_id]"
                           :options="$citiesCollection"
                           :selected-value="old('city_id', $user->profile->city_id)"
                           :label="__('users.city')"
                           :required="true"
            ></x-form.select>
        </div>
    </div>
    <div class="col-md-6">
        @can('updateRole', $user)
            <div class="form-group local-forms">
                <x-form.select id="role"
                               class="floating"
                               type="text"
                               name="role"
                               :options="UserRoleEnum::getTranslationMap('users.role_enums', 'casesAvailable')->prepend('', '')"
                               :selected-value="old('role', $user->role)"
                               :label="__('users.role')"
                               :required="true"
                               :disabled="$user && $user->id === getUserId()"
                ></x-form.select>
            </div>
        @endcan

        <div class="form-group local-forms">
            <x-form.input id="password"
                          class="pass-input"
                          type="password"
                          name="password"
                          :label="__('users.password')"
                          :required="!$user->exists"
                          input-icon="profile-views feather-eye-off toggle-password"
            ></x-form.input>
        </div>

        @if(!$user->exists)
            <div class="form-group">
                <x-form.checkbox id="first-login-new-password"
                                 name="first_login_new_password"
                                 :label="__('Новый пароль при входе')"
                ></x-form.checkbox>
            </div>
        @endif
    </div>
</div>
