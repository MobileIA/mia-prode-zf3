<?php

namespace MIAProde\Entity;

class Group extends \MIABase\Entity\Base implements \Zend\InputFilter\InputFilterAwareInterface
{
    /**
     * @var int
     */
    public $tournament_id = null;

    /**
     * @var int
     */
    public $user_id = null;

    /**
     * @var string
     */
    public $title = null;

    /**
     * @var string
     */
    public $start_date = null;

    /**
     * @var int
     */
    public $is_closed = 0;

    public function toArray()
    {
        $data = parent::toArray();
        $data['tournament_id'] = $this->tournament_id;
        $data['user_id'] = $this->user_id;
        $data['title'] = $this->title;
        $data['start_date'] = $this->start_date;
        $data['is_closed'] = $this->is_closed;
        return $data;
    }

    public function exchangeArray(array $data)
    {
        parent::exchangeArray($data);
        $this->tournament_id = (!empty($data['tournament_id'])) ? $data['tournament_id'] : 0;
        $this->user_id = (!empty($data['user_id'])) ? $data['user_id'] : 0;
        $this->is_closed = (!empty($data['is_closed'])) ? $data['is_closed'] : 0;
        $this->title = (!empty($data['title'])) ? $data['title'] : '';
        $this->start_date = (!empty($data['start_date'])) ? $data['start_date'] : '';
    }

    public function exchangeObject($data)
    {
        parent::exchangeObject($data);
        $this->tournament_id = $data->tournament_id;
        $this->user_id = $data->user_id;
        $this->title = $data->title;
        $this->start_date = $data->start_date;
        $this->is_closed = $data->is_closed;
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }
                
        $inputFilter = new \Zend\InputFilter\InputFilter();
        $inputFilter->add([
                    'name' => 'tournament_id',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'user_id',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
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
                    'name' => 'start_date',
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