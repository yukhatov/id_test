<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20.02.18
 * Time: 17:12
 */
namespace Classes;

use App\Application;

/**
 * Factory for building application instance
 *
 * Class ApplicationFactory
 * @package Classes
 */
class ApplicationFactory
{
    /**
     * @return Application
     */
    public function createApplication()
    {
        $application = new Application();

        $application->setValidator(new Validator());
        $application->setCrawler(new Crawler());
        $application->setReportManager(new ReportManager());

        return $application;
    }
}
