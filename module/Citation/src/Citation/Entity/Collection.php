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
    protected $name;

    /** @var DateTime */
    protected $createdAt = null;

    /** @var DateTime */
    protected $updatedAt = null;

    /**
     * Constructor
     *
     * @param string $id
     * @param string $userId
     */
    public function __construct($id, $userId)
    {
        $this->id     = $id;
        $this->userId = $userId;
    }

    /**
     * @param \Article\Entity\Article[] $articles
     */
    public function setArticles(array $articles)
    {
        if(!empty($articles)) {
            $tmp = array();
            foreach($articles as $article) {
                $tmp[$article->getId()] = $article;
            }
            $this->setData($tmp);
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

} 