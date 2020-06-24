<?php namespace LamaLama\LaravelBuckaroo\Acknowledgments;


interface AcknowledgmentInterface
{
    public function toArray() : array;

    public function parseBuckarooResponse() : void;
}