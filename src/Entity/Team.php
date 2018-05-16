<?php

namespace MIAProde\Entity;

class Team extends \MIABase\Entity\Base implements \Zend\InputFilter\InputFilterAwareInterface
{
    /**
     * @var string
     */
    public $title = null;

    /**
     * @var string
     */
    public $title_short = null;

    /**
     * @var string
     */
    public $photo = null;

    /**
     * @var int
     */
    public $country_id = null;
    /**
     *
     * @var boolean
     */
    protected $hasCreatedAt = false;
    /**
     *
     * @var boolean
     */
    protected $hasUpdatedAt = false;

    public function toArray()
    {
        $data = parent::toArray();
        $data['title'] = $this->title;
        $data['title_short'] = $this->title_short;
        $data['photo'] = $this->photo;
        $data['country_id'] = $this->country_id;
        return $data;
    }

    public function exchangeArray(array $data)
    {
        parent::exchangeArray($data);
        $this->title = (!empty($data['title'])) ? $data['title'] : '';
        $this->title_short = (!empty($data['title_short'])) ? $data['title_short'] : '';
        $this->photo = (!empty($data['photo'])) ? $data['photo'] : '';
        $this->country_id = (!empty($data['country_id'])) ? $data['country_id'] : 0;
    }

    public function exchangeObject($data)
    {
        parent::exchangeObject($data);
        $this->title = $data->title;
        $this->title_short = $data->title_short;
        $this->photo = $data->photo;
        $this->country_id = $data->country_id;
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }
                
        $inputFilter = new \Zend\InputFilter\InputFilter();
        $inputFilter->add([
                    'name' => 'title',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\StripTags::class],
                        ['name' => \Zend\Filter\StringTrim::class],
                    ],
                    'validators' => [
                        [
                            'name' => \Zend\Validator\StringLength::class,
                            'options' => [
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 100,
                            ],
                        ],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'title_short',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\StripTags::class],
                        ['name' => \Zend\Filter\StringTrim::class],
                    ],
                    'validators' => [
                        [
                            'name' => \Zend\Validator\StringLength::class,
                            'options' => [
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 100,
                            ],
                        ],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'photo',
                    'required' => false,
                ]);
        $inputFilter->add([
                    'name' => 'country_id',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }

    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
                    '%s does not allow injection of an alternate input filter',
                    __CLASS__
                ));
    }
}