<?php
	return [
		"drivers" => [
			/** Specifies the default driver */
			"default" => "local",
			
			"local" => [
			    'base_url'      => env('APP_URL', '127.0.0.1'),
				'table_name'    => 'links',
                'charset'       => 'latin1',

                // utf8_bin gives us case sensitive search to the DB
                'collation'     => 'latin1_bin',

                /**
                 * For making the urls unique in the DB, we must create an index or unique_key
                 * for the column url
                 * But based on different versions of mysql the size of the index key
                 * is different as you can see here
                 * https://dev.mysql.com/doc/refman/5.7/en/innodb-restrictions.html#innodb-maximums-minimums
                 *
                 * Possible values in byte could be (767, 3072) and based on the
                 * charset and collation used for the database or the table, given values may differ
                 * for example for a ** utf8mb4 ** charset the given values could be (767/4, 3072/4);
                 *
                 */
				'index_key_prefix_size'     => '767',

				/**
				 * The shuffled string contains both [0-9] and [a-zA-Z]
				 */
				'str_shuffled'      => "7hkZ5LiTs29FbCIzoYBADldOP1nueEgcWXqMNJ46GHxUpSaKjr8mfy3RQwvtV0",
				'case_sensitive'    => true,
				
				/**
				 * Here are some info about number of urls with a specific length that can be made if we use
				 * both 52(case-sensitive) english alphabet chars and 10 digits
				 * -----------------------------------------------------------------
				 * |                    |
				 * |    url length      |           max number of urls can be made
				 * |                    |
				 * -----------------------------------------------------------------
				 * |         1          |           ((52 + 10) = 62) ^ 1  = 62
				 * -----------------------------------------------------------------
				 * |         2          |           62 ^ 2  = 3,844
				 * -----------------------------------------------------------------
				 * |         3          |           62 ^ 3  = 238,328
				 * -----------------------------------------------------------------
				 * |         4          |           62 ^ 4  = 14,776,336
				 * -----------------------------------------------------------------
				 * |         5          |           62 ^ 5  = 916,132,832
				 * -----------------------------------------------------------------
				 * |         6          |           62 ^ 6  = 56,800,235,584
				 * -----------------------------------------------------------------
				 * |         7          |           62 ^ 7  = 3,521,614,606,208
				 * -----------------------------------------------------------------
				 * |         8          |           62 ^ 8  = 218,340,105,584,896
				 * -----------------------------------------------------------------
				 */
				'min_length'        => 4,
				'max_length'        => 8
			],
			"bitly" => [
				"user" => "user",
				"pass" => "pass"
			],
			"google" => [
				"user" => "user",
				"pass" => "pass"
			]
		]
	];
