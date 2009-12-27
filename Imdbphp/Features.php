<?php
/**
 * A class which provides all the functions to interact with the 
 * more special features of the IMDb iPhone API.
 * 
 * @package Imdbphp
 * @class Imdbphp_Features
 * @extends Imdbphp_Base
 * @author John McClane
 * @copyright John McClane 2009
 * @version 0.9.0
 */

require_once (dirname(__FILE__)."/Base.php");

class Imdbphp_Features extends Imdbphp_Base {
	####################################################
	# OVERRIDDEN METHODS                               #
	####################################################
	/**
	 * Constructor method.
	 * 
	 * @param  string         $locale 		(Optional) Localization Parameter in the Format en_US.
	 * @return Imdbphp_Features
	 */
	public function __construct($locale = null) 
	{	
		// call the parent constructor
		if ($locale) {
			parent::__construct( $locale );
		} else {
			parent::__construct();
		}
	}
	
	####################################################
	# PUBLIC METHODS                                   #
	####################################################
	/**
	 * Get a hello from the IMDb iPhone API formatted as a JSON string.
	 * 
	 * Inside the JSON is also a status which is also checked by statusCheck().
	 * 
	 * @return string  JSON formatted string.
	 */
	public function getHello()
	{
		return $this->makeRequest('/hello');
	}
	
	/**
	 * Get showtimes for a given date and location formatted as a JSON string.
	 * 
	 * A Location (US,33333) is required
	 *
	 * @param  string $location  			Location in the following format "US,33333".
	 * @param  string $date  	(Optional)  Date in the sql format "2009-12-24".
	 * @return string  						JSON formatted string.
	 */
	public function getShowtimes($location, $date = null)
	{
		// checks the given date or sets today
		if( ! $this->checkDate($date))
		{
			$date = date("Y-m-d", time());
		}
		
		// checks the location or throws exception
		if( ! $this->checkLocation($location))
		{
			throw new Exception ('getShowtimes(): A location is required.');
		}
		
		$arg['date'] = $date;
		$arg['location'] = $location;
		return $this->makeRequest('/showtimes/location', $arg);
	}
	
	/**
	 * Get a list of movies coming soon formatted as a JSON string.
	 *
	 * @return string  JSON formatted string.
	 */
	public function getComingSoon()
	{
		return $this->makeRequest('/feature/comingsoon');
	}
	
	/**
	 * Get current box office results for a specified region formatted as a JSON string.
	 *
	 * @param  string $boxofficeRegion	(Optional) 	The boxoffice region formatted in two character country code "US".
	 * @return string  								JSON formatted string.
	 */
	public function getBoxOfficeResults($boxofficeRegion = null)
	{
		// checks the given Boxoffice Region or sets "US"
		if( ! $this->checkBoxofficeRegion($boxofficeRegion))
		{
			$boxofficeRegion = "US";
		}
		
		$arg['boxoffice_region'] = $boxofficeRegion;
		return $this->makeRequest('/boxoffice', $arg);
	}
	
	/**
	 * Get the Moviemeter list formatted as a JSON string.
	 * 
	 * @return string  JSON formatted string.
	 */
	public function getMoviemeter()
	{
		return $this->makeRequest('/chart/moviemeter');
	}
	
	/**
	 * Get the latest top 250 movies list formatted as a JSON string.
	 * 
	 * @return string  JSON formatted string.
	 */
	public function getTop250Movies()
	{
		return $this->makeRequest('/chart/top');
	}
	
	/**
	 * Get the tv program for a given night formatted as a JSON string.
	 *
	 * @param  string $date  (Optional) Date in the sql format "2009-12-24".
	 * @return string  					JSON formatted string.
	 */
	public function getUsTvTonight($date = null)
	{
		// checks the given date or sets today
		if( ! $this->checkDate($date))
		{
			$date = date("Y-m-d", time());
		}
		
		$arg['date'] = $date;
		return $this->makeRequest('/tv/tonight', $arg);
	}
	
	/**
	 * Get tv recaps for a given night formatted as a JSON string.
	 *
	 * @param  string $date  (Optional) Date in the sql format "2009-12-24".
	 * @return string  					JSON formatted string.
	 */
	public function getUsTvRecaps($date = null)
	{
		// checks the given date or sets today
		if( ! $this->checkDate($date))
		{
			$date = date("Y-m-d", time());
		}
		
		$arg['date'] = $date;
		return $this->makeRequest('/tv/recap', $arg);
	}
	
	/**
	 * Get the latest Starmeter list formatted as a JSON string.
	 * 
	 * @return string  JSON formatted string.
	 */
	public function getStarmeter()
	{
		return $this->makeRequest('/chart/starmeter');
	}
	
	/**
	 * Get a list of people born at the given date formatted as a JSON string.
	 *
	 * @param  string $date  (Optional) Date in the sql format "2009-12-24".
	 * @return string  					JSON formatted string.
	 */
	public function getBornToday($date = null)
	{
		// checks the given date or sets today
		if( ! $this->checkDate($date))
		{
			$date = date("Y-m-d", time());
		}
		
		$arg['date'] = $date;
		return $this->makeRequest('/feature/borntoday', $arg);
	}
	
	/**
	 * Get the latest news formatted as a JSON string.
	 * 
	 * @return string  JSON formatted string.
	 */
	public function getNews()
	{
		return $this->makeRequest('/news');
	}
	
	###########################################################
	# PROTECTED METHOD                                        #
	###########################################################
	/**
	 * Checks if the given location is in a valid format
	 * 
	 * If it is not valid it throws an exception.
	 * 
	 * @param  string $location 	Location in following format "US,33333".
	 * @return bool  				True if Location is alright. False if there is a problem with it.
	 */
	protected function checkLocation($location)
	{
		if(!$location)
		{
			return false;
		} else {
			if(!preg_match('/^([A-Z]{2}),([0-9]{5})$/', $location)) {
				throw new Exception ( 'checkLocation() expects a Location formatted like US,33333.' );
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Checks if the given date is in a valid format
	 * 
	 * If it is not valid it throws an exception.
	 * 
	 * @param  string $date 	Date in the sql format "2009-12-24".
	 * @return bool  			True if Date is alright. False if there is a problem with it.
	 */
	protected function checkDate($date)
	{
		if(!$date)
		{
			return false;
		} else {
			if(!preg_match('/^([1-3][0-9]{3,3})-(0?[1-9]|1[0-2])-(0?[1-9]|[1-2][1-9]|3[0-1])$/', $date)) {
				throw new Exception ( 'checkDate() expects a SQL formatted date like 2009-12-24.' );
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Checks if the given Boxoffice Region is in a valid format
	 * 
	 * If it is not valid it throws an exception.
	 * 
	 * @param  string $boxofficeRegion 	Boxoffice Region in following format "US".
	 * @return bool  					True if Boxoffice Region is alright. False if there is a problem with it.
	 */
	protected function checkBoxofficeRegion($boxofficeRegion)
	{
		if(!$boxofficeRegion)
		{
			return false;
		} else {
			if(!preg_match("/^([A-Z]{2})?$/", $boxofficeRegion)) {
				throw new Exception ( 'checkBoxofficeRegion(): Format should be XX. For example US.' );
				return false;
			}
		}
		return true;
	}
}