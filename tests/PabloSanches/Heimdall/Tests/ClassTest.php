<?php

class SomeClass
{
    /**
     * @type string
     * @maxlength 13
     * @minlength 5
     * @required
     * @message Campo nome é obrigatório.
     */
    public $name;

    /**
     * @type email
     * @required
     * @message Campo e-mail é obrigatório.
     */
    public $email;

    /**
     * @type chosen[M,F]
     * @required
     */
    public $gender;

    /**
     * @type phone
     * @required
     */
    public $phone;

    /**
     * @type date
     */
    public $birthday;

    /**
     * @type number
     */
    public $age;

    /**
     * @type cpf
     */
    public $cpf;

    /**
     * @type cnpj
     */
    public $cnpj;

    /**
     * @type zipcode
     */
    public $cep;
}