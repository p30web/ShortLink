<?php

	namespace alirezap30web\ShortUrl\Models;
	
	use Illuminate\Database\Eloquent\Model;
	
	class Link extends Model
	{
		protected  $fillable = [
			'short_path',
			'long_path',
			'base_url',
			'clicks',
			'properties'
		];
		
		protected  $casts = [
			'properties' => 'collection'
		];

		public function __construct(array $attributes = [])
        {
            $this->table = config('shorturl.drivers.local.table_name');
            parent::__construct($attributes);
        }
    }