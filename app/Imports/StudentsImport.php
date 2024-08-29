<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function model(array $row)
    {
        return new Student([
            'name' => $row['nombre'],
            'grade' => $row['grado'],
            'institution' => $row['institucion'],
            'section' => $row['seccion'],
            'user_id' => $this->userId, // Asignar el user_id
        ]);
    }
}
?>