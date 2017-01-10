<?PHP


class Slider {

	public $NAME;
	public $ORIENTATION;

	function Slider(){
	}






	function show(){
	   echo "
    		<div class=\"carpe_slider_display_holder\" >
                <input class=\"carpe_slider_display\"
                    id=\"" .$NAME ."\"
                    name=\"" .$NAME ."_var\"
                    type=\"text\" 
                    from=\"0\" 
                    to=\"100\" 
                    valuecount=\"101\" 
                    value=\"0\" 
                    typelock=\"off\" />
            </div>    
        ";

        echo "
            <div class=\"carpe_horizontal_slider_track\">
                <div class=\"carpe_slider_slit\">&nbsp;</div>
                <div class=\"carpe_slider\"
                    id=\"" .$NAME ."_slider\"
                    orientation=\"horizontal\"
                    distance=\"100\"
                    display=\"" .$NAME ."\"
                    style=\"left: 0px;\">&nbsp;</div>
            </div>

        ";

        // LINK NACH: http://carpe.ambiprospect.com  erforderlich! 
	}


}


?>