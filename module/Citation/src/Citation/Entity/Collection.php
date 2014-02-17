<?php
namespace Citation\Entity;

use Article\Entity\Article;
use \DateTime;
use Common\Entity\OrderedList;

class Collection extends OrderedList
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

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param \Article\Entity\Article[] $articles
     * @throws \InvalidArgumentException
     */
    public function setArticles(array $articles)
    {
        if(!empty($articles)) {
            $tmp = array();
            foreach($articles as $article) {
                if(!$article instanceof Article) {
                    throw new \InvalidArgumentException('articles should be array of type Article\Entity\Article');
                }
                $tmp[$article->getId()] = $article;
            }
            $this->setData($tmp);
        } else {
            $this->setData(array());
        }
    }

    /**
     * @return \Article\Entity\Article[]
     */
    public function getArticles()
    {
        return $this->getData();
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        if(is_string($createdAt)) {
            $createdAt = new DateTime($createdAt);
        }
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
     * @param string $updatedAt|null
     */
    public function setUpdatedAt($updatedAt)
    {
        if(is_string($updatedAt)) {
            $updatedAt = new DateTime($updatedAt);
        }
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
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
        parent::offsetSet($offset, $value);
    }
}