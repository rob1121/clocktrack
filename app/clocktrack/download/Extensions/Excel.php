<?php

namespace App\Clocktrack\Download\Extensions;

class Excel 
{
    public function export($filename, $data) 
    {
        //export to excel
        Excel::create($filename, function ($excel) use ($timesheets) {
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
        $sheet->fromArray($timesheets);
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