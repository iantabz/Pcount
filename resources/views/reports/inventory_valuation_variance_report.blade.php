<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Actual Count (APP)</title>
    <style>
        body {
            font-family: 'Segoe UI', 'Microsoft Sans Serif', sans-serif;
            font-size: 12px;
            /* line-height: 1.42857143; */
            color: #333;
            background-color: #fff;
        }

        /* html,
        body {
            height: 100%;
        }

        body {
            width: 100%;
        } */

        header:before,
        header:after {
            content: " ";
            display: table;
        }

        header:after {
            clear: both;
        }

        header {
            display: block;
        }

        .title1 {
            float: center;
            margin: 0 auto;
            font-size: 18px;
            width: 100%;
            font-weight: bold;
            text-align: center;
        }

        .title2 {
            float: center;
            margin: 0 auto;
            width: 100%;
            margin-top: 30px;
            /* font-size: 19px; */
            font-weight: bold;
            text-align: center;
        }

        .from {
            float: left;
        }

        .to {
            float: right;
        }

        .fromto {
            padding-top: 20px;
            width: 600px;
            font-size: 14px;
        }

        .fromto1 {
            width: 180px;
            padding-top: 20px;
            /* font-size: 14px; */
        }

        .head1 {
            /* padding-top: 20px; */
            width: 100%;
            /* font-size: 11px; */
        }

        .head1 th {
            padding: 3px;
            text-align: right;
            /* font-size: 12px; */
        }

        .endt {
            /* padding-top: 10px; */
            width: 100%;
            font-size: 11px;
            text-align: center;
        }

        .body1 {
            margin-top: 10px;
            max-width: 100%;
            font-size: 11px;
            text-align: center;
        }

        .body1 th {
            border-bottom: 1px solid #000;
            padding: 5px 0px 5px 0px;
            text-align: center;
        }

        .body1 td {
            padding: 8px;
            line-height: 1.42857143;
            border-bottom: 1px solid rgba(0, 0, 0, 0.227);
            border-bottom-style: dashed;
        }

        .body2 {
            margin-top: 5px;
            width: 100%;
            font-size: 11px;
            text-align: center;
        }

        .body2 th {
            border-bottom: 0px solid #000;
            padding: 5px 0px 5px 0px;
            text-align: center;
        }

        .body2 td {
            padding: 2px;
        }

        .fromtocontent {
            margin: 10px;
            margin-right: 15px;
        }

        .panel {
            background-color: #e8e5e5;
            padding: 7px;
        }

        .items {
            clear: both;
            display: table;
            padding: 20px;
        }

        /* Factor out common styles for all of the "col-" classes.*/
        div[class^='col-'] {
            display: table-cell;
            padding: 7px;
        }

        /*for clarity name column styles by the percentage of width */
        .col-1-10 {
            width: 10%;
        }

        .col-1-52 {
            width: 52%;
        }

        /* 
        .row {
            display: table-row;
            page-break-inside: avoid;
        } */

        .page-break {
            page-break-after: always;
        }

        .page-break:last-child {
            page-break-before: avoid;
            page-break-after: avoid;
        }


        h4 {
            margin: 3px;
        }

        img {
            display: inline-block;
            max-width: 100%;
            height: auto;
            /* vertical-align: middle; */
            /* border: 0; */
        }

        img.img-tabz {
            font-size: 190px;
            z-index: -1;
            position: absolute;
            margin-top: -14px;
            height: 100px;
            width: 180px;
        }

        span.span-text {
            position: absolute;
            z-index: 1;
        }

        div {
            display: block;
        }

        table {
            display: table;
            border-collapse: separate;
            box-sizing: border-box;
            text-indent: initial;
            border-spacing: 2px;
            border-color: grey;
        }

        thead {
            display: table-header-group;
            vertical-align: middle;
            border-color: inherit;
        }

        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }

        th {
            display: table-cell;
            vertical-align: inherit;
            font-weight: bold;
            text-align: -internal-center;
        }

        td {
            display: table-cell;
            vertical-align: inherit;
        }

        span.span-text {
            position: absolute;
            z-index: 1;
        }

        .row {
            margin-right: -15px;
            margin-left: -15px;
        }

        .col-xs-1,
        .col-sm-1,
        .col-md-1,
        .col-lg-1,
        .col-xs-2,
        .col-sm-2,
        .col-md-2,
        .col-lg-2,
        .col-xs-3,
        .col-sm-3,
        .col-md-3,
        .col-lg-3,
        .col-xs-4,
        .col-sm-4,
        .col-md-4,
        .col-lg-4,
        .col-xs-5,
        .col-sm-5,
        .col-md-5,
        .col-lg-5,
        .col-xs-6,
        .col-sm-6,
        .col-md-6,
        .col-lg-6,
        .col-xs-7,
        .col-sm-7,
        .col-md-7,
        .col-lg-7,
        .col-xs-8,
        .col-sm-8,
        .col-md-8,
        .col-lg-8,
        .col-xs-9,
        .col-sm-9,
        .col-md-9,
        .col-lg-9,
        .col-xs-10,
        .col-sm-10,
        .col-md-10,
        .col-lg-10,
        .col-xs-11,
        .col-sm-11,
        .col-md-11,
        .col-lg-11,
        .col-xs-12,
        .col-sm-12,
        .col-md-12,
        .col-lg-12 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }


        .col-lg-12 {
            width: 100%;
        }
    </style>
