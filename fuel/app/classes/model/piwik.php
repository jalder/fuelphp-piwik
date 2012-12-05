<?php 
/**
 * @author jalder
 * 
 * @references http://piwik.org/docs/analytics-api/reference/
 *
 */

class Model_Piwik{
	//standard
	private $api_key;
	private $uri;
	private $site_id;
	private $method = 'ImageGraph.get'; //API, Actions, CustomVariables, ExampleAPI, Goals, ImageGraph.get, LanguagesManager, Live, MobileMessaging, MultiSites, Overlay, PDFReports, Provider, Referers, SEO, SitesManager, Transitions, UserCountry, UserSettings, UsersManager, VisitFrequency, VisitTime, VisitorInterest, VisitsSummary
	private $period = 'month'; //can be day, week, month, year, or range
	private $date = 'today'; //date can be magic words 'today' or 'yesterday' or 'YYYY-MM-DD', if $period==range can be comma sep. range, lastX, previousX
	private $segment; //custom segment to filter reports to, ex: referrerName==twitter.com, see docs for combined AND / OR formats
	private $format = 'json'; //xml, json (check your cross-domain!), csv, tsv, html, php, rss, orignal (PHP data structure to unserialize)
	
	//imageGraph
	private $apiModule = 'VisitsSummary';
	private $apiAction = 'get';
	private $graphType = 'evolution'; //evolution, verticalBar, pie, 3dPie
	private $colors;
	private $width = '500';
	private $height = '250';
	private $fontSize = '9';
	private $legendFontSize;
	private $aliasedGraph = '0';
	private $idGoal;
	private $legendAppendMetric = '1';
	private $metric;
	private $outputType = '0';
	private $columns = '';
	private $labels = '';
	private $showLegend = '1';
	
	//optionals
	private $language;
	private $idSubtable;
	private $expanded;
	private $flat;
	private $label;
	
	//filters
	private $filter_limit;
	
	public function __construct()
	{
		Config::load('piwik','piwik');
		$this->api_key = Config::get('piwik.api_key');
		$this->uri = Config::get('piwik.uri');
		$this->site_id = Config::get('piwik.site_id');
		$this->colors = Config::Get('piwik.colors');
	}	
	
	public function getKeywords()
	{
		$this->method = 'Referers.getKeywords';
		$this->period = 'month';
		$this->date = 'yesterday';
		$this->filter_limit = '10';
		$this->format = 'json';
		return json_decode($this->downloadPage($this->buildRequest()));
	}
	
	private function downloadPage($uri)
	{
		$url = $uri;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
	}
	
	private function buildRequest()
	{
		return $this->uri.'?module=API&method='.$this->method.'&idSite='.$this->site_id.'&apiModule='.$this->apiModule.'&apiAction='.$this->apiAction.'&token_auth='.$this->api_key.'&graphType='.$this->graphType.'&period='.$this->period.'&date='.$this->date.'&width='.$this->width.'&height='.$this->height.'&filter_limit='.$this->filter_limit.'&format='.$this->format.'&aliasedGraph='.$this->aliasedGraph;
	}
	
	public function getImageSource($graph = 'visits', $date = 'previous30', $period = 'day')
	{
		$this->method = 'ImageGraph.get';
		$this->date = $date;
		$this->period = $period;
		switch($graph){
			case 'browsers':
				$this->apiModule = 'UserSettings';
				$this->apiAction = 'getBrowser';
				$this->graphType = 'horizontalBar';
				break;
			case 'visits':
				$this->apiModule = 'VisitsSummary';
				$this->apiAction = 'get';
				$this->graphType = 'evolution';
				break;
			case 'resolutions':
				$this->apiModule = 'UserSettings';
				$this->apiAction = 'getResolution';
				$this->graphType = 'verticalBar';
				break;
			case 'os':
				$this->apiModule = 'UserSettings';
				$this->apiAction = 'getOS';
				$this->graphType = 'pie';				
				break;
			default:
	
				break;
		}
		
		return $this->buildRequest();
	}
	
}
