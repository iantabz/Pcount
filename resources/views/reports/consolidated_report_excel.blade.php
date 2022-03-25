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

        /* 
        .row {
            display: table-row;
            page-break-inside: avoid;
        } */

        .page-break {
            page-break-after: always;
        }

        .page-break:last-child {
            page-break-after: avoid;
            page-break-before: avoid;
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
    <table>
        <thead>
            <tr>
                <th style="text-align: left; font-size: 12px;">
                    INVENTORY COUNT CONSOLIDATION SYSTEM
                </th>
            </tr>
            <tr>
                <th style="text-align: left; font-size: 12px;">
                    Consolidated Report
                </th>
            </tr>
            <tr>
                <th style="text-align: left; font-size: 12px;">
                    As of {{ $data['date']}}
                </th>
            </tr>
        </thead>
    </table>

    @foreach ($data['data'] as $vendor_name => $categories)
    {{-- <h4 style="text-align: left; font-size: 12px; margin-block-end: 0px;">Vendor: {{ $vendor_name }}</h4> --}}

    <table>
        <tr>
            <th style="text-align: left; font-size: 12px">Vendor: {{ $vendor_name }}</th>
        </tr>
    </table>

    @foreach ($categories as $category => $items)
    {{-- <h4 style="text-align: left; font-size: 12px; margin-block-start: 1em">Category: {{ $category }}</h4>
    --}}
    <table>
        <tr>
            <th style="text-align: left; font-size: 12px">Category: {{ $category }}</th>
        </tr>
    </table>

    <table class="body1">
        <thead>
            <tr>
                <th width="100%" rowspan="2" class="text-center" style="vertical-align: middle; text-align:center">
                    Item Code
                </th>
                <th width="100%" rowspan="2" class="text-center" style="vertical-align: middle; text-align:center">
                    Description
                </th>
                <th width="100%" rowspan="2" class="text-center" style="vertical-align: middle; text-align:center">
                    UOM
                </th>

                <th colspan="{{ count($items->first()->stores) }}" class="text-center"
                    style="vertical-align: middle; text-align:center">
                    QTY per Store
                </th>
                <th width="100%" rowspan="2" class="text-center" style="vertical-align: middle; text-align:center">
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
                <td width="100%" style="text-align: center;">{{ $item->item_code }}
                </td>
                <td width="100%" style="text-align: left;">{{ $item->extended_desc }}</td>
                <td width="100%" style="text-align: center;">{{ $item->uom }}</td>
                @foreach ($item->stores as $qty)
                <td width="100%" style="text-align: center;">{{ number_format($qty, 0) }}</td>
                @endforeach
                <td width="100%" style="text-align: center;">{{ number_format($item->totalQty, 0) }}</td>
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

</html>