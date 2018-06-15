<?php
/**
 * Class TlpdTest
 *
 * @package TLPD
 */

/**
 * Sample test case.
 */
class TlpdTest extends WP_UnitTestCase {


	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

	function test_tlpd_howdy_message() {
		$translated_text = 'Howdy';
		$text            = 'Blar Howdy,Blar';
		$domain          = 'tlpd';

		$new_message = tlpd_howdy_message( $translated_text, $text, $domain );

		$this->assertEquals( 'Blar Blar', $new_message );
	}

	function test_tlpd_event_output() {

		$test_data = tlpd_event_output( 1 );

		$this->assertTrue( is_array( $test_data ) );

	}
}
