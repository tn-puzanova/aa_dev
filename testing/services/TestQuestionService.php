<?php
namespace testing\services;

use common\transactions\TransactionManager;
use olympic\repositories\OlympicRepository;
use testing\forms\question\TestQuestionClozeForm;
use testing\forms\question\TestQuestionClozeUpdateForm;
use testing\forms\question\TestQuestionEditForm;
use testing\forms\question\TestQuestionForm;
use testing\forms\question\TestQuestionTypesFileForm;
use testing\forms\question\TestQuestionTypesForm;
use testing\helpers\TestQuestionHelper;
use testing\models\Answer;
use testing\models\AnswerCloze;
use testing\models\QuestionProposition;
use testing\models\TestQuestion;
use testing\repositories\AnswerClozeRepository;
use testing\repositories\AnswerRepository;
use testing\repositories\QuestionPropositionRepository;
use testing\repositories\TestQuestionGroupRepository;
use testing\repositories\TestQuestionRepository;
use yii\helpers\ArrayHelper;

class TestQuestionService
{
    private $repository;
    private $groupRepository;
    private $transaction;
    private $answerRepository;
    private $questionPropositionRepository;
    private $answerClozeRepository;
    private $olympicRepository;

    public function __construct(TestQuestionRepository $repository,
                                TestQuestionGroupRepository $groupRepository,
                                TransactionManager $transaction,
                                OlympicRepository $olympicRepository,
                                QuestionPropositionRepository $questionPropositionRepository,
                                AnswerRepository $answerRepository,
                                AnswerClozeRepository $answerClozeRepository)
    {
        $this->repository = $repository;
        $this->groupRepository = $groupRepository;
        $this->transaction = $transaction;
        $this->answerRepository = $answerRepository;
        $this->questionPropositionRepository = $questionPropositionRepository;
        $this->answerClozeRepository = $answerClozeRepository;
        $this->olympicRepository = $olympicRepository;
    }

    public function create(TestQuestionTypesForm $form, $olympic_id)
    {
        $olympic = $this->olympicRepository->get($olympic_id);
        $group_id = $this->isGroupQuestionId($form->question->group_id);
        $model = TestQuestion::create($form->question, $group_id, null, null, $olympic->id);
        $this->transaction->wrap(function () use ($model, $form) {
            $this->repository->save($model);
            $this->addAnswer($form, $model->type_id, $model->id);
        });
        return $model;
    }

    public function update(TestQuestionTypesForm $form)
    {
        $question = $this->repository->get($form->question->_question->id);
        $group_id = $this->isGroupQuestionId($form->question->group_id);

        $question->edit($form->question, $group_id, null);

        $this->transaction->wrap(function () use ($question, $form) {
            $this->repository->save($question);
            $this->updateAnswer($form, $question->type_id, $question->id);
        });
    }


    private function addAnswerSelect(TestQuestionTypesForm $form, $quest_id) {
    foreach ($form->answer as $index => $answer) {
        $answerObject = Answer::create($quest_id, $answer->name, $answer->is_correct, null);
        $this->answerRepository->save($answerObject);
    }
}

    private function updateAnswerSelect(TestQuestionTypesForm $form, $quest_id) {
        $deletedIDs = array_diff($form->oldIds, array_filter(ArrayHelper::map($form->answer, 'id', 'id')));
        if (!empty($deletedIDs)) {
            Answer::deleteAll(['id'=>$deletedIDs]);
        }
        foreach ($form->answer as $index => $answer) {
            if ($answer->id) {
                $answerModel = $this->answerRepository->get($answer->id);
                $answerModel->edit( $answer->name, $answer->is_correct, null);
            }else {
                $answerModel = Answer::create($quest_id, $answer->name, $answer->is_correct, null);
            }
            $this->answerRepository->save($answerModel);
        }
    }

    private function addAnswerSort(TestQuestionTypesForm $form, $quest_id) {
        foreach ($form->answer as $answer) {
            $answerObject = Answer::create($quest_id, $answer->name, 1, null);
            $this->answerRepository->save($answerObject);
        }
    }

