<?php

namespace alirezap30web\ShortUrl\Tests\Unit;

use alirezap30web\ShortUrl\Models\Link;
use alirezap30web\ShortUrl\Tests\TestCase;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Carbon;

class TestLocalDriver extends TestCase
{

	public function test_shorten ()
	{
	    $long = "127.0.0.1:8000/here/is/a/long/url?x=12#asdlf12";
        $shortened = \Shorturl::shorten($long);
        $this->assertNotEmpty($shortened);
        $this->assertNotNull($shortened);
	}


	public function test_expand ()
    {
        $long = "127.0.0.1:8000/here/is/a/long/url?x=12#asdlf12";

        $long1 = \Shorturl::expand(\Shorturl::shorten($long));

        $this->assertEquals($long1, $long1);
    }

    public function test_get_driver ()
    {
        $this->assertEquals(config("shorturl.drivers.default"), \Shorturl::getDriver());
    }

    public function test_multiple_pathes_with_same_timestamp ()
    {
        $tbl = config('shorturl.drivers.local.table_name');
        \DB::table($tbl)->insert([
            [
                'short_path' => '7777',
                'long_path' => 'long/path/test/1',
                'base_url' => 'http://127.0.0.1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'short_path' => '777h',
                'long_path' => 'long/path/test/2',
                'base_url' => 'http://127.0.0.1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'short_path' => '777k',
                'long_path' => 'long/path/test/3',
                'base_url' => 'http://127.0.0.1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
        $short = \Shorturl::shorten("http://127.0.0.1/long/path/test/4");
        $this->assertEquals("http://127.0.0.1/777Z", $short);
    }
}