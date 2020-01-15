<?php


namespace dod\services;

use common\auth\forms\SignupForm;
use common\auth\repositories\UserRepository;
use common\transactions\TransactionManager;
use dod\forms\SignupDodForm;
use dod\models\UserDod;
use dod\repositories\DateDodRepository;
use dod\repositories\UserDodRepository;
use olympic\forms\auth\ProfileCreateForm;
use olympic\repositories\auth\ProfileRepository;
use olympic\models\auth\Profiles;
use common\auth\models\User;
use Yii;


class DodRegisterUserService
{
    private $transaction;

    private $userRepository;
    private $profileRepository;
    private $dodRepository;
    private $userDodRepository;

    public function __construct(
        UserDodRepository $userDodRepository,
        DateDodRepository $dodRepository,
        UserRepository $userRepository,
        ProfileRepository $profileRepository,
        TransactionManager $transaction
    )
    {
        $this->userDodRepository = $userDodRepository;
        $this->dodRepository = $dodRepository;
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
        $this->transaction = $transaction;
    }

    public function signup(SignupDodForm $form): void
    {
        $this->transaction->wrap(function () use ($form) {

            $user = $this->newUser($form->user);
            $this->userRepository->save($user);

            $profile = $this->newProfile($form->profile, $user->id);
            $this->profileRepository->save($profile);

            $userDod = $this->newUserDod($form->dateDodId, $user->id);
            $this->userDodRepository->save($userDod);

            $this->sendEmail($user);
        });
    }

    public function newUser(SignupForm $form): User
    {
        $user = User::requestSignup($form);
        return $user;
    }

    public function newProfile(ProfileCreateForm $form, $user_id): Profiles
    {
        $profile = Profiles::create($form, $user_id);
        return $profile;
    }

    public function newUserDod($dod_id, $user_id): UserDod
    {
        $dateDod = $this->dodRepository->get($dod_id);
        $userDod = UserDod::create($dateDod->id, $user_id);
        return $userDod;
    }

    public function sendEmail(User $user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($user->email)
            ->setSubject('Активация аккаунта. ' . Yii::$app->name)
            ->send();
    }

}