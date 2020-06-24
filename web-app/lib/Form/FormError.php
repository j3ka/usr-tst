<?php

namespace Lib\Form;

class FormError
{
    /**
     * @var string
     */
    private string $message;

    /**
     * FormError constructor.
     * @param string $msg
     */
    public function __construct(string $msg)
    {
        $this->message = $msg;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}