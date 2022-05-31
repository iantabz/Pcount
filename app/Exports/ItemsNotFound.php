<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class ItemsNotFound implements FromView, ShouldAutoSize, WithEvents
{
    use Exportable, RegistersEventListeners;

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $event->getWriter()
                    ->getDelegate()
                    ->getActiveSheet()
                    ->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
            }
        ];
    }

    public function view(): View
    {
        $data = session()->get('data');
        // dd($data);
        return $data['type'] == 'Excel' ?  view('reports.pcount_app_notfound_excel', ['data' => $data]) : view('reports.pcount_app_notfound', ['data' => $data]);
        // return view('reports.pcount_app_notfound_excel', ['data' => session()->get('data')]);
    }

    // public function columnWidths(): array
    // {
    //     return [
    //         'A' => 20,
    //         'B' => 30,
    //         'C' => 20,
    //         'D' => 30,
    //         'E' => 20
    //     ];
    // }
}
