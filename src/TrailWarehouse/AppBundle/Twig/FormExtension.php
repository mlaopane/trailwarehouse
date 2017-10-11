<?php
namespace TrailWarehouse\AppBundle\Twig;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;

class FormExtension extends TWExtension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('showErrors', [$this, 'showErrors'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('form_addon', [$this, 'formAddon'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters()
    {
        return [
        ];
    }

    public function showErrors(Form $form)
    {
        return $this->twig->render('form/errors.html.twig', [
            'errors' => $form->getErrors(true, true)
        ]);
    }

    public function formAddon($add_on, $widget)
    {
        return $this->twig->render('form/input_group_addon.html.twig', [
            'add_on' => $add_on,
            'widget' => $widget,
        ]);
    }
}
