<?php
namespace Article\Entity\Citation;

use Article\Entity\Article;

class Vancouver implements CitationInterface
{
    public function getCitation(Article $article)
    {
        $citationString = "";

        $authors = $article->getAuthors();
        if(!empty($authors)) {
            if(count($authors) > 6) {
                $authors = array_slice($authors, 0, 6);
                $authors[] = "et al";
            }
            $citationString .= implode(", ", $authors) . ". ";
        }

        $citationString .= $article->getTitle() . " ";

        $journalAbbr = preg_replace("/\./", "", $article->getJournalAbbr());
        $citationString .= $journalAbbr . ". ";

        if($article->getPubStatus() === Article::PUBLISHED) {
            $citationString .= $article->getYear();

            $month = $article->getMonth();
            if(!empty($month)) {
                $citationString .= " " . $month;
            }

            $volume = $article->getVolume();
            $issue  = $article->getIssue();
            if(!empty($volume) || !empty($issue)) {
                $citationString .= ";";
            }
            if(!empty($volume)) {
                $citationString .= $volume;
            }
            if(!empty($issue)) {
                $citationString .= sprintf("(%s)", $issue);
            }

            $pages = $article->getPages();
            if(!empty($pages)) {
                $citationString .= ":" . $pages;
            }
        } else {
            $citationString .= "Epub ";
            $citationString .= $article->getYear();
            $month = $article->getMonth();
            if(!empty($month)) {
                $citationString .= " " . $month;
                $day = $article->getDay();
                if(!empty($day)) {
                    $citationString .= " " . $day;
                }
            }
        }
        return $citationString;
    }
} 