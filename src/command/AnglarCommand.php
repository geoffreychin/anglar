<?php namespace Gchin\Anglar;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AnglarCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'Anglar:make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates Laravel to Angular scafolding from larval service to angular mvv';

	protected $app_path = __DIR__;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->app_path = base_path();
	}

	/**
	 * Execute the console command.
	 * Ingest trending or article data command
	 *
	 * @return mixed
	 */
	public function fire()
	{
		//requiredname
		$resourcename = $this->argument('resourcename');

		//flags
		$hasNgService = $this->option('ngservice');
		$hasNgDashboard = $this->option('ngdashboard');

		$find_array = array('##resourcename##','##lresourcename##');
		$replace_array = array(ucfirst($resourcename),strtolower($resourcename));

		//create backend service
		$this->_generateTemplateCode(__DIR__."/../views/templateLaravelService.txt",$this->app_path."/app/models/".ucfirst(strtolower($resourcename))."Service.php",$find_array,$replace_array);
		//create backed controller
		$this->_generateTemplateCode(__DIR__."/../views/templateLaravelController.txt",$this->app_path."/app/controllers/".ucfirst(strtolower($resourcename))."Controller.php",$find_array,$replace_array);		
		//Routes and api endpoints
		$this->_appendRoutes($resourcename,$hasNgDashboard);

		// create frontend service
		if($hasNgService)
		{
			$this->_generateTemplateCode(__DIR__."/../views/templateAngularService.txt",$this->app_path."/public/ng-".strtolower($resourcename)."-service.js",$find_array,$replace_array);
		}
		
		//create dashboard view
		if($hasNgDashboard)
		{
			$this->_generateTemplateCode(__DIR__."/../views/templateLaravelView.txt",$this->app_path."/app/views/".ucfirst(strtolower($resourcename))."Dashboard.blade.php",$find_array,$replace_array);
			$this->_generateTemplateCode(__DIR__."/../views/templateAngularApp.txt",$this->app_path."/public/ng-".strtolower($resourcename)."-app.js",$find_array,$replace_array);
			$this->_generateTemplateCode(__DIR__."/../views/templateAngularController.txt",$this->app_path."/public/ng-".strtolower($resourcename)."-controller.js",$find_array,$replace_array);

		}

	
		echo PHP_EOL.'Scafolded !!'.PHP_EOL;
		$myFile = __DIR__."/../views/ascii.txt";
		$fh = fopen($myFile, 'r');
		$theData = fread($fh, filesize($myFile));
		fclose($fh);
		echo $theData . PHP_EOL;

		echo 'Do a git status to check your file structure for new files.' . PHP_EOL;
		if($hasNgDashboard)
		{
			echo 'View a sample at http://localserverhostname/'.strtolower($resourcename).'/dashboard  to view ' . PHP_EOL;
		}
	}

	protected function _read($filePath)
	{
		return file_get_contents($filePath);
	}

	protected function _generateTemplateCode($tmplPath,$savePath,$searchArray,$replaceArray)
	{
		$fileString = $this->_read($tmplPath);
		$fileString = str_replace($searchArray,$replaceArray, $fileString);

		if(!file_exists($savePath))
		{
			$newFile = fopen($savePath, "w");
			fwrite($newFile, $fileString);
			fclose($newFile);
		}else{
			echo "File exist already: '{$savePath}'" . PHP_EOL;
		}
	}

	protected function _appendRoutes($resourcename,$useDashboard){
		$fileString = $this->_read(__DIR__."/../views/templateLaravelRoutes.txt");

		if($useDashboard)
			$dashboard_String ="//Angular Dashboard for ".$resourcename." services 
Route::GET('/".strtolower($resourcename)."/dashboard/', array('uses' => '".ucfirst($resourcename)."Controller@".ucfirst($resourcename)."Dashboard'));";
		else
			$dashboard_String = '';
		$fileString = str_replace(array('##resourcename##','##lresourcename##','##Dashboard##'), array(ucfirst($resourcename),strtolower($resourcename),$dashboard_String), $fileString);

		if(file_exists($this->app_path."/app/routes.php"))
		{
			file_put_contents($this->app_path."/app/routes.php",$fileString,FILE_APPEND);
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			  array('resourcename', InputArgument::REQUIRED, 'Name of the resource'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('ngdashboard', null, InputOption::VALUE_NONE, 'Adds a angular basic dashboard view and controller for service', null),
			array('ngservice', null, InputOption::VALUE_NONE, 'Adds angular basic service', null),
		);
	}

}
