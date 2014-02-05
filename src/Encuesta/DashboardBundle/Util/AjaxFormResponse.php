<?php
namespace Encuesta\DashboardBundle\Util;

use Encuesta\DashboardBundle\Util\AjaxResponse;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;

class AjaxFormResponse extends AjaxResponse
{
    private $form;

    public function __construct()
    {
        parent::__construct();
    }

    public function response()
    {
        $errors_form = $this->getErrorsForm();

        $response = parent::response();
        $response['errors_form'] = $errors_form;

        return $response;
    }

    public function setForm(Form $form)
    {
        $this->form = $form;

        return $this;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function translateConstraint($message)
    {
        return $this->translator->trans($message);
    }

    public function getGlobalErrorsForm()
    {
        $errors = $this->form->getErrors();

        if(count($errors) > 0) {
            $this->setHttpCode(500);

            foreach($errors as $key => $error)
                $errors[$key] = $error->getMessage();
        }

        return $errors;
    }

    public function getErrorsForm()
    {
        return array(
            'global_errors' => $this->getGlobalErrorsForm(),
            'widget_errors' => $this->getWidgetsErrorsForm()
        );
    }

    public function getWidgetsErrorsForm()
    {
        $errors = array();
        foreach($this->form->all() as $name => $widget) {
            if($widget->hasErrors()) {
                $errors[$this->form->getName().'['.$name.']'] = $this->getWidgetErrors($widget);
            }
        }

        return $errors;
    }

    public function getSingleWidgetErrorsForm($name)
    {
        return $this->getWidgetErrors($this->form->get($name));
    }

    public function getWidgetErrors(Form $widget)
    {
        $widget_errors = array();
        foreach($widget->getErrors() as $error)
            $widget_errors[] = $this->translateConstraint($error->getMessage());

        if(count($widget_errors) > 0)
            $this->setHttpCode(500);

        return $widget_errors;
    }

}
