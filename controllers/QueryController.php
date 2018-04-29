<?php

namespace app\controllers;

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
        $model = new Query();

        return $this->render('create', ['model' => $model]);
    }
    
    /**
     * Displays query form page.
     *
     * @return string
     */
    public function actionSendQuery()
    {
        $model = new Query();
        if (isset(Yii::$app->request->post()['Query'])) {
            $input = Yii::$app->request->post()['Query'];
            $input['start'] = Custom::dateFormat($input['start']);
            $input['end'] = Custom::dateFormat($input['end']);
            $input['vars'] = ($input['vars']) ? implode(':', $input['vars']) : '';
            $input['stns'] = ($input['stns']) ? implode(':', $input['stns']) : '';
            $model->attributes = $input;
            dd($model);
            if ($model->validate()) {
                $model->store();

                return $this->actionIndex($model);
            }
        }

        return $this->actionCreate();
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
