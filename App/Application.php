<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20.02.18
 * Time: 16:46
 */
namespace App;

use Classes\ReportManager;
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
     * Application constructor.
     * @param IValidator $validator
     * @param ICrawler $crawler
     * @param IManager $reportManager
     */
    public function __construct(IValidator $validator, ICrawler $crawler, IManager $reportManager)
    {
        $this->validator = $validator;
        $this->crawler = $crawler;
        $this->reportManager = $reportManager;
    }

    /**
     * @param $url
     * @return array
     */
    public function crawl($url) : array {
        $tag = '<img';
        $startTime = microtime(true);
        $tagsCount = $this->crawler->countTags($this->crawler->getResource($url), $tag);
        $duration = microtime(true) - $startTime;

        return  [ReportManager::$key_url => $url, ReportManager::$key_count_of_tags => $tagsCount, ReportManager::$key_duration => $duration];
    }

    /**
     * @param $url
     * @return string
     * 
     * @throws Exception if url is not valid.
     */
    public function run($url)
    {
        if (!$this->validator->isValid($url)) {
            throw new \Exception("Url validation error!");
        }
        
        $reportData = $this->crawl($url);

        $this->reportManager->report($reportData);

        return 'Success';
    }
}