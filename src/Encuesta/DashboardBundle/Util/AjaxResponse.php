<?php
namespace Encuesta\DashboardBundle\Util;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Translation\Translator;

class AjaxResponse
{
    protected $translator;
    protected $global_error;
    protected $errors;
    protected $http_code;
    protected $message;
    protected $message_types;
    protected $data_holder;

    public function __construct()
    {
        $this->global_error = null;
        $this->errors = array();
        $this->http_code = 200;
        $this->message_types = array(
            200 => 'success',
            500 => 'error',
            404 => 'info'
        );
        $this->data_holder = array();
    }

    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    public function hasErrors()
    {
        return $this->http_code == 500;
    }

    public function isSuccess()
    {
        return $this->http_code == 200;
    }

    public function setHttpCode($code)
    {
        $this->http_code = $code;
    }

    public function getHttpCode()
    {
        return $this->http_code;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setGlobalError($error)
    {
        $this->global_error = $error;
        $this->setMessage($error);
        $this->setHttpCode(500);
    }

    public function getGlobalError()
    {
        return $this->global_error;
    }

    public function setErrors(array $errors)
    {
        $this->errors = $errors;
        $this->setHttpCode(500);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setOneError($key, $error)
    {
        $this->errors[$key] = $error;
        $this->setHttpCode(500);
    }

    public function getOneError($key)
    {
        return $this->errors[$key];
    }

    public function setDataHolder($holder)
    {
        $this->data_holder = $holder;
    }

    public function getDataHolder()
    {
        return $this->data_holder;
    }

    public function response()
    {
        return array(
            'http_code' => $this->getHttpCode(),
            'has_error' => $this->hasErrors(),
            'is_success' => $this->isSuccess(),
            'message' => $this->getMessage(),
            'message_type' => $this->message_types[$this->getHttpCode()],
            'errors_holder' => array(
                'global_error' => $this->getGlobalError(),
                'errors' => $this->getErrors()
            ),
            'data_holder' => $this->getDataHolder()
        );
    }
}
?>