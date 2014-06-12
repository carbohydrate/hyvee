<?php
class ods {
    public function __construct() {
        $this->content = '<?xml version="1.0" encoding="UTF-8"?><office:document-content xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" xmlns:presentation="urn:oasis:names:tc:opendocument:xmlns:presentation:1.0" xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0" xmlns:math="http://www.w3.org/1998/Math/MathML" xmlns:form="urn:oasis:names:tc:opendocument:xmlns:form:1.0" xmlns:script="urn:oasis:names:tc:opendocument:xmlns:script:1.0" xmlns:ooo="http://openoffice.org/2004/office" xmlns:ooow="http://openoffice.org/2004/writer" xmlns:oooc="http://openoffice.org/2004/calc" xmlns:dom="http://www.w3.org/2001/xml-events" xmlns:xforms="http://www.w3.org/2002/xforms" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:rpt="http://openoffice.org/2005/report" xmlns:of="urn:oasis:names:tc:opendocument:xmlns:of:1.2" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:grddl="http://www.w3.org/2003/g/data-view#" xmlns:tableooo="http://openoffice.org/2009/table" xmlns:drawooo="http://openoffice.org/2010/draw" xmlns:calcext="urn:org:documentfoundation:names:experimental:calc:xmlns:calcext:1.0" xmlns:loext="urn:org:documentfoundation:names:experimental:office:xmlns:loext:1.0" xmlns:field="urn:openoffice:names:experimental:ooo-ms-interop:xmlns:field:1.0" xmlns:formx="urn:openoffice:names:experimental:ooxml-odf-interop:xmlns:form:1.0" xmlns:css3t="http://www.w3.org/TR/css3-text/" office:version="1.2">';
        //public $counter = int;
    }                      //1        2     3        4       5      6             7            8                9
    public function addCell($sheet, $row, $column, $value, $type, $border = 0, $width = 1, $spanCell = 1, $repeat = 1) {
        $this->sheets[$sheet]['rows'][$row][$column]['cellProp'] = array('cellType'=>$type, 'cellValue'=>$value, 'xml:id'=>$column, 'cellBorder'=>$border, 'cellSpan'=> $spanCell, 'repeat'=> $repeat);
    }

    public function addArray($sheet, $array, $start, $column, $addBlank = NULL, $emptyBorder) {
        if ($addBlank != NULL) {
            for ($i = 1; $i <= $addBlank; $i++) {
                $this->addCell(0, $start, $column, '', 'string', 0, 1, 1, 4);
                $start++;
            }
        }
        foreach ($array as $shift) {
            foreach ($shift as $key => $cell) {
                $key = $key + $column;  //$key is either (0,1,2) and column is sent.  To put cells to the right of cell that are already created need to add the column+start point.
                $this->addCell($sheet, $start, $key, $cell, 'string', 1);
            }
            $key++;
            $this->addCell($sheet, $start, $key, '', 'string', 1, 1, 1, 1);  //this puts break boxes in, aka column 4 small boxes.
            $start++;
        }
        for ($i = 1; $i <= $emptyBorder; $i++) {  //for empty border at the bottom of the list of workers.
            $this->addCell(0, $start, $column, '', 'string', 1, 1, 1, 4);
            $start++;
        }
    }

    private function addBorder($border) {
        //ce1=all ce2=top ce3=right ce4=bottom ce5=left ce6=top,left ce7=top,right ce8=bottom,right ce9=bottom,left
        return '<table:table-cell table:style-name="ce' . $border . '" ';
    }

