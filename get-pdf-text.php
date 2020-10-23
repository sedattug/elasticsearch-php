<?php

require_once "app/init.php";

$prerequisites = array();
$from_pdf_path = 'files/pdfs' . DIRECTORY_SEPARATOR;
/**
 * TODO: ADAPT THIS PATH TO pdfparser
 */
$pdfparser = 'app/libraries/Smalot/PdfParser/';

$prerequisites['pdfparser'] = array (
    $pdfparser.'/Parser.php',
    $pdfparser.'/Document.php',
    $pdfparser.'/Header.php',
    $pdfparser.'/PDFObject.php',
    $pdfparser.'/Element.php',
    $pdfparser.'/Encoding.php',
    $pdfparser.'/Font.php',
    $pdfparser.'/Page.php',
    $pdfparser.'/Pages.php',
    $pdfparser.'/Element/ElementArray.php',
    $pdfparser.'/Element/ElementBoolean.php',
    $pdfparser.'/Element/ElementString.php',
    $pdfparser.'/Element/ElementDate.php',
    $pdfparser.'/Element/ElementHexa.php',
    $pdfparser.'/Element/ElementMissing.php',
    $pdfparser.'/Element/ElementName.php',
    $pdfparser.'/Element/ElementNull.php',
    $pdfparser.'/Element/ElementNumeric.php',
    $pdfparser.'/Element/ElementStruct.php',
    $pdfparser.'/Element/ElementXRef.php',
    $pdfparser.'/Encoding/StandardEncoding.php',
    $pdfparser.'/Encoding/ISOLatin1Encoding.php',
    $pdfparser.'/Encoding/ISOLatin9Encoding.php',
    $pdfparser.'/Encoding/MacRomanEncoding.php',
    $pdfparser.'/Encoding/WinAnsiEncoding.php',
    $pdfparser.'/Font/FontCIDFontType0.php',
    $pdfparser.'/Font/FontCIDFontType2.php',
    $pdfparser.'/Font/FontTrueType.php',
    $pdfparser.'/Font/FontType0.php',
    $pdfparser.'/Font/FontType1.php',
    $pdfparser.'/RawData/FilterHelper.php',
    $pdfparser.'/RawData/RawDataParser.php',
    $pdfparser.'/XObject/Form.php',
    $pdfparser.'/XObject/Image.php'
);

foreach($prerequisites as $project => $includes) {
    foreach($includes as $mapping => $file) {
        require_once $file;
    }
}

$parser = new \Smalot\PdfParser\Parser();

$pdf_files = glob($from_pdf_path . "*.{pdf}", GLOB_BRACE);

foreach($pdf_files as $file) {
    $pdf_file_name = pathinfo($file)['filename'];
    echo "\n\n" . $pdf_file_name . " dosyası okunuyor...\n\n";
    if (!is_dir('files/contents/' . $pdf_file_name)) {
        mkdir( 'files/contents/' . $pdf_file_name);
    }

    $pdf = $parser->parseFile($from_pdf_path . basename($file));

    // Retrieve all pages from the pdf file.
    $pages  = $pdf->getPages();

    $sayfa = 0;
    // Loop over each page to extract text.
    foreach ($pages as $page) {
        echo $sayfa + 1 . ". sayfa (" . $pdf_file_name . ")\n";
        create_txt_file($page->getText(), $sayfa += 1, $pdf_file_name);
    }
}

function create_txt_file($page_content, $sayfa, $pdf_file_name) {
    $myfile = file_put_contents( 'files/contents/' . $pdf_file_name . '/pdf_' . $sayfa . '.txt', $page_content.PHP_EOL , FILE_APPEND | LOCK_EX);

    $search_engine_data['title'] = $pdf_file_name;
    $search_engine_data['page_content'] = $page_content.PHP_EOL;
    $search_engine_data['page_num'] = $sayfa;

    //echo '<pre>' , print_r($search_engine_data), '</pre>';

    add_to_search_engine($search_engine_data);
}

function add_to_search_engine($search_engine_data) {

    global $client;

    $indexed = $client->index([
        'index' => 'text_container',
        'type' => 'text',
        'id'    => preg_replace('/\s+/', '_', mb_strtolower($search_engine_data['title'], 'UTF-8')).'_'.$search_engine_data['page_num'],
        'body' => [
            'title' => $search_engine_data['title'] . ' - Sayfa ' . $search_engine_data['page_num'],
            'page_content' => $search_engine_data['page_content'],
            'page_num' => $search_engine_data['page_num']
        ]
    ]);

    if($indexed) {
        print_r($indexed);
    }else{
        echo "Bir hata olustu";
    }
}