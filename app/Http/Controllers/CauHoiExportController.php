<?php

namespace App\Exports;

use App\Models\BaiThi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CauHoiExportController implements FromCollection, WithHeadings
{
    protected $baiThiId;

    public function __construct(int $baiThiId)
    {
        $this->baiThiId = $baiThiId;
    }

    public function collection()
    {
        // Lấy danh sách câu hỏi từ cơ sở dữ liệu với ID bài thi được cung cấp
        $baiThi = BaiThi::findOrFail($this->baiThiId);

        $formattedData = [];
        foreach ($baiThi->danh_sach_cau_hoi as $cauHoi) {
            $formattedData[] = [
                'Câu hỏi' => $cauHoi['cau_hoi'],
                'Câu trả lời' => implode(', ', $cauHoi['cau_tra_loi']),
                'Đáp án đúng' => implode(', ', array_map(function ($dapAn) {
                    return $dapAn + 1; // Chuyển vị trí đáp án đúng thành số thứ tự
                }, $cauHoi['dap_an_dung'])),
            ];
        }

        return new Collection($formattedData);
    }

    public function headings(): array
    {
        return [
            'Câu hỏi',
            'Câu trả lời',
            'Đáp án đúng',
        ];
    }

    
}
