<?php
namespace app\widgets;

use \PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Class XlsxHelper
 * @package app\widgets
 */
class XlsxHelper
{
    /**
     * Create a .xlsx file from an array.
     *
     * @param $outputFileName
     * @param $data
     * @return string
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function create($outputFileName, $data)
    {
        $spreadsheet = new Spreadsheet();

        // Set document properties
        $spreadsheet->getProperties()->setCreator('Lennert van Overbeeke')
            ->setLastModifiedBy('Lennert van Overbeeke')
            ->setTitle('KNMI data')
            ->setSubject("KNMI data")
            ->setDescription("KNMI data from a specific time interval")
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Result file');
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setTitle('KNMI data');

        try {
            $spreadsheet->getActiveSheet()
                ->fromArray(
                    $data,
                    NULL,
                    'A1'
                );
        } catch (Exception $e) {
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        return $writer->save($outputFileName);
    }
}