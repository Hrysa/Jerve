<?php

namespace Jerve\Routing;

class Parser
{
	private $regex = "@\{([A-Za-z0-9_%]+)\}@";

	private $parameters = [];

	public function __construct($path)
	{
		$this->path = $path;
	}

	public function generate($absPath)
	{
		$this->absPath = $absPath;
		$this->regexPath = preg_replace($this->regex, "([A-Za-z0-9_%]+)" , $absPath);
		if($this->match())
		{
			if($this->generateParameters())
				return $this->parameters;
			return true;
		}
		return false;
	}

	private function generateParameters()
	{
			$r = preg_match_all($this->regex, $this->absPath, $matches);
			foreach($matches[1] as $k => $each)
			{
				$this->parameters[$each] = $this->matches[$k+1];
			}
			return count($this->parameters);
	}

	private function parseRegular()
	{
		
	}

	private function match()
	{
		return preg_match("@^".$this->regexPath."$@i", $this->path, $this->matches);
	}
}
