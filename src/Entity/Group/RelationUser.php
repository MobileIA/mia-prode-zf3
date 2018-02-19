<?php

namespace MIAProde\Entity\Group;

class RelationUser extends \MIABase\Entity\Base implements \Zend\InputFilter\InputFilterAwareInterface
{
    const ROLE_GUEST = 0;
    const ROLE_ADMIN = 1;
    
    /**
     * @var int
     */
    public $group_id = null;

    /**
     * @var int
     */
    public $user_id = null;

    /**
     * @var string
     */
    public $username = null;

    /**
     * @var string
     */
    public $phone = null;
    
    /**
     * @var string
     */
    public $facebook_id = null;

    /**
     * @var int
     */
    public $role = null;
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
        $data['group_id'] = $this->group_id;
        $data['user_id'] = $this->user_id;
        $data['username'] = $this->username;
        $data['phone'] = $this->phone;
        $data['facebook_id'] = $this->facebook_id;
        $data['role'] = $this->role;
        return $data;
    }

    public function exchangeArray(array $data)
    {
        parent::exchangeArray($data);
        $this->group_id = (!empty($data['group_id'])) ? $data['group_id'] : 0;
        $this->user_id = (!empty($data['user_id'])) ? $data['user_id'] : 0;
        $this->username = (!empty($data['username'])) ? $data['username'] : '';
        $this->phone = (!empty($data['phone'])) ? $data['phone'] : '';
        $this->facebook_id = (!empty($data['facebook_id'])) ? $data['facebook_id'] : '';
        $this->role = (!empty($data['role'])) ? $data['role'] : 0;
    }

    public function exchangeObject($data)
    {
        parent::exchangeObject($data);
        $this->group_id = $data->group_id;
        $this->user_id = $data->user_id;
        $this->username = $data->username;
        $this->phone = $data->phone;
        $this->facebook_id = $data->facebook_id;
        $this->role = $data->role;
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }
                
        $inputFilter = new \Zend\InputFilter\InputFilter();
        $inputFilter->add([
                    'name' => 'group_id',
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
                    'name' => 'username',
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
                    'name' => 'phone',
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
                    'name' => 'role',
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