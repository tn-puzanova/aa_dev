<?php


namespace frontend\controllers;

use common\auth\readRepositories\UserSchoolReadRepository;
use dictionary\readRepositories\DictSchoolsReadRepository;
use frontend\components\UserNoEmail;
use olympic\forms\auth\SchooLUserCreateForm;
use olympic\services\UserSchoolService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class SchoolsController extends Controller
{
    private $repository;
    private $userSchoolReadRepository;
    private $service;

    public function __construct($id,  $module, DictSchoolsReadRepository $repository,
                                UserSchoolReadRepository $userSchoolReadRepository,
                                UserSchoolService $service,
                                $config = [])
    {
        $this->repository = $repository;
        $this->userSchoolReadRepository = $userSchoolReadRepository;
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function actionAll($country_id, $region_id = null) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['result' => $this->repository->getAllSchools($region_id, $country_id)];

    }

    public function beforeAction($action)
    {
        return (new UserNoEmail())->redirect();
    }


    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $form = new SchooLUserCreateForm();

        if (is_null($form->country_id)) {
            Yii::$app->session->setFlash('warning', 'Чтобы добавить Вашу учебную оргнизацию, необхдимо заполнить профиль.');
            return $this->redirect(['auth/profile/profile']);
        }
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->signup($form);
                $this->redirect('index');
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        $dataProvider =  $this->userSchoolReadRepository->getUserSchoolsAll(Yii::$app->user->id);

        return $this->render('index',
            ['dataProvider' => $dataProvider,
            'model' => $form]);

    }

    /*
      * @param $id
      * @return mixed
      * @throws NotFoundHttpException
    */

    public function actionUpdate($id) {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = $this->find($id);
        $form = new SchooLUserCreateForm($model);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->update($model->id,$form);
                return $this->redirect('index');
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        return $this->render('update',
            ['model' => $form]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->service->remove($id, Yii::$app->user->id);
            Yii::$app->session->setFlash('success', 'Успешно удалена запись');
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(Yii::$app->request->referrer);
    }


    /*
      * @param $id
      * @return mixed
      * @throws NotFoundHttpException
    */
    protected function find($id)
    {
        if (($model = $this->userSchoolReadRepository->getUserSchool($id, Yii::$app->user->id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}