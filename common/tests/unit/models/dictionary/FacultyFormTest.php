<?php namespace common\tests\models\dictionary;

use common\fixtures\dictionary\FacultyFixture;
use common\forms\dictionary\FacultyForm;
use common\models\dictionary\Faculty;
use common\repositories\dictionary\FacultyRepository;
use common\services\dictionary\FacultyService;

class FacultyFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    public function testValidationForm()
    {
        $model = new FacultyForm();

        $model->full_name = "";
        $this->assertFalse($model->validate(['full_name']));

        $model->full_name = "Suka";
        $this->assertTrue($model->validate(['full_name']));
    }

    public function testNewSavingFaculty()
    {
        $form =  new FacultyForm();
        $form->full_name = "Suka";

        $repoFaculty = $this->makeEmpty(FacultyRepository::class);

        $facultyModel = Faculty::create($form->full_name);
        $this->returnSelf($facultyModel);

        $this->assertEquals('Suka', $facultyModel->full_name);
        $this->assertNull($repoFaculty->save($facultyModel));
    }

    public function testServiceSavingFaculty()
    {
        $repoFaculty = $this->makeEmpty(FacultyRepository::class);
        $serviceFaculty = new FacultyService($repoFaculty);

        $form =  new FacultyForm();
        $form->full_name = "Suka";

        $facultyModel = $serviceFaculty->create($form);
        $this->assertEquals('Suka', $facultyModel->full_name);
    }

    public function testServiceUpdateFaculty()
    {
        $repoFaculty = $this->makeEmpty(FacultyRepository::class);
        $serviceFaculty = new FacultyService($repoFaculty);
        $this->assertIsObject($serviceFaculty);

        $form =  new FacultyForm(new Faculty(["full_name" => "Suka"]));
        $this->assertIsObject($form);
        $this->assertEquals('Suka', $form->full_name);

        $form->full_name = "Suka Update";
        $this->assertEquals('Suka Update', $form->full_name);

        $facultyUpdate = $serviceFaculty->edit(1, $form);

        $this->assertNull($facultyUpdate);
    }

    public function testServiceRemoveFaculty()
    {
        $repoFaculty = $this->makeEmpty(FacultyRepository::class);

        $serviceFaculty = new FacultyService($repoFaculty);
        $this->assertIsObject($serviceFaculty);

        $facultyRemove = $serviceFaculty->remove(1);
        $this->assertEmpty($facultyRemove);
    }
}