<?php
	namespace alirezap30web\ShortUrl\Drivers;

	use Illuminate\Support\Str;
	use alirezap30web\ShortUrl\Models\Link;
	
	class LocalDriver implements BaseDriver
	{
		protected  $props = [];
		protected $config, $main_str, $head, $tail, $base_url, $path;
		
		public function __construct ()
		{
			$this->config = config('shorturl.drivers.local');
			$this->main_str = $this->config['str_shuffled'];
			$this->head = $this->main_str[0];
			$this->tail = $this->main_str[strlen($this->main_str) - 1];
            $this->checkCaseSensitive ();
		}

        /**
         * @param string $url
         *
         * @return string
         */
		public function  expand (string $url) :string
		{
		    $this->parseUrl($url);
		    $link = Link::where("short_path", $this->path)->first();
		    if ($link) {
		        $link->increment("clicks");
		        return $link->base_url . "/" . $link->long_path;
            }
			return "";
		}

        /**
         * @param string $url
         *
         * @return string
         * @throws \Exception
         */
		public function shorten (string $url) :string
		{
            $this->parseUrl ($url);

            // Check if given url has been shorten previously
            $duplicate = Link::where(['long_path' => $this->path])->first();
            if ($duplicate)
                return $duplicate->base_url . "/" . $duplicate->short_path;

            $short_path = $this->getNextShortpath();

            try {
                Link::create([
                    "long_path" => $this->path,
                    "short_path" => $short_path,
                    'base_url' => $this->base_url,
                    'properties' => $this->props]
                );
            } catch (\Exception $e) {
                // If it is duplicate entry exception
                // try to insert a new entry
                if ($e instanceof \Illuminate\Database\QueryException && $e->getCode() == "23000") {
                    return $this->shorten($url);
                }
            }
            return $this->base_url . "/" . $short_path;
        }

        /**
         * Git the first short url
         *
         * @return string
         */
        private function getFirstUrl () : string
        {
            $min_length = $this->config['min_length'];
            $short_path = "";
            for ($i = 0; $i < $min_length; $i++)
                $short_path .= $this->head;
            return $short_path;
        }

        /**
         *
         * Get the next short url based on the given item (it gets permutations one by one)
         *
         * @param string $current_perm
         * @return string
         */
        private function findNextPerm (string $current_perm) :string
		{
			if (!strlen($current_perm))
			    return $this->head;

			$arr = array_reverse(str_split($current_perm));
			foreach($arr as $key => $current_char) {
				if ($current_char == $this->tail) {
					$current_perm = Str::replaceLast($current_char, "", $current_perm);
					return $this->findNextPerm($current_perm) . $this->head;
				}
                $next_char = str_split(Str::after($this->main_str, $current_char))[0];
				return Str::replaceLast($current_char, $next_char, $current_perm);
			}
		}
		
		/**
		 * @param array $props
		 *
		 * @return LocalDriver
		 */
		public function withProperties (array $props = []) :LocalDriver
		{
			$this->props = array_merge($this->props, $props);
			return $this;
		}

        private function parseUrl (string $url)
        {
            $parse = parse_url($url);
            $path = "";
            if ($parse['path'] ?? null)
                $path .= str::replaceFirst("/", "", $parse['path']);
            if ($parse['query'] ?? null)
                $path .= "?" . $parse['query'];
            if ($parse['fragment'] ?? null)
                $path .= "#" . $parse['fragment'];

            $this->base_url = str_replace("/" . $path, "", $url);
            $this->path = $path;
        }

        private function checkCaseSensitive ()
        {
            if ((!$this->config['case_sensitive'] ?? null)) {
                // Remove upper cases from main string
                $this->main_str = preg_replace("/(.)\\1+/", "$1", strtolower($this->main_str));
            }
        }

        /**
         * @return string
         */
        private function getNextShortpath () : string
        {
            $tbl = (new Link)->getTable();
            // Get latest short_path(s)
            // As multiple instances could be created at the same timestamp which we get
            // the latest one based on that
            // so we must find the latest one(short_path) in the permutation of the main_str
            $latest = collect(\DB::select("SELECT short_path FROM $tbl WHERE created_at = (SELECT MAX(created_at) FROM $tbl)"));

            if (!$latest->count())
                return $this->getFirstUrl();

            if ($latest->count() == 1) {
                return $this->findNextPerm($latest->first()->short_path);
            }
            else {
                foreach ($latest->reverse() as $key => $item) {
                    $next = $this->findNextPerm($item->short_path);

                    // If next permutation of current item is in fetched items
                    // find next permutation of the next fetched one
                    if (!$latest->contains("short_path", $next))
                        return $next;
                }
            }
        }
	}