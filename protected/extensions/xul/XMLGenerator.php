<?php

class XMLGenerator
{

    var $xml;
    var $ver;
    var $charset;

    function __constructor($ver = '1.0', $charset = 'UTF-8')
    {
        $this->ver = $ver;
        $this->charset = $charset;
    }

    function generate($root, $data = array())
    {
        $this->xml = new XmlWriter();
        $this->xml->openMemory();
        $this->xml->setIndent(true);
        $this->xml->setIndentString('  ');
        $this->xml->startDocument($this->ver, $this->charset);
        $this->xml->startElement($root);
        $this->write($this->xml, $data);
        $this->xml->endElement();
        $this->xml->endDocument();
        $xml = $this->xml->outputMemory(true);
        $this->xml->flush();
        return $xml;
    }

    function write(XMLWriter $xml, $data)
    {
        foreach ($data as $key => $value) {
            $name = $key;
            if (is_object($value))
                $name = strtolower(get_class($value));

            if (is_array($value) || is_object($value)) {
                $xml->startElement($name);
                $this->write($xml, $value);
                $xml->endElement();

            } else{
                $xml->writeAttribute($name, $value);                
            }

        }

    }

}

?>