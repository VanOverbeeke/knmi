<?php

namespace app\controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Query;
use app\models\Custom;

class QueryController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
//            'value' => function($event) {
//            $format = "Y/m/d";
//            return date()
//            }
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
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
            $requestData['start'] = Custom::dateFormat($requestData['start'], 'KNMI_FORMAT');
            $requestData['end'] = Custom::dateFormat($requestData['end'], 'KNMI_FORMAT');
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
        $outputFile = '/home/lennert/projects/knmi/output/' . $timestamp . '.csv';
        $client = new Client();

        try {
            $response = $client->post(
                $url,
                [
                    'form_params' => $requestData,
                    'sink' => $outputFile,
                ]
            );

            return $this->actionLoading($outputFile);
        } catch (RequestException $e) {
            echo $e->getRequest();
            if ($e->hasResponse()) {
                echo $e->getResponse();
            }
        }
    }

    public function actionLoading($outputFile)
    {
        return $this->render('loading', compact('outputFile'));
    }

    public function actionResult($outputFile)
    {
        $handle = fopen($outputFile, 'r');
        $data = [];
        while (($line = fgetcsv($handle)) !== false) {
            if ( count($line) === 3 && (substr($line[0], 0, 1) !== '#')) {
                $datum = [
                    'x' => $line[1],
                    'y' => preg_replace('/\s+/', '', $line[2])
                ];
                $data[] = $datum;
            }
        }

        return $this->render('result', compact('data'));
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
