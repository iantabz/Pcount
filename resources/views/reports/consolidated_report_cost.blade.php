<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consolidate Report w/ Cost</title>
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
    </style>
</head>

<body>

    <header>
        <h4 style="margin-bottom: 3px;">INVENTORY COUNT CONSOLIDATION SYSTEM </h4>
        <h4 style="margin: 0px; margin-bottom: 3px;">As of {{ $data['date']}}</h4>
        <h4 style="margin: 0px; margin-bottom: 3px;"> Batch Date: {{ $data['date']}}</h4>
        <div class="title1">
            CONSOLIDATED REPORT w/ COST
        </div>
    </header>
    {{-- {{dd($data)}} --}}
    @foreach ($data['data'] as $vendor_name =>$categories)
    {{-- {{dd($categories)}} --}}
    <div>
        <h4 style="text-align: left; font-size: 12px">Vendor: {{ $vendor_name }}</h4>
    </div>

    @foreach ($categories as $category => $items)
    {{-- {{dd($items->first()->stores)}} --}}
    <div>
        <h4 style="text-align: left; font-size: 12px">Category: {{ $category }}</h4>
    </div>

    <table class="body1">
        <thead>

            <tr>
                <th rowspan="2" class="text-center" style="vertical-align: middle; text-align:center">
                    Item Code
                </th>
                <th rowspan="2" class="text-center" style="vertical-align: middle; text-align:center">
                    Description
                </th>
                <th rowspan="2" class="text-center" style="vertical-align: middle; text-align:center">
                    UOM
                </th>
                <th colspan="2" class="text-center" style="vertical-align: middle; text-align:center">
                    Cost
                </th>
                <th colspan="{{ count($items->first()->stores) }}" class="text-center"
                    style="vertical-align: middle; text-align:center">
                    QTY per Store
                </th>
                <th rowspan="2" class="text-center" style="vertical-align: middle; text-align:center">
                    Total Qty
                </th>
                <th colspan="2" class="text-center" style="vertical-align: middle; text-align:center">
                    Total Cost
                </th>
            </tr>
            <tr>
                <th class="text-center" style="vertical-align: middle; text-align:center">
                    w/ Vat
                </th>
                <th class="text-center" style="vertical-align: middle; text-align:center">
                    w/o Vat
                </th>
                {{-- {{dd($items->first()->stores)}} --}}
                @foreach($items->first()->stores as $store => $count)
                <th class="text-center" style="vertical-align: middle; text-align:center">
                    {{$store}}
                </th>
                @endforeach
                <th class="text-center" style="vertical-align: middle; text-align:center">
                    w/ Vat
                </th>
                <th class="text-center" style="vertical-align: middle; text-align:center">
                    w/o Vat
                </th>
            </tr>
        </thead>
        <tbody>
            @php
            $total = [];
            $x = [];
            $grandTotalQty = 0;
            $grandTotalCostVat = 0;
            $grandTotalCostNoVat = 0;
            @endphp

            @foreach ($items as $test => $item)

            @php

            foreach ($item->stores as $store => $qty) {
            $total[$store] = 0;
            $total[$store] += (float) $qty;
            }

            $x[] = $total;
            @endphp

            <tr>
                <td style="text-align: center;">{{ $item->item_code }}
                </td>
                <td style="text-align: left;">{{ $item->extended_desc }}</td>
                <td style="text-align: center;">{{ $item->uom }}</td>
                <td style="text-align: right;">{{ number_format($item->cost_vat ? $item->cost_vat : 0, 2) }}</td>
                <td style="text-align: right;">{{ $item->cost_no_vat }}</td>
                @foreach ($item->stores as $qty)
                <td style="text-align: center;">{{number_format($qty, 0)}}</td>
                @endforeach
                <td style="text-align: center;">{{ number_format($item->totalQty, 0) }}</td>
                <td style="text-align: right;">{{ number_format($item->totalCostVat, 2) }}</td>
                <td style="text-align: right;">{{ number_format($item->totalCostNoVat, 2) }}</td>

            </tr>
            <?php
            $grandTotalQty += $item->totalQty;
            $grandTotalCostVat += $item->totalCostVat;
            $grandTotalCostNoVat += $item->totalCostNoVat;
            ?>
            @endforeach
            <td colspan="5" style="font-weight: bold; text-align: right; font-size: 12px; border-bottom-style: none;">
                GRAND TOTAL >>>>
            </td>
            @foreach ($item->stores as $store => $qty)
            <td style="text-align: center; border-bottom-style: none; border-top-style: double;">
                {{ number_format(collect($x)->sum($store), 0) }}</td>
            @endforeach
            <td style="text-align:center; border-bottom-style: none; border-top-style: double;">
                {{ number_format($grandTotalQty, 0)}}
            </td>
            <td style="text-align:right; border-bottom-style: none; border-top-style: double;">
                {{ number_format($grandTotalCostVat, 2)}}
            </td>
            <td style="text-align:right; border-bottom-style: none; border-top-style: double;">
                {{ number_format($grandTotalCostNoVat, 2)}}
            </td>
            </tr>
        </tbody>
    </table>
    @endforeach
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
    {{-- {{dd($data)}} --}}
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