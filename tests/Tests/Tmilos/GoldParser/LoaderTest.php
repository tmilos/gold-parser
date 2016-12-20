<?php

namespace Tests\Tmilos\GoldParser;

use Tmilos\GoldParser\LalrParser;
use Tmilos\GoldParser\Loader;
use Tmilos\GoldParser\Rule\Rule;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function getFilenames()
    {
        return [
            ['/sample01.json'],
            ['/sample02.json'],
            ['/sample03.json'],
            ['/sample04.json'],
        ];
    }

    public function _test_loading()
    {
        $loader = Loader::fromFile(__DIR__ . '/JSON.cgt');
        $rules = array_map(function (Rule $r) {
            return (string) $r;
        }, $loader->getRules()->all());

        $this->assertEquals([
            '<Json> ::= <Object>',
            '<Json> ::= <Array>',
            '<Object> ::= { }',
            '<Object> ::= { <Members> }',
            '<Members> ::= <Pair>',
            '<Members> ::= <Pair> , <Members>',
            '<Pair> ::= String : <Value>',
            '<Array> ::= [ ]',
            '<Array> ::= [ <Elements> ]',
            '<Elements> ::= <Value>',
            '<Elements> ::= <Value> , <Elements>',
            '<Value> ::= String',
            '<Value> ::= Number',
            '<Value> ::= <Object>',
            '<Value> ::= <Array>',
            '<Value> ::= true',
            '<Value> ::= false',
            '<Value> ::= null',
        ], $rules);
    }

    public function test_serialize_deserialize()
    {
        $loader = Loader::fromFile(__DIR__ . '/JSON.cgt');
        $parser = $loader->createNewParser();
        /** @var LalrParser $parser */
        $parser = unserialize(serialize($parser));
        $node = $parser->parse(file_get_contents(__DIR__ . '/sample02.json'));
        $this->assertTrue($parser->isAccepted());
        $this->assertEquals(
            "<Json> = [<Object> = [} <Members> = [<Pair> = [<Value> = [<Object> = [} <Members> = [<Members> = [<Members> = [<Pair> = [<Value> = [<Object> = [} <Members> = [<Pair> = [<Value> = [<Array> = [] <Elements> = [<Elements> = [<Elements> = [<Value> = [<Object> = [} <Members> = [<Members> = [<Pair> = [<Value> = [\"CloseDoc()\"] : \"onclick\"]] , <Pair> = [<Value> = [\"Close\"] : \"value\"]] {]]] , <Value> = [<Object> = [} <Members> = [<Members> = [<Pair> = [<Value> = [\"OpenDoc()\"] : \"onclick\"]] , <Pair> = [<Value> = [\"Open\"] : \"value\"]] {]]] , <Value> = [<Object> = [} <Members> = [<Members> = [<Pair> = [<Value> = [\"CreateNewDoc()\"] : \"onclick\"]] , <Pair> = [<Value> = [\"New\"] : \"value\"]] {]]] []] : \"menuitem\"]] {]] : \"popup\"]] , <Pair> = [<Value> = [\"File\"] : \"value\"]] , <Pair> = [<Value> = [\"file\"] : \"id\"]] {]] : \"menu\"]] {]]",
            (string) $node
        );
    }

    /**
     * @dataProvider getFilenames
     */
    public function test_parsing($filename)
    {
        $loader = Loader::fromFile(__DIR__ . '/JSON.cgt');
        $parser = $loader->createNewParser();

        $node = $parser->parse(file_get_contents(__DIR__.$filename));
        $this->assertEquals('Json', $node->getSymbol()->getName());
        $this->assertTrue($parser->isAccepted());
    }

    /**
     * @expectedException \Tmilos\GoldParser\Error\TokenException
     * @expectedExceptionMessage [TOKEN ERROR] Unexpected text i at line 0 col 2
     */
    public function test_token_error()
    {
        $loader = Loader::fromFile(__DIR__ . '/JSON.cgt');
        $parser = $loader->createNewParser();

        $parser->parse('{ invalid: 1, json');
    }

    /**
     * @expectedException \Tmilos\GoldParser\Error\ParseException
     * @expectedExceptionMessage [Syntax error] Expected : but found ,
     */
    public function test_parse_error()
    {
        $loader = Loader::fromFile(__DIR__ . '/JSON.cgt');
        $parser = $loader->createNewParser();

        $parser->parse('{ "invalid", "json" }');
    }
}
