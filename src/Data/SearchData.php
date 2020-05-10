<?php

namespace App\Data;
use Symfony\Component\Validator\Constraints as Assert;

class SearchData {

    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min = 4,
     *     minMessage = "Votre recherche doit comporter au minimum 4 caractères"
     * )
     */
    public $q = '';

    /**
     * @var null|integer
     */
    public $min;

    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|integer
     */
    public $room;

}