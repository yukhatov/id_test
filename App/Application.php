<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20.02.18
 * Time: 16:46
 */
namespace App;

use Interfaces\ICrawable;
use Interfaces\ICrawler;
use Interfaces\IRunable;
use Interfaces\IValidator;
use Interfaces\IManager;

/**
 * Class Application
 * @package App
 */
class Application implements IRunable, ICrawable
{
    /**
     * @var IValidator
     */
    private $validator;

    /**
     * @var ICrawler
     */
    private $crawler;

    /**
     * @var IManager
     */
    private $reportManager;

    /**
     * @param IValidator $validator
     */
    public function setValidator(IValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param ICrawler $crawler
     */
    public function setCrawler(ICrawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @param IManager $reportManager
     */
    public function setReportManager(IManager $reportManager)
    {
        $this->reportManager = $reportManager;
    }

    /**
     * @param $url
     * @return array
     */
    public function crawl($url) {
        $tag = '<img';
        $startTime = microtime(true);
        $tagsCount = $this->crawler->countTags($this->crawler->getResource($url), $tag);
        $duration = microtime(true) - $startTime;

        return  ['url' => $url, 'count_of_tags' => $tagsCount, 'duration' => $duration];
    }

    /**
     * @param $url
     * @return string
     */
    public function run($url)
    {
        if (!$this->validator->isValid($url)) {
            return 'Website url is not valid!';
        }

        $reportData = $this->crawl($url);

        $this->reportManager->report($reportData);
    }
}