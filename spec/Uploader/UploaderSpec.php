<?php

namespace spec\SpeechKit\Uploader;

use GuzzleHttp\Psr7\Uri;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SpeechKit\Client\ClientInterface;
use SpeechKit\Speech\SpeechStreamInterface;
use SpeechKit\Uploader\UploaderInterface;
use SpeechKit\Uploader\UrlGenerator;

class UploaderSpec extends ObjectBehavior
{
    public function let(UrlGenerator $generator, ClientInterface $client)
    {
        $this->beConstructedWith($generator, $client);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('SpeechKit\Uploader\Uploader');
    }

    public function it_is_uploader()
    {
        $this->shouldImplement('SpeechKit\Uploader\UploaderInterface');
    }

    public function it_uploads_speech_to_url_from_generator(UrlGenerator $generator, ClientInterface $client, SpeechStreamInterface $speech, ResponseInterface $response, Uri $generatedUri)
    {
        $generator->generate($speech)->willReturn($generatedUri);
        $client->upload(Argument::type('Psr\Http\Message\RequestInterface'))->willReturn($response);

        $this->upload($speech)->shouldReturn($response);
        $client->upload(Argument::type('Psr\Http\Message\RequestInterface'))->shouldHaveBeenCalled();
        $client->upload(Argument::which('getUri', $generatedUri->getWrappedObject()))->shouldHaveBeenCalled();
    }

    public function it_takes_content_type_from_speech(UrlGenerator $generator, ClientInterface $client, SpeechStreamInterface $speech, ResponseInterface $response, Uri $generatedUri)
    {
        $speech->getContentType()->willReturn('test/test');
        $generator->generate($speech)->willReturn($generatedUri);
        $client->upload(Argument::type('Psr\Http\Message\RequestInterface'))->willReturn($response);

        $this->upload($speech)->shouldReturn($response);
        $client->upload(Argument::type('Psr\Http\Message\RequestInterface'))->shouldHaveBeenCalled();
        $client->upload(Argument::which('getHeaders', ['Content-Type' => ['test/test']]))->shouldHaveBeenCalled();
    }

    public function it_uploads_speech(UrlGenerator $generator, ClientInterface $client, SpeechStreamInterface $speech, ResponseInterface $response, Uri $generatedUri)
    {
        $generator->generate($speech)->willReturn($generatedUri);
        $client->upload(Argument::type('Psr\Http\Message\RequestInterface'))->willReturn($response);

        $this->upload($speech)->shouldReturn($response);
        $client->upload(Argument::type('Psr\Http\Message\RequestInterface'))->shouldHaveBeenCalled();
        $client->upload(Argument::which('getBody', $speech->getWrappedObject()))->shouldHaveBeenCalled();
    }
}