    public function getContent() {
        $sheetArr = $this->sheets;
        $string = $this->content;
        $string .= '<office:scripts/><office:font-face-decls></office:font-face-decls>';
        $string .= '<office:automatic-styles>';
        $string .= '<style:style style:name="co1" style:family="table-column"><style:table-column-properties fo:break-before="auto" style:column-width=".95in"/></style:style>';
        $string .= '<style:style style:name="co2" style:family="table-column"><style:table-column-properties fo:break-before="auto" style:column-width=".5in"/></style:style>';
        $string .= '<style:style style:name="co3" style:family="table-column"><style:table-column-properties fo:break-before="auto" style:column-width=".5in"/></style:style>';
        $string .= '<style:style style:name="co4" style:family="table-column"><style:table-column-properties fo:break-before="auto" style:column-width=".2in"/></style:style>';
        $string .= '<style:style style:name="co5" style:family="table-column"><style:table-column-properties fo:break-before="auto" style:column-width="1in"/></style:style>';
        $string .= '<style:style style:name="co6" style:family="table-column"><style:table-column-properties fo:break-before="auto" style:column-width=".95in"/></style:style>';
        $string .= '<style:style style:name="co7" style:family="table-column"><style:table-column-properties fo:break-before="auto" style:column-width=".5in"/></style:style>';
        $string .= '<style:style style:name="co8" style:family="table-column"><style:table-column-properties fo:break-before="auto" style:column-width=".5in"/></style:style>';
        $string .= '<style:style style:name="co9" style:family="table-column"><style:table-column-properties fo:break-before="auto" style:column-width=".2in"/></style:style>';
        $string .= '<style:style style:name="ce0" style:family="table-cell" style:parent-style-name="Default"><style:table-cell-properties fo:border="none"/></style:style><style:style style:name="ce1" style:family="table-cell" style:parent-style-name="Default"><style:table-cell-properties fo:border="0.06pt solid #000000"/></style:style><style:style style:name="ce2" style:family="table-cell" style:parent-style-name="Default"><style:table-cell-properties fo:border-bottom="none" fo:border-left="none" fo:border-right="none" fo:border-top="0.06pt solid #000000"/></style:style><style:style style:name="ce3" style:family="table-cell" style:parent-style-name="Default"><style:table-cell-properties fo:border-bottom="none" fo:border-left="none" fo:border-right="0.06pt solid #000000" fo:border-top="none"/></style:style><style:style style:name="ce4" style:family="table-cell" style:parent-style-name="Default"><style:table-cell-properties fo:border-bottom="0.06pt solid #000000" fo:border-left="none" fo:border-right="none0" fo:border-top="none"/></style:style><style:style style:name="ce5" style:family="table-cell" style:parent-style-name="Default"><style:table-cell-properties fo:border-bottom="none" fo:border-left="0.06pt solid #000000" fo:border-right="none" fo:border-top="none"/></style:style><style:style style:name="ce6" style:family="table-cell" style:parent-style-name="Default"><style:table-cell-properties fo:border-bottom="none" fo:border-left="0.06pt solid #000000" fo:border-right="none" fo:border-top="0.06pt solid #000000"/></style:style><style:style style:name="ce7" style:family="table-cell" style:parent-style-name="Default"><style:table-cell-properties fo:border-bottom="none" fo:border-left="none" fo:border-right="0.06pt solid #000000" fo:border-top="0.06pt solid #000000"/></style:style><style:style style:name="ce8" style:family="table-cell" style:parent-style-name="Default"><style:table-cell-properties fo:border-bottom="0.06pt solid #000000" fo:border-left="none" fo:border-right="0.06pt solid #000000" fo:border-top="none"/></style:style><style:style style:name="ce9" style:family="table-cell" style:parent-style-name="Default"><style:table-cell-properties fo:border-bottom="0.06pt solid #000000" fo:border-left="0.06pt solid #000000" fo:border-right="none" fo:border-top="none"/></style:style>';
        $string .= '</office:automatic-styles>';
        $string .= '<office:body><office:spreadsheet>';
        foreach ($sheetArr as $sheetIndex => $sheetContent) {
            $string .= '<table:table table:name="' . $sheetIndex . '" table:style-name="ta1">';
            $string .= '<table:table-column table:style-name="co1"/>';
            $string .= '<table:table-column table:style-name="co2"/>';
            $string .= '<table:table-column table:style-name="co3"/>';
            $string .= '<table:table-column table:style-name="co4"/>';
            $string .= '<table:table-column table:style-name="co5"/>';
            $string .= '<table:table-column table:style-name="co6"/>';
            $string .= '<table:table-column table:style-name="co7"/>';
            $string .= '<table:table-column table:style-name="co8"/>';
            $string .= '<table:table-column table:style-name="co9"/>';
            //code for a cell taking up more then one column
            //$string .= '<table:table-column table:number-columns-repeated="3"/>';
            foreach ($sheetContent['rows'] as $rowIndex => $rowContent) {
                $string .= '<table:table-row>';
                foreach ($rowContent as $cellIndex => $a) {
                    for ($i = 1; $i <= $a['cellProp']['repeat']; $i++) {
                        $string .= $this->addBorder($a['cellProp']['cellBorder']);
                        //$string .= 'table:number-columns-spanned="' . $a['cellProp']['cellSpan'] . '" ';
                        $string .= 'office:value-type ="' . $a['cellProp']['cellType'] . '">';
                        $string .= '<text:p>' . $a['cellProp']['cellValue'] . '</text:p>';
                        $string .= '</table:table-cell>';
                    }
                }
                $string .= '</table:table-row>';
            }
            $string .= '</table:table>';
        }
        $string .= '</office:spreadsheet></office:body>';
        $string .= '</office:document-content>';
        return $string;
    }