    private function updateAnswerSort(TestQuestionTypesForm $form, $quest_id) {
        $deletedIDs = array_diff($form->oldIds, array_filter(ArrayHelper::map($form->answer, 'id', 'id')));
        if (!empty($deletedIDs)) {
            Answer::deleteAll(['id'=>$deletedIDs]);
        }
        foreach ($form->answer as $index => $answer) {
            if ($answer->id) {
                $answerModel = $this->answerRepository->get($answer->id);
                $answerModel->edit( $answer->name, 1, null);
            }else {
                $answerModel = Answer::create($quest_id, $answer->name, 1, null);
            }
            $this->answerRepository->save($answerModel);
        }
    }

    private function addAnswerMatching(TestQuestionTypesForm $form, $quest_id) {
        foreach ($form->answer as $answer) {
            $isCorrect = $answer->name ? 1 : 0;
            $answerObject = Answer::create($quest_id, $answer->name, $isCorrect, $answer->answer_match);
            $this->answerRepository->save($answerObject);
        }
    }

    private function updateAnswerMatching(TestQuestionTypesForm $form, $quest_id) {
        $deletedIDs = array_diff($form->oldIds, array_filter(ArrayHelper::map($form->answer, 'id', 'id')));
        if (!empty($deletedIDs)) {
            Answer::deleteAll(['id'=>$deletedIDs]);
        }
        foreach ($form->answer as $index => $answer) {
            $isCorrect = $answer->name ? 1 : 0;
            if ($answer->id) {
                $answerModel = $this->answerRepository->get($answer->id);
                $answerModel->edit($answer->name, $isCorrect, $answer->answer_match);
            }else {
                $answerModel = Answer::create($quest_id, $answer->name, $isCorrect, $answer->answer_match);
            }
            $this->answerRepository->save($answerModel);
        }
    }

    private function addAnswer(TestQuestionTypesForm $form, $type_id, $quest_id) {
        if ($type_id === TestQuestionHelper::TYPE_SELECT || $type_id ===  TestQuestionHelper::TYPE_SELECT_ONE) {
            $this->addAnswerSelect($form, $quest_id);
        } else if ($type_id === TestQuestionHelper::TYPE_ANSWER_SHORT) {
            $this->addAnswerSort($form, $quest_id);
        } else if ($type_id === TestQuestionHelper::TYPE_MATCHING) {
            $this->addAnswerMatching($form, $quest_id);
        }
    }

    private function updateAnswer(TestQuestionTypesForm $form, $type_id, $quest_id) {
        if ($type_id === TestQuestionHelper::TYPE_SELECT || $type_id ===  TestQuestionHelper::TYPE_SELECT_ONE) {
            $this->updateAnswerSelect($form, $quest_id);
        } else if ($type_id === TestQuestionHelper::TYPE_ANSWER_SHORT) {
            $this->updateAnswerSort($form, $quest_id);
        } else if ($type_id === TestQuestionHelper::TYPE_MATCHING) {
            $this->updateAnswerMatching($form, $quest_id);
        }
    }


    public function createQuestion(TestQuestionForm $form, $olympic_id)
    {
        $olympic = $this->olympicRepository->get($olympic_id);
        $group_id = $this->isGroupQuestionId($form->group_id);
        $model = TestQuestion::create($form, $group_id, null, null, $olympic->id);
        $this->repository->save($model);
        return $model;
    }

    public function createTypeFile(TestQuestionTypesFileForm $form, $olympic_id)
    {
        $olympic = $this->olympicRepository->get($olympic_id);
        $group_id = $this->isGroupQuestionId($form->question->group_id);
        $model = TestQuestion::create($form->question, $group_id, $form->file_type_id, null, $olympic->id);
        $this->repository->save($model);
        return $model;
    }

    public function updateQuestion(TestQuestionEditForm $form)
    {
        $question = $this->repository->get($form->_question->id);
        $group_id = $this->isGroupQuestionId($form->group_id);
        $question->edit($form, $group_id, null);
        $this->repository->save($question);
    }

