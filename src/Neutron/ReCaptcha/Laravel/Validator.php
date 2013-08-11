<?php

namespace Neutron\ReCaptcha\Laravel;

use Facade as ReCaptcha;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class RecaptchaValidator extends Validator
{

    protected $request;

    /**
     * Create a new Validator instance.
     *
     * @param  \Symfony\Component\HttpFoundation\Request           $request
     * @param  \Symfony\Component\Translation\TranslatorInterface  $translator
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @return void
     */
    public function __construct(Request $request, TranslatorInterface $translator, $data, $rules, $messages)
    {
        $this->request = $request;

        parent::__construct($translator, $data, $rules, $messages);
    }

    /**
     * Validate that the recaptcha was correctly typed
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  mixed   $parameters
     * @return bool
     */
    public function validateRecaptcha($attribute, $value, $parameters = array())
    {
        $challenge = reset($parameters) ?: 'recaptcha_challenge_field';
        $response = ReCaptcha::checkAnswer($this->request->getClientIp(), $this->data[$challenge], $value);

        return $response->isValid();
    }

}