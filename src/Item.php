<?php
declare(strict_types=1);

namespace Votemike\Archive;

use stdClass;

class Item
{
    /** @var null|string */
    public $avg_rating;

    /** @var null|string */
    public $backup_location;

    /** @var null|string */
    public $btih;

    /** @var null */
    public $call_number;

    /** @var string[] */
    public $collection;

    /** @var null|string */
    public $contributor;

    /** @var null|string */
    public $coverage;

    /**
     * From Metadata
     * @var null|int
     */
    public $created;

    /** @var null|string|string[] */
    public $creator;

    /**
     * From Metadata
     * @var null|string
     */
    public $d1;

    /**
     * From Metadata
     * @var null|string
     */
    public $d2;

    /** @var null|string */
    public $date;

    /** @var null|string|string[] */
    public $description;

    /**
     * From Metadata
     * @var null|string
     */
    public $dir;

    /** @var int */
    public $downloads;

//    public $external-identifier; //@TODO

    /**
     * From Metadata
     * @var null|stdClass[]
     */
    public $files;

    /**
     * From Metadata
     * @var null|int
     */
    public $files_count;

    /** @var null */
    public $foldoutcount;

    /** @var string[] */
    public $format;

    /** @var null */
    public $headerImage;

    /** @var string */
    public $identifier;

    /**
     * From Metadata
     * @var null|int
     */
    public $item_size;

    /** @var null|string */
    public $language;

    /** @var null|string */
    public $licenseurl;

    /** @var null|string */
    public $mediatype;

    /** @var null */
    public $members;

    /** @var null|MetaData */
    public $metadata;

    /** @var int */
    public $month;

    /** @var null|int */
    public $num_reviews;

    /** @var string[] */
    public $oai_updatedate;

    /** @var null|string */
    public $publicdate;

    /** @var null|string */
    public $publisher;

//    public $related-external-id; //@TODO

    /** @var null|string */
    public $reviewdate;

    /**
     * From Metadata
     * @var null|stdClass[]
     */
    public $reviews;

    /** @var null|string */
    public $rights;

    /** @var null */
    public $scanningcentre;

    /**
     * From Metadata
     * @var null|string
     */
    public $server;

    /** @var null|string */
    public $source;

    /** @var null|string|string[] */
    public $stripped_tags;

    /** @var null|string|string[] */
    public $subject;

    /** @var string|string[] */
    public $title;

    /** @var null|string */
    public $type;

    /**
     * From Metadata
     * @var null|int
     */
    public $uniq;

    /**
     * From Metadata
     * @var null|int
     */
    public $updated;

    /** @var null */
    public $volume;

    /** @var null|int */
    public $week;

    /**
     * From Metadata
     * @var null|string[]
     */
    public $workable_servers;

    /** @var null|string|string[] */
    public $year;
}
