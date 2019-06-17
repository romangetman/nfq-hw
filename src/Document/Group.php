<?php

namespace App\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
/**
 * @MongoDB\Document(collection="groups")
 */
class Group
{
    /**
     * @MongoDB\Id
     */
    protected $id;



    /**
     * @MongoDB\Field(type="string")
     */
    protected $name;

    /**
     * @MongoDB\ReferenceMany(
     *     targetDocument="User",
     *     mappedBy="group"
     * )
     */
    protected $users = [];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Group
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Group
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }




    public function __toString()
    {
        return $this->getName();
    }

}
