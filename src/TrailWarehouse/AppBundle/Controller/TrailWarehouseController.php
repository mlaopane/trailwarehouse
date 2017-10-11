<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;

abstract class TrailWarehouseController extends Controller
{
    /**
     * @var string $viewDir
     */
    private $viewDir;

    public function __construct()
    {
        $search = [__NAMESPACE__, 'Controller'];
        $this->viewDir = str_replace($search, '', static::class);
    }

    /**
     * @param string $templateName
     * @param array $data
     *
     * @return Response
     */
    protected function renderTwig(string $templateName, array $data): Response
    {
        return $this->render(
            "TrailWarehouseAppBundle:{$this->viewDir}:{$templateName}.html.twig",
            $data
        );
    }

    /**
     * @param string $formName
     * @param Form $form
     *
     * @return Response
     */
    protected function renderForm(string $formName, Form $form): Response
    {
        return $this->render(
            'TrailWarehouseAppBundle:User:'.$formName.'.html.twig', [
                'form' => $form,
                $formName.'_form' => $form->createView(),
                'errors' => $form->getErrors(true, true),
            ]
        );
    }

    protected function getViewDir(): string
    {
        return $this->viewDir;
    }
}