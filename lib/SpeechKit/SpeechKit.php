<?php
/**
 * Created by Evgeny Soynov <saboteur@saboteur.me> .
 */

namespace SpeechKit;

use SpeechKit\Exception\NoUploaderSetException;
use SpeechKit\SpeechContent\SpeechFactory;
use SpeechKit\SpeechContent\SpeechContentInterface;
use SpeechKit\Uploader\UploaderInterface;

class SpeechKit
{
    protected $key;
    /** @var UploaderInterface */
    protected $uploader;
    protected $responseParser;
    protected $response;
    protected $speech;

    protected $defaults = [
        'topic' => UploaderInterface::TOPIC_GENERAL,
        'lang' =>  UploaderInterface::LANG_RU
    ];

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    public function setUploader(UploaderInterface $uploader)
    {
        $uploader->options()->setKey($this->key);

        $this->uploader = $uploader;

        return $this;
    }

    public function setResponseParser($parser)
    {
        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function recognize($data)
    {
        if(empty($this->uploader)) {
            throw new NoUploaderSetException('No uploader have been set to handle upload');
        }

        if(empty($this->responseParser)) {

        }

        if(!$data instanceof SpeechContentInterface) {
            $data = SpeechFactory::fromData($data);
        }

        $this->response = $response = $this->uploader->upload($data);

        return $response;
    }
} 