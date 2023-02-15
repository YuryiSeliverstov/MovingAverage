<?php

namespace Math;

class MovingAverage
{
	private $inputDays		=	[];
	private $inputWeeks		=	[];
	private $inputMonths	=	[];
	
	public function __construct(string $fileName='weather_statistics.csv')
	{
		$this->parseFile($fileName);
	}
	
	public function renderChunk(string $title, array $data, string $type='days', string $view='chunkChart.tpl')
	{
		$labels			=	'';
		$avgValues		=	'';
		$maValues		=	'';
		$containerId	=	$type.'_'.time();
		foreach ($data as $index=>$value)
		{
			$avgValues	.=	$value['avg'].',';
			$maValues	.=	$value['ma'].',';
			if ($type=='days')
			{
				$labels	.=	"'".date('d.m.Y',$index)."',";
			}
			else
			{
				$labels	.=	"'".$index."',";
			}
		}
		require($view);
	}
	
	private function calculateAvg($value,$count)
	{
		return round($value/$count,2);
	}
	
	public function getGraphDataDays():array
	{
		foreach ($this->inputDays as $dayDt=>$value)
		{
			$this->inputDays[$dayDt]['ma']	=	$value['sum']/24;
		}
		return $this->inputDays;
	}
	
	public function getGraphDataWeeks():array
	{
		foreach ($this->inputWeeks as $weekNumber=>$value)
		{
			$this->inputWeeks[$weekNumber]['avg']	=	$value['sum']/$value['dayCount'];
			$this->inputWeeks[$weekNumber]['ma']	=	$value['sum']/7;
		}
		return $this->inputWeeks;
	}
	
	public function getGraphDataMonths():array
	{
		foreach ($this->inputMonths as $monthNumber=>$value)
		{
			$this->inputMonths[$monthNumber]['avg']	=	$value['sum']/$value['dayCount'];
			$this->inputMonths[$monthNumber]['ma']	=	$value['sum']/30;
		}
		return $this->inputMonths;
	}
	
	private function getWeekNumber(int $date)
	{
		$date	=	new \DateTime(date('Y-m-d',$date));
		$w		=	(int)$date->format('W');
		$m		=	(int)$date->format('n');
		return $w==1?($m==12?53:1):($w>=51?($m==1?1:$w):$w);
    }
	
	private function getMonthNumber(string $date):int
	{
		return intval(date('m',$date));
	}
	
	private function parseFile(string $fileName):bool
	{
		$this->inputDays	=	[];
		$this->inputWeeks	=	[];
		$this->inputMonths	=	[];
		if (file_exists($fileName))
		{
			$handle = fopen($fileName, "r");
			if ($handle) 
			{
				/*
					Skip First Line
				*/
				$line 				= 	fgets($handle);
				/*
					Hours count in data
				*/
				$hoursCountInDay	=	[];
				$prevIndex			=	0;
				while (($line = fgets($handle)) !== false) 
				{
					$line					=	str_replace('"','',$line);
					$r						=	explode(';',$line);
					$r[1]					=	floatval($r[1]);
					
					$dayDate				=	strtotime(date('d.m.Y',strtotime($r[0])));
					
					if ($prevIndex && $prevIndex!=$dayDate)
					{
						$this->inputDays[$prevIndex]['avg']=$this->calculateAvg($this->inputDays[$prevIndex]['sum'],$hoursCountInDay[$prevIndex]);
					}
					/*
						Calculates Avg Temperature per Day
					*/					
					if (!isset($this->inputDays[$dayDate]))
					{
						$this->inputDays[$dayDate]['sum']				=	$r[1];
						$hoursCountInDay[$dayDate]						=	1;
					}
					else
					{
						$this->inputDays[$dayDate]['sum']				+=	$r[1];
						$hoursCountInDay[$dayDate]						++;
					}
					
					$prevIndex=$dayDate;
				}
				/*
					Calculate Avg Temp Last Element
				*/
				
				$this->inputDays[$dayDate]['avg']	=	$this->calculateAvg($this->inputDays[$dayDate]['sum'],$hoursCountInDay[$dayDate]);
				
				foreach ($this->inputDays as $dayDt=>$value)
				{
					$weekNumber=$this->getWeekNumber($dayDt);
					
					if (!isset($this->inputWeeks[$weekNumber]['dayCount']))
					{
						$this->inputWeeks[$weekNumber]['dayCount']	=	0;
						$this->inputWeeks[$weekNumber]['sum']		=	0;
					}
					
					$monthNumber=$this->getMonthNumber($dayDt);
					if (!isset($this->inputMonths[$monthNumber]['dayCount']))
					{
						$this->inputMonths[$monthNumber]['dayCount']=	0;
						$this->inputMonths[$monthNumber]['sum']		=	0;
					}
					
					$this->inputWeeks[$weekNumber]['dayCount']		++;
					$this->inputWeeks[$weekNumber]['sum']			+=	$value['avg'];
					
					$this->inputMonths[$monthNumber]['dayCount']	++;
					$this->inputMonths[$monthNumber]['sum']			+=	$value['avg'];
				}
				
				fclose($handle);
			}
		}
		return false;
	}
}
?>