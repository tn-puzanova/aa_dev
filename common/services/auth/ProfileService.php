<?php


namespace common\services\auth;


use common\forms\auth\ProfileForm;
use common\models\auth\Profiles;
use common\readRepositories\UserReadRepository;
use common\repositories\ProfileRepository;

class ProfileService
{
    private $user;
    private $profile;

    public function __construct(ProfileRepository $profile, UserReadRepository $user)
    {
        $this->profile = $profile;
        $this->user = $user;
    }

    public function createOrEdit(ProfileForm $form)
    {

        if (!$this->profile->getUserId()) {
            $profile = $this->create($form);
        } else {
            $profile = $this->edit($form);
        }
        $this->profile->save($profile);
    }

    public function create(ProfileForm $form)
    {
        $profile = Profiles::create($form->last_name,
            $form->first_name, $form->patronymic,
            $form->phone, $form->country_id, $form->region_id);
        return $profile;
    }

    public function edit(ProfileForm $form)
    {
        $profile = $this->profile->getUserId();
        $profile->edit($form->last_name,
            $form->first_name, $form->patronymic,
            $form->phone, $form->country_id, $form->region_id);
        return $profile;
    }

}