<?php
namespace Article\Entity;

use Article\Entity\Citation\CitationInterface;
use \RuntimeException;
class Article {
    const PUBLISHED      = 1;
    const AHEAD_OF_PRINT = 2;

    /** @var string id of the article either pmid or similar to that */
    protected $id;

    /** @var string volume of the journal */
    protected $volume;

    /** @var string issune number of the journal */
    protected $issue;

    /** @var string year of publication */
    protected $year;

    /** @var string month of publication */
    protected $month;

    /** @var string day of publication */
    protected $day;

    /** @var string page number range of the article */
    protected $pages;

    /** @var string unique number of the journal */
    protected $issn;

    /** @var string title of the journal */
    protected $journal;

    /** @var string standard abbreviated form of the journal */
    protected $journalAbbr;

    /** @var string title of the article */
    protected $title;

    /** @var array abstract in array with heading as key and para as value */
    protected $abstract;

    /** @var string affiliation */
    protected $affiliation;

    /** @var array authors as array */
    protected $authors;

    /** @var string various ids assigned by indexing systems */
    protected $articleId;

    /** @var array keywords as array */
    protected $keywords;

    /** @var int indicating publication status as defined by constants of the class */
    protected $pubStatus;

    /** @var CitationInterface */
    protected $citationGenerator = null;

    /**
     * @param array $abstract
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    }

    /**
     * @return array
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * @param string $affiliation
     */
    public function setAffiliation($affiliation)
    {
        $this->affliation = $affiliation;
    }

    /**
     * @return string
     */
    public function getAffiliation()
    {
        return $this->affiliation;
    }

    /**
     * @param string $articleId
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;
    }

    /**
     * @return string
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @param array $authors
     */
    public function setAuthors($authors)
    {
        $this->authors = $authors;
    }

    /**
     * @return array
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * @param string $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return string
     */
    public function getDay()
    {
        return $this->day;
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
     * @param string $issn
     */
    public function setIssn($issn)
    {
        $this->issn = $issn;
    }

    /**
     * @return string
     */
    public function getIssn()
    {
        return $this->issn;
    }

    /**
     * @param string $issue
     */
    public function setIssue($issue)
    {
        $this->issue = $issue;
    }

    /**
     * @return string
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * @param string $journal
     */
    public function setJournal($journal)
    {
        $this->journal = $journal;
    }

    /**
     * @return string
     */
    public function getJournal()
    {
        return $this->journal;
    }

    /**
     * @param string $journalAbbr
     */
    public function setJournalAbbr($journalAbbr)
    {
        $this->journalAbbr = $journalAbbr;
    }

    /**
     * @return string
     */
    public function getJournalAbbr()
    {
        return $this->journalAbbr;
    }

    /**
     * @param array $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * @return array
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param string $month
     */
    public function setMonth($month)
    {
        $this->month = $month;
    }

    /**
     * @return string
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param string $pages
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    }

    /**
     * @return string
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param int $pubStatus
     */
    public function setPubStatus($pubStatus)
    {
        if(is_string($pubStatus)) {
            switch($pubStatus) {
                case "ppublish":
                case "epublish":
                    $pubStatus = self::PUBLISHED;
                    break;
                case "aheadofprint":
                    $pubStatus = self::AHEAD_OF_PRINT;
                    break;
            }
        }
        $this->pubStatus = $pubStatus;
    }

    /**
     * @return int
     */
    public function getPubStatus()
    {
        return $this->pubStatus;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $volume
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;
    }

    /**
     * @return string
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param string $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    public function setCitationGenerator(Citation\CitationInterface $generator)
    {
        $this->citationGenerator = $generator;
    }

    public function getCitation()
    {
        if($this->citationGenerator === null) {
            throw new RuntimeException('Citation generator not injected into article');
        }
        return $this->citationGenerator->getCitation($this);
    }

    public function toArray()
    {
        return array(
            'id'           => (int) $this->getId(),
            'volume'       =>       $this->getVolume(),
            'issue'        =>       $this->getIssue(),
            'year'         =>       $this->getYear(),
            'month'        =>       $this->getMonth(),
            'day'          =>       $this->getDay(),
            'pages'        =>       $this->getPages(),
            'issn'         =>       $this->getIssn(),
            'journal'      =>       $this->getJournal(),
            'journal_abbr' =>       $this->getJournalAbbr(),
            'title'        =>       $this->getTitle(),
            'abstract'     =>       $this->getAbstract(),
            'affiliation'  =>       $this->getAffiliation(),
            'authors'      =>       $this->getAuthors(),
            'article_id'   =>       $this->getArticleId(),
            'keywords'     =>       $this->getKeywords(),
            'pub_status'   =>       $this->getPubStatus()
        );
    }
}