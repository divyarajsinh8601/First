<?php
namespace HelenOfTroy\Webinar\Model;

interface WebinarInterface
{
    /**
     * GET for PreWebinarsCollection
     *
     * @return object
     */
    public function getPreWebinarsCollection():object;

    /**
     * GET for PostWebinarsCollection
     *
     * @return object
     */
    public function getPostWebinarsCollection():object;
}
