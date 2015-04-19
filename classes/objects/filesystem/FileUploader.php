<?PHP

class FileUploader{
  private $TARGETPATH;


  function FileUploader($targetPath="/tmp", $prefix=""){
     $this->setTargetpath($targetPath);
  }

  

  function setTargetPath($path){
	$this->TARGETPATH = $_SERVER['DOCUMENT_ROOT']."/".substr(dirname($_SERVER['SCRIPT_NAME']),1).$path;
  }


  function getTargetPath(){
	return $this->TARGETPATH;
  }




  function show(){
  	
      echo "						
			<div align=\"center\">
			
				<applet
						title=\"JUpload\"
						name=\"JUpload\"
						code=\"com.smartwerkz.jupload.classic.JUpload\"
						codebase=\".\"
						archive=\"dist/jupload.jar,
							      dist/commons-codec-1.3.jar,
								  dist/commons-httpclient-3.0-rc4.jar,
								  dist/commons-logging.jar,
								  dist/skinlf/skinlf-6.2.jar\"
						width=\"700\"
						height=\"360\"
						mayscript=\"mayscript\"
						alt=\"JUpload by www.jupload.biz\">
				
					<param name=\"Config\" value=\"cfg/jupload.default.config\">
				
					Your browser does not support Java Applets or you disabled Java Applets in your browser-options.
					To use this applet, please install the newest version of Sun's Java Runtime Environment (JRE).
					You can get it from <a href=\"http://www.java.com/\">java.com</a>
				
				</applet>				
			</div>


	  ";
	
  }
  
  
  
  
}



?>