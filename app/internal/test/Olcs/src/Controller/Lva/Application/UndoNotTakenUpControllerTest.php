<?php

/**
 * Undo Not Taken Up Controller Test
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
namespace OlcsTest\Controller\Lva\Application;

use Mockery as m;
use OlcsTest\Controller\Lva\AbstractLvaControllerTestCase;

/**
 * Undo Not Taken Up Controller Test
 *
 * @author Dan Eggleston <dan@stolenegg.com>
 */
class UndoNotTakenUpControllerTest extends AbstractLvaControllerTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->mockController('\Olcs\Controller\Lva\Application\UndoNotTakenUpController');
    }

    public function testIndexActionGet()
    {
        $id = 69;
        $this->sut->shouldReceive('params')->with('application')->andReturn($id);

        $mockForm = $this->mockUndoNtuForm();

        $this->mockRender();

        $view = $this->sut->indexAction();

        $this->assertEquals('internal-application-undo-ntu-title', $view->getVariable('title'));

        $this->assertSame($mockForm, $view->getVariable('form'));
    }

    public function testIndexActionWithPostConfirm()
    {
        $id = 69;
        $licenceId = 100;

        $this->sut->shouldReceive('params')->with('application')->andReturn($id);

        $mockForm = $this->mockUndoNtuForm();

        $postData = [
            'form-actions' => [
                'submit' => ''
            ],
        ];
        $this->setPost($postData);

        $mockForm
            ->shouldReceive('setData')
                ->with($postData)
                ->once()
                ->andReturnSelf()
            ->shouldReceive('getData')
                ->andReturn($postData)
            ->shouldReceive('isValid')
                ->once()
                ->andReturn(true);

        $this->mockService('Processing\Application', 'processUndoNotTakenUpApplication')
            ->with($id)
            ->once();

        $this->mockService('Helper\Translation', 'translateReplace')
            ->with('application-undo-ntu-successfully', [$id])
            ->once()
            ->andReturn('SUCCESS MESSAGE');

        $this->mockService('Helper\FlashMessenger', 'addSuccessMessage')
            ->with('SUCCESS MESSAGE')
            ->once();

        $redirect = m::mock();
        $this->sut->shouldReceive('redirect->toRouteAjax')
            ->with('lva-application/overview', ['application' => $id])
            ->andReturn($redirect);

        $this->assertSame($redirect, $this->sut->indexAction());
    }

    protected function mockUndoNtuForm()
    {
        $mockForm = $this->createMockForm('GenericConfirmation');
        $mockForm->shouldReceive('get->get->setValue')->with('internal-application-undo-ntu-confirm');
        $this->getMockFormHelper()->shouldReceive('setFormActionFromRequest')
            ->with($mockForm, $this->request);

        return $mockForm;
    }
}