    public function updateTypeFile(TestQuestionTypesFileForm $form)
    {
        $question = $this->repository->get($form->question->_question->id);
        $group_id = $this->isGroupQuestionId($form->question->group_id);
        $question->edit($form->question, $group_id, $form->file_type_id);
        $this->repository->save($question);
    }

    public function createTypeCloze(TestQuestionClozeForm $form, $olympic_id)
    {
        $olympic = $this->olympicRepository->get($olympic_id);
        $group_id = $this->isGroupQuestionId($form->question->group_id);
        $model = TestQuestion::create($form->question, $group_id, null, null, $olympic->id);
        $this->transaction->wrap(function () use ($model, $form) {
            $this->repository->save($model);
            if($form->questProp) {
                foreach ($form->questProp as $index => $questProp) {
                    $questPropModel = QuestionProposition::create($model->id, $questProp->name, $questProp->is_start, $questProp->type);
                    $this->questionPropositionRepository->save($questPropModel);
                    if($questProp->type) {
                        foreach ($form->answerCloze[$index] as $answerCloze) {
                            if ($answerCloze->name) {
                                $isCorrect = $questProp->type == TestQuestionHelper::CLOZE_TEXT ? 1 : $answerCloze->is_correct;
                                $answerClozeModel = AnswerCloze::create($questPropModel->id, $answerCloze->name, $isCorrect);
                                $this->answerClozeRepository->save($answerClozeModel);
                            }
                        }
                    }
                }
            }
        });
        return $model;
    }

    public function updateTypeCloze(TestQuestionClozeUpdateForm $form)
    {
        $question = $this->repository->get($form->question->_question->id);
        $group_id = $this->isGroupQuestionId($form->question->group_id);
        $question->edit($form->question, $group_id, null);

        $deletedQuestionPropIdsIDs = array_diff($form->oldQuestionPropIds, array_filter(ArrayHelper::map($form->questProp, 'id', 'id')));
        $deletedAnswerClozeIds = array_diff($form->oldAnswerClozeIds, $form->answerClozeIds);
        $this->transaction->wrap(function () use ($question, $form, $deletedQuestionPropIdsIDs, $deletedAnswerClozeIds) {
            $this->repository->save($question);
            if (!empty($deletedQuestionPropIdsIDs)) {
                QuestionProposition::deleteAll(['id'=>$deletedQuestionPropIdsIDs]);
            }
            if (!empty($deletedAnswerClozeIds)) {
                AnswerCloze::deleteAll(['id'=>$deletedAnswerClozeIds]);
            }
            if($form->questProp) {
                foreach ($form->questProp as $index => $questProp) {
                    if ($questProp->id) {
                        $questPropModel  = $this->questionPropositionRepository->get($questProp->id);
                        $questPropModel->edit($questProp->name, $questProp->is_start, $questProp->type);
                    }else {
                        $questPropModel = QuestionProposition::create($question->id, $questProp->name, $questProp->is_start, $questProp->type);
                    }
                    $this->questionPropositionRepository->save($questPropModel);
                    if($questProp->type) {
                        if (isset($form->answerCloze[$index]) && is_array($form->answerCloze[$index])) {
                            foreach ($form->answerCloze[$index] as $answerCloze) {
                                if ($answerCloze->name) {
                                    $isCorrect = $questProp->type == TestQuestionHelper::CLOZE_TEXT ? 1 : $answerCloze->is_correct;
                                    if ($answerCloze->id) {
                                        $answerClozeModel = $this->answerClozeRepository->get($answerCloze->id);
                                        $answerClozeModel->edit($answerCloze->name, $isCorrect);
                                    }else {
                                        $answerClozeModel = AnswerCloze::create($questPropModel->id, $answerCloze->name, $isCorrect);
                                    }
                                    $this->answerClozeRepository->save($answerClozeModel);
                                }
                            }
                        }
                    }
                }
            }
        });
    }

    private function isGroupQuestionId($group_id) {
        $group = $this->groupRepository->getIs($group_id);
        return $group->id ?? null;
    }

    public function remove($id)
    {
        $model = $this->repository->get($id);
        $this->repository->remove($model);
    }

}