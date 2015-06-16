<?php

namespace YoVideo;

class Dates extends Model{

	public function  __construct($data = array()){
		parent::__construct();
	}

	public function search($language, $from, $to, $mode=NULL){

		$out  = ['cine' => [], 'sale' => [], 'rent' => []];
		$url  = '/dates/'.$language;
		$get  = ['from' => $from, 'to' => $to];

		if($mode) $url  = '/dates/'.$mode.'/'.$language;

		try{
			$results = $this->request->get($url, $get);
		} catch(Exception $e){
			throw $e;
		}

		if($mode){
			$out = [];

			foreach($results as $n => $film){
				$film = new Film($film);
				$film->nearestDate('fr', $mode, $from, $to);
				$out[] = $film;
			}

		}else{

			foreach($results as $k => $res){
				foreach($res as $n => $r){
					$film = new Film($r);
					$film->nearestDate('fr', $k, $from, $to);
					$results[$k][$n] = $film;
				}
			}

			$out = $results;
		}

		$this->set($out);

		return $this;
	}

// HELPERS /////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function previousWednesday(){
		return date('Y-m-d', strtotime('last Wednesday'));
	}

	public function nextWednesday($more=NULL){
		$str = 'next Wednesday';
		if($more) $str .= ' '.$more;

		return date('Y-m-d', strtotime($str));
	}

}