    private function getManifest() {
        return '<?xml version="1.0" encoding="UTF-8"?><manifest:manifest xmlns:manifest="urn:oasis:names:tc:opendocument:xmlns:manifest:1.0" manifest:version="1.2"><manifest:file-entry manifest:full-path="/" manifest:version="1.2" manifest:media-type="application/vnd.oasis.opendocument.spreadsheet"/><manifest:file-entry manifest:full-path="Thumbnails/thumbnail.png" manifest:media-type="image/png"/><manifest:file-entry manifest:full-path="settings.xml" manifest:media-type="text/xml"/><manifest:file-entry manifest:full-path="content.xml" manifest:media-type="text/xml"/><manifest:file-entry manifest:full-path="meta.xml" manifest:media-type="text/xml"/><manifest:file-entry manifest:full-path="styles.xml" manifest:media-type="text/xml"/><manifest:file-entry manifest:full-path="manifest.rdf" manifest:media-type="application/rdf+xml"/><manifest:file-entry manifest:full-path="Configurations2/accelerator/current.xml" manifest:media-type=""/><manifest:file-entry manifest:full-path="Configurations2/" manifest:media-type="application/vnd.sun.xml.ui.configuration"/></manifest:manifest>';
    }

    private function getMeta() {
        return '<?xml version="1.0" encoding="UTF-8"?>
        <office:document-meta xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:ooo="http://openoffice.org/2004/office" xmlns:grddl="http://www.w3.org/2003/g/data-view#" office:version="1.2">
        <office:meta>
            <meta:initial-creator>php-to-ods</meta:initial-creator>
            <meta:creation-date>2014-05-31T22:27:17.730124783</meta:creation-date>
            <dc:date>2014-05-31T22:43:42.957608204</dc:date>
            <dc:creator>user</dc:creator>
            <meta:editing-duration>PT15M58S</meta:editing-duration>
            <meta:editing-cycles>2</meta:editing-cycles>
            <meta:generator>php</meta:generator>
            <meta:document-statistic meta:table-count="1" meta:cell-count="8" meta:object-count="0"/>
        </office:meta>
        </office:document-meta>';
    }

