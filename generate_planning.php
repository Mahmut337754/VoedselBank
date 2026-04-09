<?php

// Planningdata
$rows = [
    ['nr' => '1', 'begin' => '9:00',  'eind' => '9:15',  'activiteit' => 'Standup meeting',                    'opmerking' => 'Dennis komt 5 min later'],
    ['nr' => '2', 'begin' => '9:15',  'eind' => '10:15', 'activiteit' => 'Scenario_01 schrijven',              'opmerking' => ''],
    ['nr' => '3', 'begin' => '10:15', 'eind' => '10:35', 'activiteit' => 'Database script helemaal klaarmaken','opmerking' => 'Afgerond'],
    ['nr' => '4',  'begin' => '10:35', 'eind' => '12:30', 'activiteit' => 'Unit tests schrijven voor Allergie model',  'opmerking' => ''],
    ['nr' => '5',  'begin' => '12:30', 'eind' => '13:00', 'activiteit' => 'Lunchpauze',                                 'opmerking' => ''],
    ['nr' => '6',  'begin' => '13:00', 'eind' => '13:30', 'activiteit' => 'AllergieController afmaken',                 'opmerking' => 'CRUD volledig'],
    ['nr' => '7',  'begin' => '13:30', 'eind' => '14:15', 'activiteit' => 'Views allergieen afronden',                  'opmerking' => 'index, create, edit, show'],
    ['nr' => '8',  'begin' => '14:15', 'eind' => '14:30', 'activiteit' => 'Korte pauze',                                'opmerking' => ''],
    ['nr' => '9',  'begin' => '14:30', 'eind' => '15:30', 'activiteit' => 'Scenario_02 schrijven en testen',            'opmerking' => ''],
    ['nr' => '10', 'begin' => '15:30', 'eind' => '16:00', 'activiteit' => 'Dagafsluiting en retrospectief',             'opmerking' => 'Wat ging goed / wat kan beter'],
];

// Kleur voor header rij (donkergrijs)
$headerShade = '404040';
// Kleur voor data rijen (lichtgrijs afwisselend)
$rowShade1 = 'D9D9D9';
$rowShade2 = 'F2F2F2';

function makeCell(string $text, bool $bold = false, string $shade = '', string $width = '1200'): string {
    $shadeXml = $shade ? "<w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"{$shade}\"/>" : '';
    $boldXml  = $bold  ? '<w:b/>' : '';
    return "
        <w:tc>
          <w:tcPr>
            <w:tcW w:w=\"{$width}\" w:type=\"dxa\"/>
            {$shadeXml}
          </w:tcPr>
          <w:p>
            <w:pPr><w:spacing w:before=\"60\" w:after=\"60\"/></w:pPr>
            <w:r><w:rPr>{$boldXml}<w:sz w:val=\"20\"/><w:szCs w:val=\"20\"/></w:rPr>
              <w:t xml:space=\"preserve\">" . htmlspecialchars($text) . "</w:t>
            </w:r>
          </w:p>
        </w:tc>";
}

function makeRow(array $cells): string {
    return "<w:tr>" . implode('', $cells) . "</w:tr>";
}

// Header
$headerRow = makeRow([
    makeCell('Nr',               true, '404040', '600'),
    makeCell('Begintijd',        true, '404040', '1100'),
    makeCell('Eindtijd',         true, '404040', '1100'),
    makeCell('Soort activiteit', true, '404040', '4500'),
    makeCell('Opmerking',        true, '404040', '2200'),
]);

// Data rijen
$dataRows = '';
foreach ($rows as $i => $row) {
    $shade = ($i % 2 === 0) ? $rowShade1 : $rowShade2;
    $dataRows .= makeRow([
        makeCell($row['nr'],         false, $shade, '600'),
        makeCell($row['begin'],      false, $shade, '1100'),
        makeCell($row['eind'],       false, $shade, '1100'),
        makeCell($row['activiteit'], false, $shade, '4500'),
        makeCell($row['opmerking'],  false, $shade, '2200'),
    ]);
}

$documentXml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas"
  xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
  xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <w:body>
    <w:p>
      <w:pPr><w:spacing w:after="100"/></w:pPr>
      <w:r>
        <w:rPr><w:b/><w:sz w:val="32"/><w:szCs w:val="32"/></w:rPr>
        <w:t>Planning</w:t>
      </w:r>
    </w:p>
    <w:p>
      <w:pPr><w:spacing w:after="100"/></w:pPr>
      <w:r>
        <w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr>
        <w:t>Voor de planning gebruik je de onderstaande tabel:</w:t>
      </w:r>
    </w:p>
    <w:tbl>
      <w:tblPr>
        <w:tblStyle w:val="TableGrid"/>
        <w:tblW w:w="9500" w:type="dxa"/>
        <w:tblBorders>
          <w:top    w:val="single" w:sz="4" w:space="0" w:color="999999"/>
          <w:left   w:val="single" w:sz="4" w:space="0" w:color="999999"/>
          <w:bottom w:val="single" w:sz="4" w:space="0" w:color="999999"/>
          <w:right  w:val="single" w:sz="4" w:space="0" w:color="999999"/>
          <w:insideH w:val="single" w:sz="4" w:space="0" w:color="999999"/>
          <w:insideV w:val="single" w:sz="4" w:space="0" w:color="999999"/>
        </w:tblBorders>
      </w:tblPr>
      {$headerRow}
      {$dataRows}
    </w:tbl>
    <w:sectPr>
      <w:pgSz w:w="12240" w:h="15840"/>
      <w:pgMar w:top="1080" w:right="1080" w:bottom="1080" w:left="1080"/>
    </w:sectPr>
  </w:body>
</w:document>
XML;

$relsXml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>
XML;

$stylesXml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:style w:type="table" w:styleId="TableGrid">
    <w:name w:val="Table Grid"/>
  </w:style>
</w:styles>
XML;

$contentTypesXml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml"  ContentType="application/xml"/>
  <Override PartName="/word/document.xml"
    ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
  <Override PartName="/word/styles.xml"
    ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
</Types>
XML;

$rootRelsXml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1"
    Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument"
    Target="word/document.xml"/>
</Relationships>
XML;

// Bouw de zip (docx)
$zipFile = __DIR__ . '/planning.docx';
if (file_exists($zipFile)) unlink($zipFile);

$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE) !== true) {
    die("Kan zip niet aanmaken\n");
}

$zip->addFromString('[Content_Types].xml',        $contentTypesXml);
$zip->addFromString('_rels/.rels',                $rootRelsXml);
$zip->addFromString('word/document.xml',          $documentXml);
$zip->addFromString('word/styles.xml',            $stylesXml);
$zip->addFromString('word/_rels/document.xml.rels', $relsXml);
$zip->close();

echo "planning.docx aangemaakt!\n";
