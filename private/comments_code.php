<?php
    function load_xml(){
        $xml = new DOMDocument("1.0", "ISO-8859-15");
        $xml->load("private/comments.xml") or die("Error while loading comments.xml ...");
        
        return $xml;
    }
    
    function get_node($node, $nb, $xml){
        return $xml->getElementsByTagName($node)->item($nb);
    }
    
    function get_childs($nodelst){
        return $nodelst->childNodes;
    }
    
    function add_element_in($in, $content, $xml){
        $first = get_node("root", 0, $xml);
        $new = $first->createElement($in, $content);
        $first->appendChild($new);
        
        return $first;
    }
    
    function create_comments_zone_for_article($article_nb, $written_by, $xml){
        $ret = add_element_in($article_nb, "", $xml);
        $ret->setAttribute("date", date("d-m-Y"));
        $ret->setAttribute("by", $written_by);
        
        $xml->appendChild($ret);
    }
    
    function add_comment_on($pseudo, $comment, $on){
        $xml = load_xml();
        
        $xml->saveXML();
    }
?>