    private function getSettings() {
        return '<?xml version="1.0" encoding="UTF-8"?><office:document-settings xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:config="urn:oasis:names:tc:opendocument:xmlns:config:1.0" xmlns:ooo="http://openoffice.org/2004/office" office:version="1.2"><office:settings><config:config-item-set config:name="ooo:view-settings"><config:config-item config:name="VisibleAreaTop" config:type="int">1806</config:config-item><config:config-item config:name="VisibleAreaLeft" config:type="int">0</config:config-item><config:config-item config:name="VisibleAreaWidth" config:type="int">4515</config:config-item><config:config-item config:name="VisibleAreaHeight" config:type="int">1806</config:config-item><config:config-item-map-indexed config:name="Views"><config:config-item-map-entry><config:config-item config:name="ViewId" config:type="string">view1</config:config-item><config:config-item-map-named config:name="Tables"><config:config-item-map-entry config:name="Sheet1"><config:config-item config:name="CursorPositionX" config:type="int">0</config:config-item><config:config-item config:name="CursorPositionY" config:type="int">4</config:config-item><config:config-item config:name="HorizontalSplitMode" config:type="short">0</config:config-item><config:config-item config:name="VerticalSplitMode" config:type="short">0</config:config-item><config:config-item config:name="HorizontalSplitPosition" config:type="int">0</config:config-item><config:config-item config:name="VerticalSplitPosition" config:type="int">0</config:config-item><config:config-item config:name="ActiveSplitRange" config:type="short">2</config:config-item><config:config-item config:name="PositionLeft" config:type="int">0</config:config-item><config:config-item config:name="PositionRight" config:type="int">0</config:config-item><config:config-item config:name="PositionTop" config:type="int">0</config:config-item><config:config-item config:name="PositionBottom" config:type="int">0</config:config-item><config:config-item config:name="ZoomType" config:type="short">0</config:config-item><config:config-item config:name="ZoomValue" config:type="int">100</config:config-item><config:config-item config:name="PageViewZoomValue" config:type="int">60</config:config-item><config:config-item config:name="ShowGrid" config:type="boolean">true</config:config-item></config:config-item-map-entry></config:config-item-map-named><config:config-item config:name="ActiveTable" config:type="string">Sheet1</config:config-item><config:config-item config:name="HorizontalScrollbarWidth" config:type="int">270</config:config-item><config:config-item config:name="ZoomType" config:type="short">0</config:config-item><config:config-item config:name="ZoomValue" config:type="int">100</config:config-item><config:config-item config:name="PageViewZoomValue" config:type="int">60</config:config-item><config:config-item config:name="ShowPageBreakPreview" config:type="boolean">false</config:config-item><config:config-item config:name="ShowZeroValues" config:type="boolean">true</config:config-item><config:config-item config:name="ShowNotes" config:type="boolean">true</config:config-item><config:config-item config:name="ShowGrid" config:type="boolean">true</config:config-item><config:config-item config:name="GridColor" config:type="long">12632256</config:config-item><config:config-item config:name="ShowPageBreaks" config:type="boolean">true</config:config-item><config:config-item config:name="HasColumnRowHeaders" config:type="boolean">true</config:config-item><config:config-item config:name="HasSheetTabs" config:type="boolean">true</config:config-item><config:config-item config:name="IsOutlineSymbolsSet" config:type="boolean">true</config:config-item><config:config-item config:name="IsSnapToRaster" config:type="boolean">false</config:config-item><config:config-item config:name="RasterIsVisible" config:type="boolean">false</config:config-item><config:config-item config:name="RasterResolutionX" config:type="int">1270</config:config-item><config:config-item config:name="RasterResolutionY" config:type="int">1270</config:config-item><config:config-item config:name="RasterSubdivisionX" config:type="int">1</config:config-item><config:config-item config:name="RasterSubdivisionY" config:type="int">1</config:config-item><config:config-item config:name="IsRasterAxisSynchronized" config:type="boolean">true</config:config-item></config:config-item-map-entry></config:config-item-map-indexed></config:config-item-set><config:config-item-set config:name="ooo:configuration-settings"><config:config-item config:name="LoadReadonly" config:type="boolean">false</config:config-item><config:config-item config:name="SaveVersionOnClose" config:type="boolean">false</config:config-item><config:config-item config:name="IsDocumentShared" config:type="boolean">false</config:config-item><config:config-item config:name="IsKernAsianPunctuation" config:type="boolean">false</config:config-item><config:config-item config:name="ApplyUserData" config:type="boolean">true</config:config-item><config:config-item config:name="CharacterCompressionType" config:type="short">0</config:config-item><config:config-item config:name="PrinterSetup" config:type="base64Binary"/><config:config-item config:name="PrinterName" config:type="string"/><config:config-item config:name="UpdateFromTemplate" config:type="boolean">true</config:config-item><config:config-item config:name="AutoCalculate" config:type="boolean">true</config:config-item><config:config-item config:name="EmbedFonts" config:type="boolean">false</config:config-item><config:config-item config:name="RasterIsVisible" config:type="boolean">false</config:config-item><config:config-item config:name="IsSnapToRaster" config:type="boolean">false</config:config-item><config:config-item config:name="RasterSubdivisionX" config:type="int">1</config:config-item><config:config-item config:name="IsOutlineSymbolsSet" config:type="boolean">true</config:config-item><config:config-item config:name="AllowPrintJobCancel" config:type="boolean">true</config:config-item><config:config-item config:name="RasterResolutionX" config:type="int">1270</config:config-item><config:config-item config:name="HasSheetTabs" config:type="boolean">true</config:config-item><config:config-item config:name="LinkUpdateMode" config:type="short">3</config:config-item><config:config-item config:name="IsRasterAxisSynchronized" config:type="boolean">true</config:config-item><config:config-item config:name="ShowPageBreaks" config:type="boolean">true</config:config-item><config:config-item config:name="HasColumnRowHeaders" config:type="boolean">true</config:config-item><config:config-item config:name="GridColor" config:type="long">12632256</config:config-item><config:config-item config:name="ShowGrid" config:type="boolean">true</config:config-item><config:config-item config:name="RasterSubdivisionY" config:type="int">1</config:config-item><config:config-item config:name="RasterResolutionY" config:type="int">1270</config:config-item><config:config-item config:name="ShowNotes" config:type="boolean">true</config:config-item><config:config-item config:name="ShowZeroValues" config:type="boolean">true</config:config-item></config:config-item-set></office:settings></office:document-settings>';
    }

