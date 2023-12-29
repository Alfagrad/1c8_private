<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PriceExcel implements FromArray, WithColumnFormatting, WithColumnWidths, WithHeadings
{

    private array $price = [];

    /**
     * @param array $price
     */
    public function __construct(array $price)
    {
        $this->price = $price;
    }

    public function array(): array
    {
        return $this->price;
    }

    public function columnFormats(): array
    {
        return [
//            'V' => NumberFormat::FORMAT_NUMBER,
//            'V' => DataType::TYPE_NUMERIC,
//            'V' => \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_ERROR,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 40,
            'C' => 50,
        ];
    }

    public function headings(): array
    {
        return count($this->price) > 0 ? array_keys($this->price[0]) : [];
    }

    public function name(): string
    {
        return 'price_from_' . Carbon::now()->format('d.m.Y') . '.xlsx';
    }



}
