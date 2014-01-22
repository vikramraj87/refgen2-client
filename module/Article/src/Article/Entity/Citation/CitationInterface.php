<?php
namespace Article\Entity\Citation;

use Article\Entity\Article;

interface CitationInterface {
    public function getCitation(Article $article);
} 