    private function getStyle() {
        return '<?xml version="1.0" encoding="UTF-8"?><office:document-styles xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" xmlns:presentation="urn:oasis:names:tc:opendocument:xmlns:presentation:1.0" xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0" xmlns:math="http://www.w3.org/1998/Math/MathML" xmlns:form="urn:oasis:names:tc:opendocument:xmlns:form:1.0" xmlns:script="urn:oasis:names:tc:opendocument:xmlns:script:1.0" xmlns:ooo="http://openoffice.org/2004/office" xmlns:ooow="http://openoffice.org/2004/writer" xmlns:oooc="http://openoffice.org/2004/calc" xmlns:dom="http://www.w3.org/2001/xml-events" xmlns:rpt="http://openoffice.org/2005/report" xmlns:of="urn:oasis:names:tc:opendocument:xmlns:of:1.2" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:grddl="http://www.w3.org/2003/g/data-view#" xmlns:tableooo="http://openoffice.org/2009/table" xmlns:drawooo="http://openoffice.org/2010/draw" xmlns:calcext="urn:org:documentfoundation:names:experimental:calc:xmlns:calcext:1.0" xmlns:loext="urn:org:documentfoundation:names:experimental:office:xmlns:loext:1.0" xmlns:css3t="http://www.w3.org/TR/css3-text/" office:version="1.2"><office:font-face-decls><style:font-face style:name="Liberation Sans" svg:font-family="&apos;Liberation Sans&apos;" style:font-family-generic="swiss" style:font-pitch="variable"/><style:font-face style:name="DejaVu Sans" svg:font-family="&apos;DejaVu Sans&apos;" style:font-family-generic="system" style:font-pitch="variable"/><style:font-face style:name="Lohit Devanagari" svg:font-family="&apos;Lohit Devanagari&apos;" style:font-family-generic="system" style:font-pitch="variable"/><style:font-face style:name="WenQuanYi Zen Hei Sharp" svg:font-family="&apos;WenQuanYi Zen Hei Sharp&apos;" style:font-family-generic="system" style:font-pitch="variable"/></office:font-face-decls><office:styles><style:default-style style:family="table-cell"><style:paragraph-properties style:tab-stop-distance="0.5in"/><style:text-properties style:font-name="Liberation Sans" fo:language="en" fo:country="US" style:font-name-asian="DejaVu Sans" style:language-asian="zh" style:country-asian="CN" style:font-name-complex="DejaVu Sans" style:language-complex="hi" style:country-complex="IN"/></style:default-style><number:number-style style:name="N0"><number:number number:min-integer-digits="1"/></number:number-style><number:currency-style style:name="N104P0" style:volatile="true"><number:currency-symbol number:language="en" number:country="US">$</number:currency-symbol><number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/></number:currency-style><number:currency-style style:name="N104"><style:text-properties fo:color="#ff0000"/><number:text>-</number:text><number:currency-symbol number:language="en" number:country="US">$</number:currency-symbol><number:number number:decimal-places="2" number:min-integer-digits="1" number:grouping="true"/><style:map style:condition="value()&gt;=0" style:apply-style-name="N104P0"/></number:currency-style><style:style style:name="Default" style:family="table-cell"><style:text-properties style:font-name-asian="WenQuanYi Zen Hei Sharp" style:font-family-asian="&apos;WenQuanYi Zen Hei Sharp&apos;" style:font-family-generic-asian="system" style:font-pitch-asian="variable" style:font-name-complex="Lohit Devanagari" style:font-family-complex="&apos;Lohit Devanagari&apos;" style:font-family-generic-complex="system" style:font-pitch-complex="variable"/></style:style><style:style style:name="Result" style:family="table-cell" style:parent-style-name="Default"><style:text-properties fo:font-style="italic" style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color" fo:font-weight="bold"/></style:style><style:style style:name="Result2" style:family="table-cell" style:parent-style-name="Result" style:data-style-name="N104"/><style:style style:name="Heading" style:family="table-cell" style:parent-style-name="Default"><style:table-cell-properties style:text-align-source="fix" style:repeat-content="false"/><style:paragraph-properties fo:text-align="center"/><style:text-properties fo:font-size="16pt" fo:font-style="italic" fo:font-weight="bold"/></style:style><style:style style:name="Heading1" style:family="table-cell" style:parent-style-name="Heading"><style:table-cell-properties style:rotation-angle="90"/></style:style></office:styles><office:automatic-styles><style:page-layout style:name="Mpm1"><style:page-layout-properties style:writing-mode="lr-tb"/><style:header-style><style:header-footer-properties fo:min-height="0.2953in" fo:margin-left="0in" fo:margin-right="0in" fo:margin-bottom="0.0984in"/></style:header-style><style:footer-style><style:header-footer-properties fo:min-height="0.2953in" fo:margin-left="0in" fo:margin-right="0in" fo:margin-top="0.0984in"/></style:footer-style></style:page-layout><style:page-layout style:name="Mpm2"><style:page-layout-properties style:writing-mode="lr-tb"/><style:header-style><style:header-footer-properties fo:min-height="0.2953in" fo:margin-left="0in" fo:margin-right="0in" fo:margin-bottom="0.0984in" fo:border="2.49pt solid #000000" fo:padding="0.0071in" fo:background-color="#c0c0c0"><style:background-image/></style:header-footer-properties></style:header-style><style:footer-style><style:header-footer-properties fo:min-height="0.2953in" fo:margin-left="0in" fo:margin-right="0in" fo:margin-top="0.0984in" fo:border="2.49pt solid #000000" fo:padding="0.0071in" fo:background-color="#c0c0c0"><style:background-image/></style:header-footer-properties></style:footer-style></style:page-layout></office:automatic-styles><office:master-styles><style:master-page style:name="Default" style:page-layout-name="Mpm1"><style:header><text:p><text:sheet-name>???</text:sheet-name></text:p></style:header><style:header-left style:display="false"/><style:footer><text:p>Page <text:page-number>1</text:page-number></text:p></style:footer><style:footer-left style:display="false"/></style:master-page><style:master-page style:name="Report" style:page-layout-name="Mpm2"><style:header><style:region-left><text:p><text:sheet-name>???</text:sheet-name> (<text:title>???</text:title>)</text:p></style:region-left><style:region-right><text:p><text:date style:data-style-name="N2" text:date-value="2014-05-31">00/00/0000</text:date>, <text:time>00:00:00</text:time></text:p></style:region-right></style:header><style:header-left style:display="false"/><style:footer><text:p>Page <text:page-number>1</text:page-number> / <text:page-count>99</text:page-count></text:p></style:footer><style:footer-left style:display="false"/></style:master-page></office:master-styles></office:document-styles>';
    }

