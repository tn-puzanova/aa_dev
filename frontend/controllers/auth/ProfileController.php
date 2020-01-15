<?php


namespace frontend\controllers\auth;

use olympic\forms\auth\ProfileEditForm;
use olympic\services\auth\ProfileService;
use yii\web\Controller;
use Yii;

class ProfileController extends Controller
{
    private $service;

    public function __construct($id, $module, ProfileService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function actionProfile()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new ProfileEditForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($form);
                Yii::$app->session->setFlash('success', 'Успешно обновлен.');
                return $this->redirect(['profile']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('profile', [
            'model' => $form,
        ]);
    }
}