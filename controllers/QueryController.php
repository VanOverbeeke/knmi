<?php

namespace app\controllers;

use GuzzleHttp\Client;
use SebastianBergmann\Timer\Timer;
use Yii;
use yii\data\Pagination;
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
        $queries = Query::find()->all();

        return $this->render('index', compact('queries'));
    }

    /**
     * Displays query form page.
     *
     * @return string
     */
    public function actionCreate()
    {
        $new = Yii::$app->request->post();
        $query = new Query;

        if (isset(Yii::$app->request->post()['Query'])) {
            $input = Yii::$app->request->post()['Query'];
            $input['start'] = Custom::dateFormat($input['start']);
            $input['end'] = Custom::dateFormat($input['end']);

            $query->attributes = $input;
            $queryString = $this->createQueryString($input);
            var_dump($queryString);

            die();
            return $this->render('submit', ['queryString' => $queryString]);
        }

        return $this->render('create', ['query' => $query]);
    }

    /**
     * @param array $input
     * @return string
     */
    public function createQueryString(array $input)
    {
        $input['vars'] = ($input['vars']) ? implode(':', $input['vars']) : '';
        $input['stns'] = ($input['stns']) ? implode(':', $input['stns']) : '';
        $postDataComponents = [];

        foreach (['stns', 'vars', 'start', 'end', 'inseason'] as $var) {
            if (isset($input[$var])) {
                array_push($postDataComponents, $var . '=' . $input[$var]);
            }
        }

        $command = 'wget';
        $postData = implode('&', $postDataComponents);
        $targetScript = 'http://projects.knmi.nl/klimatologie/daggegevens/getdata_dag.cgi';

        return implode(' ', [$command, $postData, $targetScript]);
    }

    public function actionSubmit()
    {
        $query = new Query;
        $query->vars = 'T';
        $query->stns = '260';

        $timestamp = date('YmdHis');
        $outputFile = '/home/lennert/projects/knmi/output/' . $timestamp . '.csv';

        $client = new Client([
            'base_uri' => 'http://projects.knmi.nl/klimatologie/daggegevens/getdata_dag.cgi',
        ]);
        $response = $client->post(
            '', [
            'form_params' => [
                'stns' => '260',
                'vars' => 'T'
            ],
            'sink' => $outputFile,
        ]);
        var_dump('<pre>');
        var_dump($response);
        var_dump('</pre>');
        die();

        return $this->render('submit', compact('queryString'));
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