    public function exportOds($path) {
        $zip = new ZipArchive();
        if ($zip->open($path, ZipArchive::CREATE)!==TRUE) {
            exit('Can\'t Open File');
        }
        $zip->addEmptyDir('Thumbnails');
        $zip->addFromString('mimetype', 'application/vnd.oasis.opendocument.spreadsheet');
        $zip->addFromString('settings.xml', $this->getSettings());
        $zip->addFromString('content.xml', $this->getContent());
        $zip->addFromString('meta.xml', $this->getMeta());
        $zip->addFromString('styles.xml', $this->getStyle());
        $zip->addEmptyDir('META-INF');
        $zip->addFromString('META-INF/manifest.xml', $this->getManifest());
        $zip->addEmptyDir('Configurations2/acceleator');
        $zip->addEmptyDir('Configurations2/floater');
        $zip->addEmptyDir('Configurations2/images');
        $zip->addEmptyDir('Configurations2/menubar');
        $zip->addEmptyDir('Configurations2/popupmenu');
        $zip->addEmptyDir('Configurations2/progressbar');
        $zip->addEmptyDir('Configurations2/statusbar');
        $zip->addEmptyDir('Configurations2/toolbar');
        $zip->close();
        chmod($path, 0777);
    }
}
//$obj = new ods;
//$obj->addCell(1,1,1,'test','string', 4);
//$path = 'uploads/file.ods';
//$obj->exportOds($path);
?>
