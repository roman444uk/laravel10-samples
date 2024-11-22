<?php

namespace App\Data;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PatientData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public ?int               $id,
        public ?int               $doctorId,
        public ?string            $fullName,
        public ?string            $phone,
        public ?string            $email,
        public ?string            $gender,
        public Carbon|string|null $birthDate,
        public ?string            $comment,
        public ?Carbon            $createdAt,
        public ?Carbon            $updatedAt,
    )
    {
    }

    public static function fromModel(Patient $patient): self
    {
        return new self(
            $patient->id,
            $patient->doctor_id,
            $patient->full_name,
            $patient->phone,
            $patient->email,
            $patient->gender,
            $patient->birth_date?->format('d.m.Y'),
            $patient->comment,
            $patient->created_at,
            $patient->updated_at,
        );
    }

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validated();

        return self::from(array_merge($validated, [
            'id' => $request->validated('id'),
            'doctorId' => getDoctorId(),
            'fullName' => $request->validated('full_name'),
            'phone' => $request->validated('phone'),
            'email' => $request->validated('email'),
            'gender' => $request->validated('gender'),
            'birthDate' => $request->validated('birth_date') ? Carbon::parse($request->validated('birth_date')) : null,
            'comment' => $request->validated('comment'),
        ]));
    }
}