</head>

<body>
    {{-- {{dd($data)}} --}}
    <table style="border: 0px black;">
        <thead>
            <tr>
                <th style="text-align: left; font-size: 12px;">
                    INVENTORY COUNT CONSOLIDATION SYSTEM
                </th>
            </tr>
            <tr>
                <th style="text-align: left; font-size: 12px; font-weight: bold;">
                    INVENTORY VALUATION VARIANCE
                </th>
            </tr>
            @if($data['business_unit'] != 'null')
            <tr>
                <th style="text-align: left; font-size: 12px;">
                    {{ $data['business_unit']}}
                </th>
            </tr>
            @endif
            @if($data['department'] != 'null')
            <tr>
                <th style="text-align: left; font-size: 12px;">
                    {{$data['department']}}

                    @if($data['section'] != 'null')
                    - {{$data['section']}}
                    @endif
                </th>
            </tr>
            @endif
            <tr>
                <th style="text-align: left; font-size: 12px;">
                    As of {{ $data['date']}}
                </th>
            </tr>
        </thead>
    </table>
    
    <table class="body1">
        <thead>
            <tr>
                <th style="vertical-align: middle; text-align: center;">
                    Item Code
                </th>
                <th style="vertical-align: middle; text-align: center;">
                    Variant Code
                </th>
                <th style="vertical-align: middle; text-align: center;">
                    Description
                </th>
                <th style="vertical-align: middle; text-align: center;">
                    Uom
                </th>
                <th style="vertical-align: middle; text-align: center;">
                    Qty
                </th>
            </tr>
        </thead>
        <tbody>

            @if(!$data['data'])
            <tr>
                <td colspan="5" style="text-align: center">No data available.</td>
            </tr>
            @endif
            @foreach ($data['data'] as $key => $item)
            {{-- {{dd(end($item))}} --}}
            <tr>
                <td style="text-align: center;">{{ $item['itemcode'] }}
                </td>
                <td style="text-align: center;">{{ $item['variant_code'] }}</td>
                <td style="text-align: left;">{{ $item['description'] }}</td>
                <td style="text-align: center;">{{ $item['uom'] }}</td>
                <td style="text-align: center;">{{ number_format($item['qty'], 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
{{-- <script type="text/php">
    if ( isset($pdf) ) { 
        $pdf->page_script('
            if ($PAGE_COUNT > 1) {
                $date =now()->format("Y-m-d h:i A");
                $font = $fontMetrics->getFont("Helvetica");
                $size = 12;
                $finalPageCount = $PAGE_COUNT -1;

                $pageText = $PAGE_NUM . " of " . ($PAGE_COUNT - 1);
                $y = $pdf->get_height() - 24;
                $x = $pdf->get_width() - 15 - $fontMetrics->getTextWidth($pageText, $font, $size);
                if($PAGE_NUM <= $finalPageCount){
                    $pdf->text(35, 580, "RUN DATE & TIME: " . $date, $font, 7);
                    $pdf->text(500, 580, $pageText, $font, 8);
                }
            } 
        ');
    }
</script> --}}

</html>