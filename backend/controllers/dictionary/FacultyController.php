<?php
namespace backend\controllers\dictionary;

use backend\forms\dictionary\FacultySearch;
use common\forms\dictionary\FacultyForm;
use common\models\dictionary\Faculty;
use common\services\dictionary\FacultyService;
use yii\web\Controller;
use Yii;
use yii\filters\VerbFilter;

class FacultyController extends Controller
{
    private $service;

    public function __construct($id, $module, FacultyService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FacultySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'faculty' => $this->findModel($id),
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new FacultyForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $faculty = $this->service->create($form);
                return $this->redirect(['view', 'id' => $faculty->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $faculty = $this->findModel($id);

        $form = new FacultyForm($faculty);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($faculty->id, $form);
                return $this->redirect(['view', 'id' => $faculty->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'faculty' => $faculty,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    protected function findModel($id): Faculty
    {
        if (($model = Faculty::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }


}