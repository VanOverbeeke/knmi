<?php

namespace app\controllers;

use app\widgets\XlsxHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Query;
use app\models\Custom;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class QueryController extends Controller
{
    /**
     * Return a file name based on timestamp and output format
     *
     * @param $timestamp
     * @param string $type
     * @return string
     */
    public function getFileName($timestamp, $type = 'csv')
    {
        if ($type === 'xlsx') {
            $outputDir = '../output';

            return $outputDir . $timestamp . '.xlsx';
        }
        $outputDir = '/home/lennert/projects/knmi/output/';

        return $outputDir . $timestamp . '.csv';
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays query form page.
     *
     * @return string
     */
    public function actionCreate()
    {
        $query = new Query;

        if (isset(Yii::$app->request->post()['Query'])) {
            $requestData = Yii::$app->request->post()['Query'];
            $requestData['start'] = Custom::dateFormat($requestData['start'], 'knmi');
            $requestData['end'] = Custom::dateFormat($requestData['end'], 'knmi');
            $requestData['vars'] = join(':', $requestData['vars']);
            $requestData['stns'] = join(':', $requestData['stns']);

            return $this->actionSubmit($requestData);
        }

        return $this->render('create', ['query' => $query]);
    }

    public function actionSubmit(array $requestData)
    {
        $url = 'http://projects.knmi.nl/klimatologie/daggegevens/getdata_dag.cgi';
        $timestamp = date('YmdHis');
        $outputFile = $this->getFileName($timestamp);
        $client = new Client();

        try {
            $response = $client->post(
                $url,
                [
                    'form_params' => $requestData,
                    'sink' => $outputFile,
                ]
            );

            return $this->actionLoading($timestamp);
        } catch (RequestException $e) {
            echo $e->getRequest();
            if ($e->hasResponse()) {
                echo $e->getResponse();
            }
        }
    }

    public function actionLoading($timestamp)
    {
        $outputfile = $this->getFileName($timestamp);

        return $this->render('loading', compact('timestamp', 'outputFile'));
    }

    public function actionResult($timestamp)
    {
        $inputFile = $this->getFileName($timestamp);
        $handle = fopen($inputFile, 'r');

        $csvData = [];
        $xlsxData = [];
        while (($line = fgetcsv($handle)) !== false) {
            if (count($line) === 3 && (substr($line[0], 0, 1) !== '#')) {
                $xlsxDateTime = $line[1];
                $csvDateTime = strtotime($line[1]) * 1000;
                $temp = preg_replace('/\s+/', '', $line[2]);
                $csvData[] = [
                    'x' => $csvDateTime,
                    'y' => $temp
                ];
                $xlsxData[] = [
                    $xlsxDateTime,
                    $temp
                ];
            }
        }

        $outputFileName = '../output/' . $timestamp . '.xlsx';
        (new XlsxHelper())->create($outputFileName, $xlsxData);

        return $this->render('result', compact('csvData'));
    }
}
