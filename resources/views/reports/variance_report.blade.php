<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Variance Report</title>
    <style media="screen">
        body {
            font-family: 'Segoe UI', 'Microsoft Sans Serif', sans-serif;
            font-size: 12px;
        }

        /*
            These next two styles are apparently the modern way to clear a float. This allows the logo
            and the word "Invoice" to remain above the From and To sections. Inserting an empty div
            between them with clear:both also works but is bad style.
            Reference:
            http://stackoverflow.com/questions/490184/what-is-the-best-way-to-clear-the-css-style-float
        */
        header:before,
        header:after {
            content: " ";
            display: table;
        }

        header:after {
            clear: both;
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
            width: 100%;
            font-size: 11px;
            text-align: center;
        }

        .body1 th {
            border-bottom: 1px solid #000;
            padding: 5px 0px 5px 0px;
            text-align: center;
        }

        .body1 td {
            padding: 5px;
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
            page-break-before: avoid;
        }

        .page-break:last-child {
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
        }

        span.span-text {
            position: absolute;
            z-index: 1;
        }
    </style>
</head>

<body>
    <header>
        <div class="container" style="max-width: 100%">
            <div class="row" style="flex-wrap: wrap;">
                <div class=""
                    style="text-align: left; width: 400px; flex-basis: 0; flex-grow: 1; float: left; margin-bottom: 10px;">
                    <h4>INVENTORY COUNT CONSOLIDATION SYSTEM </h4>
                    @if($data['business_unit'] != 'null')
                    <h4>{{ $data['business_unit']}}</h4>
                    @endif</th>
                    <h4>
                        @if($data['department'] != 'null')
                        {{$data['department']}}
                        @endif
                        @if($data['section'] != 'null')
                        - {{$data['section']}}
                        @endif
                    </h4>
                    <h4>As of {{ $data['date']}}</h4>
                    <h4>Batch Date: {{ $data['date']}}</h4>
                    <h4>Actual Count Date: {{ $data['date']}}</h4>
                    <h4>Count Type: Annual</h4>
                </div>
                <div class="" style="width: 1000px; flex-basis: 0; flex-grow: 1; margin-left: 110px;">
                    <div class="title1" style="text-align: center;">
                        VARIANCE REPORT
                    </div>
                </div>
                <div class="" style="max-width: 100%; flex-basis: 0; flex-grow: 1;"></div>
            </div>
        </div>


    </header>
    {{-- {{dd($data)}} --}}
    @php
    $countSize = count($data['data']);
    @endphp
    @foreach ($data['data'] as $vendor_name => $categories)
    @php
    --$countSize;
    @endphp
    <div>
        <h4 style="text-align: left; font-size: 12px">Vendor: {{ $vendor_name }}</h4>
    </div>

    @foreach ($categories as $category => $items)
    {{-- {{dd($category)}} --}}
    <div>
        <h4 style="text-align: left; font-size: 12px">Category: {{ $category }}</h4>
    </div>

    <table class="body1">
        <thead>
            <tr>
                <th rowspan="2" class="text-center" style="vertical-align: middle;">
                    Item Code
                </th>
                <th rowspan="2" class="text-center" style="vertical-align: middle;">
                    Barcode
                </th>
                <th rowspan="2" class="text-center" style="vertical-align: middle;">
                    Description
                </th>
                <th rowspan="2" class="text-center" style="vertical-align: middle;">
                    Uom
                </th>
                <th colspan="3" class="text-center" style="vertical-align: middle;">
                    Quantity
                </th>
                <th rowspan="2" class="text-center" style="vertical-align: middle;">
                    Variance
                </th>

            </tr>
            <tr>
                <th class="text-center" style="vertical-align: middle;">
                    NAV</th>
                <th class="text-center" style="vertical-align: middle;">
                    UNPOSTED</th>
                <th class="text-center" style="vertical-align: middle;">
                    P COUNT APP
                </th>
            </tr>
        </thead>
        <tbody>
            <?php  
            $grandTotal = 0;
            $variance = 0;
             ?>
            @foreach ($items as $key => $item)
            {{-- {{dd($item)}} --}}
            @php
            if($item['nav_qty'] == '-') $variance = $item['total_conv_qty'];

            else if($item['nav_qty'] < 0){ $variance=$item['total_conv_qty'] + ($item['nav_qty']); } else{
                $variance=$item['total_conv_qty'] - ($item['nav_qty']); } @endphp <tr>
                <td style="text-align: center;">{{ $item['itemcode'] }}
                </td>
                <td style="text-align: center;">{{ $item['barcode'] }}
                </td>
                <td style="text-align: left;">{{ $item['extended_desc'] }}</td>
                <td style="text-align: center;">{{ $item['nav_uom'] ?: 'PCS' }}</td>
                <td style="text-align: center;">
                    {{ is_numeric($item['nav_qty']) ? number_format($item['nav_qty'], 0) : $item['nav_qty'] }}
                </td>
                <td style="text-align: center;">-</td>
                <td style="text-align: center;">{{ number_format($item['total_conv_qty'], 0) }}</td>
                <td style="text-align: center;">{{ number_format($variance, 0) }}
                </td>
                </tr>
                <?php
             $grandTotal += $variance;
            ?>
                @endforeach
                <tr>
                    <td colspan="7"
                        style="font-weight: bold; text-align: right; font-size: 12px; border-bottom-style: none;">
                        Grand Total >>>
                    </td>
                    <td style="text-align:center; border-bottom-style: none; border-top-style: double;"> {{
                        number_format($grandTotal, 0)}}
                    </td>
                </tr>
        </tbody>
    </table>
    @endforeach
    {{-- {{dd($data['data']);}} --}}
    {{-- @if (count($data['data']) == max($data['data']))
    <div class="page-break"></div>
    @endif --}}
    @endforeach
    <table class="body2">
        <thead style="">
            <tr>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Prepared by:
                </th>
                <th width="10%" style="text-align: left; font-size: 12px;"></th>
                <th width="30%" style="text-align: left; font-size: 12px;">Checked By:</th>
            </tr>
            <tr>
                <th width="30%" style="text-align: left; font-size: 12px; border-bottom: 1px black solid">
                    {{-- {{dd($data);}} --}}
                    {{$data['user']}}
                </th>
                <th width="10%" style="text-align: left; font-size: 12px;"></th>
                <th width="30%" style="text-align: left; font-size: 12px; border-bottom: 1px black solid">
                    {{-- Test --}}
                </th>
            </tr>
            <tr>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    (Signature over printed name)
                </th>
                <th width="10%" style="text-align: left; font-size: 12px;"></th>
                <th width="30%" style="text-align: left; font-size: 12px;">(Signature over printed name)</th>
            </tr>
            <tr>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Designation: {{$data['user_position']}}
                </th>
                <th width="10%" style="text-align: left; font-size: 12px;"></th>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Designation:
                </th>
            </tr>
            <tr>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Date:
                </th>
                <th width="10%" style="text-align: left; font-size: 12px;"></th>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Date:
                </th>
            </tr>
            <tr>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Time:
                </th>
                <th width="10%" style="text-align: left; font-size: 12px;"></th>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Time:
                </th>
            </tr>
        </thead>
    </table>
    {{-- @if (count($data['data']) > 1) --}}
    @if (count($data['data']) != 0)
    <div class="page-break"></div>
    @endif

    {{-- {{dump( $vendor_name)}} --}}
    {{-- {{$data['runDate']}} --}}


    {{-- {{dd($data['data'])}} --}}
</body>
<script type="text/php">
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
</script>

</html>