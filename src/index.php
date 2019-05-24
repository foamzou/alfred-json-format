<?php

$input = shell_exec('pbpaste');

function _format_json($json, $html = false) {
		$tabcount = 0; 
		$result = ''; 
		$inquote = false; 
		$ignorenext = false; 
		if ($html) { 
		    $tab = "&nbsp;&nbsp;&nbsp;"; 
		    $newline = "<br/>"; 
		} else { 
		    $tab = "\t"; 
		    $newline = "\n"; 
		} 
		for($i = 0; $i < strlen($json); $i++) { 
		    $char = $json[$i]; 
		    if ($ignorenext) { 
		        $result .= $char; 
		        $ignorenext = false; 
		    } else { 
		        switch($char) { 
		            case '{': 
		                $tabcount++; 
		                $result .= $char . $newline . str_repeat($tab, $tabcount); 
		                break; 
		            case '}': 
		                $tabcount--; 
		                $result = trim($result) . $newline . str_repeat($tab, $tabcount) . $char; 
		                break; 
		            case ',': 
		                $result .= $char . $newline . str_repeat($tab, $tabcount); 
		                break; 
		            case '"': 
		                $inquote = !$inquote; 
		                $result .= $char; 
		                break; 
		            case '\\': 
		                if ($inquote) $ignorenext = true; 
		                $result .= $char; 
		                break; 
		            default: 
		                $result .= $char; 
		        } 
		    } 
		} 
		return $result; 
	}

$input = json_encode(json_decode($input, true), 256);

$ret = _format_json($input);

$style = "<style> textarea {
    border: none;
    overflow: hidden;
    outline: none;
	width: 100%;
	overflow: hidden;

    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
	color: black;
	font-size: 15px;

    resize: none; /*remove the resize handle on the bottom right*/
}
</style>";

$script = "<script>
var element = document.getElementById('t');
    element.style.height = '5px';
    element.style.height = (element.scrollHeight)+'px';

</script>
";

$ret = $style . "<textarea id='t' disabled='disabled'>{$ret}</textarea>" . $script;

$filename = '/tmp/json_' . time(). '.html';
file_put_contents($filename, $ret);
exec("open {$filename}");


?>
