<?php

namespace App\Models\Repositories;


use App\Data\DoctorData;
use App\Data\UserData;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DoctorRepository
{
    public function create(UserData $userData): ?User
    {
        return User::create([
            'name' => $userData->name,
            'email' => $userData->email,
            'password' => Hash::make($userData->password),
            'role' => $userData->role,
        ]);
    }

    public function updateOrCreate(User $user, DoctorData $doctorData): ?Doctor
    {
        return Doctor::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'client_manager_id' => $doctorData->clientManagerId ?? $user->doctor?->client_manager_id,
            'experience_from' => $doctorData->experienceFrom,
            'experience_with_aligners' => $doctorData->experienceWithAligners,
        ]);
    }
}
