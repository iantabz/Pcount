<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consolidated Report</title>
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


        /* .row {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .col {
            position: relative;
            width: 100%;
            min-height: 1px;
            padding-left: 15px;
            padding-right: 15px;
            flex-basis: 0;
            flex-grow: 1;
            max-width: 100%;
        }

        .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        } */

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <header>
        <h4 style="margin-bottom: 3px;">INVENTORY COUNT CONSOLIDATION SYSTEM </h4>
        <h4 style="margin: 0px;">As of {{ $data['date']}}</h4>
        {{-- <h4 style="margin: 0px;">Batch Date: {{ $data['date']}}</h4> --}}
        <div class="title1">
            Consolidated Report
        </div>
    </header>
    {{-- {{dd($data)}} --}}

    @foreach ($data['data'] as $vendor_name => $categories)
    {{-- {{dd($vendor)}} --}}
    {{-- <div> --}}
        <h4 style="text-align: left; font-size: 12px; margin-block-end: 0px;">Vendor: {{ $vendor_name }}</h4>
        {{--
    </div> --}}


    @foreach ($categories as $category => $items)
    {{-- {{dd($items->first()->stores)}} --}}
    {{-- <div> --}}
        <h4 style="text-align: left; font-size: 12px; margin-block-start: 1em">Category: {{ $category }}</h4>
        {{--
    </div> --}}
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

                <th colspan="{{ count($items->first()->stores) }}" class="text-center"
                    style="vertical-align: middle; text-align:center">
                    QTY per Store
                </th>
                <th rowspan="2" class="text-center" style="vertical-align: middle; text-align:center">
                    Total Qty
                </th>
            </tr>
            <tr>
                @foreach($items->first()->stores as $store => $count)
                <th class="text-center" style="vertical-align: middle; text-align:center">

                    {{$store}}
                </th>
                @endforeach
                {{-- {{ dd($vendor->first()->stores)}} --}}
            </tr>
        </thead>
        <tbody>
            @php
            $total = [];
            $x = [];
            $grandTotal = 0;
            @endphp

            @foreach ($items as $test => $item)
            {{-- {{dd($test)}} --}}
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
                @foreach ($item->stores as $qty)
                <td style="text-align: center;">{{ number_format($qty, 0) }}</td>
                @endforeach
                <td style="text-align: center;">{{ number_format($item->totalQty, 0) }}</td>
            </tr>
            <?php
            $grandTotal += $item->totalQty;
            ?>
            @endforeach

            <tr>
                <td colspan="3"
                    style="font-weight: bold; text-align: right; font-size: 12px; border-bottom-style: none;">
                    GRAND TOTAL >>>>
                </td>
                @foreach ($item->stores as $store => $qty)
                <td style="text-align: center; border-bottom-style: none; border-top-style: double;">
                    {{ number_format(collect($x)->sum($store), 0) }}</td>
                @endforeach
                <td style="text-align:center; border-bottom-style: none; border-top-style: double;">
                    {{ number_format($grandTotal, 0)}}</td>
            </tr>

        </tbody>
    </table>
    @endforeach
    {{-- @if (count($data['data']) > 1)
    <div class="page-break"></div>
    @endif --}}
    @endforeach
    {{-- {{ Footer }} --}}
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