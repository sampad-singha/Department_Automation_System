<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithSkipDuplicates, withValidation
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $department_id = self::getDepartmentId($row['department_code']);
        $password = Str::random(12); // Generate a random 12-character password

        $user = new User([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($password),
            'university_id' => $row['university_id'],
            'department_id' => $department_id,
            'session' => $row['session'],
            'year' => 1,
            'semester' => 1,
            'dob' => $row['dob'],
            'phone' => $row['phone'],
            'address' => $row['address'],
            'city' => $row['city'],
            'designation' => 'student',
            'publication_count' => 0,
            'status' => 'active',
        ]);
        $user->assignRole('student');
        return $user;
    }

    public function rules(): array
    {
        return [
            '*.name'            => 'required|string|max:255',
            '*.email'           => 'required|email|unique:users,email',
            '*.university_id'   => 'required|string|unique:users,university_id',
            '*.department_code' => 'required|string|exists:departments,short_name',
            '*.session'         => 'required|string',
            '*.dob'             => 'required|date',
            '*.phone'           => 'nullable|string|max:11|min:11',
            '*.address'         => 'nullable|string',
            '*.city'            => 'nullable|string',
        ];
    }

    public static function getDepartmentId(string $department)
    {
        return Department::where('short_name', $department)->first()->id;
    }
}
