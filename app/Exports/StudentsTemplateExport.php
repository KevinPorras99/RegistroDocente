<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class StudentsTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'Nombre', 'Grado', 'Institución', 'Sección', 'Ejemplo de Sección 10--2'
        ];
    }

    public function array(): array
    {
        return [];
    }
}

?>