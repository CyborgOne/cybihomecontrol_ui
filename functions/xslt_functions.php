<?PHP

  function getInterpretedXslt($xslPath, $xmlPath, $xsltProcessor=null ){
    if($xsltProcessor==null){
        $xsltProcessor = new XsltProcessor;
        $xsltProcessor->registerPHPFunctions();
    }
    
    $xsl = new DOMDocument;
    $xsl->load($xslPath);
    $xsltProcessor->importStylesheet($xsl);
    
    $xml = new DOMDocument;
    $xml->load($xmlPath);
    
    
    $output = $xsltProcessor->transformToXML($xml) or die('Transformation error!');
    
    return $output;
  }
 
 
 
 
    
    function getSqlResultAsXml($dbCon, $SQL_query ){
        // Replace by a query that matches your database
       
        $result = $dbCon->executeQuery($SQL_query);
         
        // we produce XML
        header("Content-type: text/xml");
        $XML = "<?xml version=\"1.0\"?>\n";
        if (isset($xslt_file) && $xslt_file) $XML .= "<?xml-stylesheet href=\"$xslt_file\" type=\"text/xsl\" ?>";
         
        // root node
        $XML .= "<result>\n";
        // rows
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {    
          $XML .= "\t<row>\n"; 
          $i = 0;
          // cells
          foreach ($row as $cell) {
            // Escaping illegal characters - not tested actually ;)
            $cell = str_replace("<", "&lt;", $cell);
            $cell = str_replace(">", "&gt;", $cell);
            $cell = str_replace("\"", "&quot;", $cell);
            //$cell = str_replace("&", "<![CDATA["."&"."]]>", $cell);
            $col_name = mysql_field_name($result,$i);
            // creates the "<tag>contents</tag>" representing the column
            $XML .= "\t\t<" . $col_name . ">" . $cell . "</" . $col_name . ">\n";
            $i++;
          }
          $XML .= "\t</row>\n"; 
         }
        $XML .= "</result>\n";
         
        // output the whole XML string
        return $XML;
    }
 
?>