<?php

namespace Citation\Entity;

use Article\Entity\Article;
use Common\Entity\OrderedList;
use \DateTime;

class ActiveCollection extends OrderedList
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $userId;

    /** @var string */
    protected $name = '';

    /** @var DateTime */
    protected $createdAt = null;

    /** @var DateTime */
    protected $updatedAt = null;

    /** @var bool */
    protected $changed = false;

    public function __construct($data = null)
    {
        if($data instanceof Collection) {
            $this->id = $data->getId();
            $this->userId = $data->getUserId();
            $this->name   = $data->getName();
            $this->setCreatedAt($data->getCreatedAt());
            $this->setUpdatedAt($data->getUpdatedAt());
            $this->setCitations($data->getArticles());
        }
    }

    /**
     * @param string|DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        if(empty($createdAt)) {
            $createdAt = null;
        }
        if(is_string($createdAt)) {
            $createdAt = new DateTime($createdAt);
        }
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        if(empty($updatedAt)) {
            $updatedAt = null;
        }
        if(is_string($updatedAt)) {
            $updatedAt = new DateTime($updatedAt);
        }
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param \Article\Entity\Article[] $articles
     * @throws \InvalidArgumentException
     */
    public function setCitations(array $articles)
    {
        if(!empty($articles)) {
            $tmp = array();
            foreach($articles as $article) {
                if(!$article instanceof Article) {
                    throw new \InvalidArgumentException('articles should be array of type Article\Entity\Article');
                }
                $tmp[$article->getId()] = $article->getCitation();
            }
            $this->setData($tmp);
        } else {
            $this->setData(array());
        }
    }

    /**
     * @return Article[]
     */
    public function getCitations()
    {
        return $this->getData();
    }

    /**
     * @param string $offset
     * @param Article $value
     * @throws \InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        if(!$value instanceof Article) {
            throw new \InvalidArgumentException('value must be of type Article\Entity\Article');
        }
        $value = $value->getCitation();
        parent::offsetSet($offset, $value);
        $this->changed = true;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        parent::offsetUnset($offset);
        $this->changed = true;
    }

    /**
     * @return bool
     */
    public function isChanged()
    {
        return $this->changed;
    }

    public function resetChanged()
    {
        $this->changed = false;
    }
} 