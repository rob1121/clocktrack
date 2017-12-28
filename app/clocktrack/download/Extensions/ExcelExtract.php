<?php

namespace App\Clocktrack\Download\Extensions;

use Maatwebsite\Excel\Facades\Excel;

class ExcelExtract
{
    protected $hasCustomHeader = 0;

    /**
     * export to excel
     *
     * @param [type] $filename
     * @param [type] $data
     * @return void
     */
    public function export($filename, $data) 
    {
        //export to excel
        Excel::create($filename, function ($excel) use ($data) {
            $this->setWorkBook($excel, $data);
        })->export('xls');
    }

    /**
     * set workbook
     *
     * @param array $excel
     * @param array $timesheets
     * @return void
     */
    protected function setWorkBook($excel, $timesheets)
    {
        $excel->sheet('Sheet 1', function ($sheet) use ($timesheets) {
            $this->setWorkSheet($sheet, $timesheets);
        });
    }

    /**
     * set worksheet
     *
     * @param array $sheet
     * @param array $timesheets
     * @return void
     */
    protected function setWorkSheet($sheet, $timesheets)
    {
        $data = $this->hasCustomHeader ? [$timesheets, null, 'A1', true, false] : [$timesheets];
        $sheet->fromArray(...$data);
    }

    /**
     * set as having a custom header
     *
     * @param [type] $bool
     * @return boolean
     */
    protected function hasCustomHeader($bool) 
    {
        $this->hasCustomHeader = $bool;
    }

    /**
     * set file name
     *
     * @param [type] $filename
     * @return void
     */
    protected function setFilename($filename)
    {
        $from = $this->from->format(config('constant.dateFormat'));
        $to = $this->to->format(config('constant.dateFormat'));
        $filename = "$filename {$from}_{$to}";
        $filename = str_replace('-', '', $filename);

        return $filename;
    }
}