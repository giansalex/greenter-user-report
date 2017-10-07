<?php
require __DIR__ . '/lib/lib.php';
require __DIR__ . '/lib/pdf/convert.php';

if (!$s->isLoggin()) {
    header('Location: login.php');
    exit();
}

if (!isset($_FILES['xml'])) {
    header('Location: index.php');
    exit();
}

function getQr(Greenter\Model\Sale\BaseSale $sale)
{
    $cl = $sale->getClient();
    $params = [
        $sale->getCompany()->getRuc(),
        $sale->getTipoDoc(),
        $sale->getSerie(),
        $sale->getCorrelativo(),
        number_format($sale->getMtoIGV(), 2, '.', ''),
        number_format($sale->getMtoImpVenta(), 2,'.',''),
        $sale->getFechaEmision()->format('Y-m-d'),
        $cl->getTipoDoc(),
        $cl->getNumDoc(),
    ];
    $content = implode('|', $params) . '|';
    ob_start();
    QRcode::png($content,false, QR_ECLEVEL_Q, 3, 0);
    $code = ob_get_clean();
    header_remove();
    return $code;
}

function getMontoLetras(\Greenter\Model\Sale\BaseSale $sale) {
    $legs = $sale->getLegends();
    foreach ($legs as $leg) {
        if ($leg->getCode() == '1000') {
            return $leg->getValue();
        }
    }
    return '';
}

function getName(\Greenter\Model\Sale\BaseSale $sale)
{
    $tipo = $sale->getTipoDoc();
    switch ($tipo) {
        case '01':
            return 'FACTURA';
        case '03':
            return 'BOLETA';
        case '07':
            return 'NOTA DE CRÉDITO';
        case '08':
            return 'NOTA DE DÉBITO';
    }

    return '';
}

$setting = $repo->getSetting($s->getUser()->getId());
$logo_path = UPLOAD_DIR . DIRECTORY_SEPARATOR . $setting->getLogo();
$logo = 'data:image/png;base64,' . base64_encode(file_get_contents($logo_path));

/**@var $inv Greenter\Model\Sale\BaseSale */
$xml = file_get_contents($_FILES['xml']['tmp_name']);
$inv = Convert::toEntity($xml);

$qr = 'data:image/png;base64,' . base64_encode(getQr($inv));
$html = Convert::toHtml($inv, [
    'logo' => $logo,
    'email' => $s->getUser()->getEmail(),
    'name' => getName($inv),
    'telefono' => '(01) 213456',
    'qrcode' =>$qr,
    'montletras' => getMontoLetras($inv),
]);

$pdf = Convert::toPdf($html);

header('Content-Type: application/pdf');
header("Content-Disposition: inline;filename='invoice.pdf'");
echo $pdf;

