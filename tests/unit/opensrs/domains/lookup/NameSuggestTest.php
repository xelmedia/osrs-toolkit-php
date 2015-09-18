<?php

use OpenSRS\domains\lookup\NameSuggest;
/**
 * @group lookup
 * @group NameSuggest
 */
class NameSuggestTest extends PHPUnit_Framework_TestCase
{
    protected $func = 'lookupNameSuggest';

    /**
     * New NameSuggest should throw an exception if 
     * data->domain is ommitted 
     * 
     * @return void
     */
    public function testValidateMissingDomain()
    {
        $data = (object) array (
            'data' => (object) array (
                // 'domain' => 'hockey.com'
                ),
            );

        $this->setExpectedException('OpenSRS\Exception');
        $ns = new NameSuggest('array', $data);
    }

    /**
     * Get TLD function should return either the selected tlds,
     * or a default set
     * 
     * @return void
     */
    public function testGetTlds()
    {
        // get included default tlds
        $data = new \stdClass;
        $data->data = new \stdClass;
        $data->data->domain = 'hockey.com';

        $bc = new OpenSRS\backwardcompatibility\dataconversion\domains\lookup\NameSuggest;
        $data = $bc->convertDataObject( $data );

        // test default tlds
        $ns = new NameSuggest('array', $data, true);
        $expectedResult = array(
            "lookup" => array(
                "tlds" => $ns->defaultTlds
                ),
            "suggestion" => array(
                "tlds" => $ns->defaultTlds
                )
            );
        $this->assertTrue($expectedResult == array('.com', '.net', '.org'));



        // get selected tlds, none are checked, pass only "all" tlds
        $data->data->allnsdomains = '.com;.net;.org';
        $data->data->alllkdomains = '.ca;.jp;.it';

        $ns = new NameSuggest('array', $data);
        $expectedResult = array(
            "lookup" => array(
                "tlds" => array( '.com', '.net', '.org' )
                ),
            "suggestion" => array(
                "tlds" => array( '.ca', '.jp', '.it' )
                )
            );
        $this->assertTrue($expectedResult == $ns->getTlds());
        // not unsetting these since we want to use them as
        // the "all domains" setting for the rest of the tests
        // unset( $data->data->allnsdomains = '.com;.net;.org' );
        // unset( $data->data->alllkdomains = '.ca;.jp;.it' );



        // get selected tlds, specify suggestion tlds
        $data->data->nsselected = '.com;.net';
        $ns = new NameSuggest('array', $data);
        $expectedResult = array(
            "lookup" => array(
                "tlds" => array( '.com', '.net' )
                ),
            "suggestion" => array(
                "tlds" => array( '.ca', '.jp', '.it' )
                )
            );
        $this->assertTrue($expectedResult == $ns->getTlds());
        // unset test value
        unset($data->data->nsselected);




        // get selected tlds, specify both lookup and suggestion tlds
        $data->data->lkselected = '.com;.net';
        $ns = new NameSuggest('array', $data);
        $expectedResult = array(
            "lookup" => array(
                "tlds" => array( '.com', '.net', '.org' )
                ),
            "suggestion" => array(
                "tlds" => array( '.com', '.net' )
                )
            );
        $this->assertTrue($expectedResult == $ns->getTlds());
        // unset test value
        unset($data->data->lkselected);




        // get selected tlds, specify both lookup and suggestion tlds
        $data->data->nsselected = '.org';
        $data->data->lkselected = '.com;.net';
        $ns = new NameSuggest('array', $data);
        $expectedResult = array(
            "lookup" => array(
                "tlds" => array( '.org' )
                ),
            "suggestion" => array(
                "tlds" => array( '.com', '.net' )
                )
            );
        $this->assertTrue($expectedResult == $ns->getTlds());
        // unset test value
        unset($data->data->nsselected);
        unset($data->data->lkselected);
    }
}
