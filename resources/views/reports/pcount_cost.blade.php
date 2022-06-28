<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Actual Count (APP) with Cost</title>
    <style>
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
{{-- {{dd($data)}} --}}

<body>

    @foreach ($data['data'] as $app_user => $audit)

    @foreach ($audit as $auditor => $vendor)

    @foreach ($vendor as $vendor_name => $categories)

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
                        ACTUAL COUNT (APP) w/ COST
                    </div>
                </div>
                <div class="" style="max-width: 100%; flex-basis: 0; flex-grow: 1;"></div>
            </div>
        </div>
    </header>
    {{-- {{dd($data)}} --}}
    @php
    $tot_novat = 0;
    $grandTotalQty = 0;
    $grandTotalNavQty = 0;
    $grandTotal = 0;
    @endphp
    
    {{-- {{dd($categories)}} --}}
    <div>
        <h4 style="text-align: left; font-size: 12px">Vendor: {{ $vendor_name }}</h4>
    </div>
    @foreach ($categories as $category => $items)
    {{-- {{dd($category)}} --}}
    <div>
        <h4 style="text-align: left; font-size: 12px">Category: {{ $category }}</h4>
    </div>

    {{-- {{dd($categories)}} --}}
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
                <th rowspan="2" class="text-center" style="vertical-align: middle;">
                    Count
                </th>
                <th rowspan="2" class="text-center" style="vertical-align: middle;">
                    Smallest SKU
                </th>
                <th rowspan="2" class="text-center" style="vertical-align: middle;">
                    Conv. Qty
                </th>
                <th rowspan="2" class="text-center" style="vertical-align: middle;">
                    Rack
                </th>
                <th colspan="2" class="text-center" style="vertical-align: middle;">
                    Cost
                </th>
                <th rowspan="2" class="text-center" style="vertical-align: middle;">
                    Total
                </th>
            </tr>
            <tr>
                <th class="text-center" style="vertical-align: middle;">
                    w/ VAT
                </th>
                <th class="text-center" style="vertical-align: middle;">
                    w/o VAT
                </th>
            </tr>
        </thead>
        <tbody>
            @if(!$data['data'])
            <tr>
                <td colspan="5" style="text-align: center">No data available.</td>
            </tr>
            @endif
            @php
            $skus =[];
            @endphp
            @foreach ($items as $key => $item)
            {{-- {{dd($item)}} --}}
            @php
            $firstItems = reset($items);
            $lastItems = end($items);

            $skus[] = $item['uom'];
            $countStart = date("Y-m-d h:i:s a", strtotime($firstItems['datetime_scanned']));
            $countEnd = date("Y-m-d h:i:s a", strtotime($lastItems['datetime_exported']));
            $tot_novat = $item['total_conv_qty'] * $item['cost_novat'];
            $grandTotalQty += $item['total_qty'];
            $grandTotalNavQty += $item['total_conv_qty'];
            $grandTotal += $tot_novat;
            @endphp
            <tr>
                <td style="text-align: center;">{{ $item['itemcode'] }}
                </td>
                <td style="text-align: center;">{{ $item['barcode'] }}</td>
                <td style="text-align: left;">{{ $item['extended_desc'] }}</td>
                <td style="text-align: center;">{{ $item['uom'] }}</td>
                <td style="text-align: center;">{{ number_format($item['total_qty'], 0) }}</td>
                <td style="text-align: center;">{{ $item['nav_uom'] }}</td>
                <td style="text-align: center;">{{ number_format($item['total_conv_qty'], 0) }}</td>
                <td style="text-align: center;">{{ $item['rack_desc'] }}</td>
                <td style="text-align: right;">{{ $item['cost_vat'] ? $item['cost_vat'] : '-' }}</td>
                <td style="text-align: right;">{{ number_format($item['cost_novat'], 5) }}</td>
                <td style="text-align: right;">{{ number_format($tot_novat, 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4"
                    style="font-weight: bold; text-align: right; font-size: 12px; border-bottom-style: none;">
                    GRAND TOTAL >>>>
                </td>
                <td style="text-align:center; border-bottom-style: none; border-top-style: double;">
                    {{ number_format($grandTotalQty, 0)}}</td>
                <td colspan="" style="text-align:center; border-bottom-style: none;">

                </td>
                <td colspan="" style="text-align:center; border-bottom-style: none; border-top-style: double;">
                    {{number_format($grandTotalNavQty, 0)}}
                </td>
                <td colspan="3"
                    style="font-weight: bold; text-align: right; font-size: 12px; border-bottom-style: none;">

                </td>
                <td style="text-align:right; border-bottom-style: none; border-top-style: double;">
                    {{ number_format($grandTotal, 2)}}</td>
            </tr>
        </tbody>
    </table>
    @endforeach
    <table class="body2">
        <thead style="">
            <tr>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Prepared by:
                </th>
                <th width="10%"></th>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    <span class="span-text">
                        Store Representative:
                    </span>
                </th>
                <th width="10%"></th>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    <span class="span-text">Verified by:</span>
                </th>
            </tr>
            <tr>
                <th width="30%" style="text-align: left; font-size: 12px;">

                    <br />
                    {{$data['user']}}
                </th>
                <th width="10%"></th>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    <img src="data:image/png;base64,{{$item['app_user_sign']}}" class="img-tabz" />
                    {{-- <img src="data:image/jpg;base64,{{$item['app_user_sign']}}" style="" /> --}}
                    {{-- <img src="data:image/xml;base64,{{$item['app_user_sign']}}" class="img-tabz" /> --}}
                    {{-- <img
                        src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDE2LjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHdpZHRoPSIyNHB4IiBoZWlnaHQ9IjI0cHgiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMjQgMjQiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnPgoJCTxnPgoJCQk8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNMi40LDE3LjRIMi4zQzIuMiwxNy40LDIsMTcuMywyLDE3LjFjLTAuNy0xLjUtMS4xLTMuMS0xLjItNC44VjEydi0wLjNDMC44LDEwLDEuMiw4LjQsMiw2LjkKCQkJCUMyLjQsNiwzLDUuMiwzLjYsNC40YzIuMi0yLjUsNS40LTQsOC43LTRjMi44LDAsNS40LDEsNy42MDEsMi45YzAsMC4yLDAuMSwwLjMsMC4xLDAuNGMwLDAuMSwwLDAuMy0wLjEsMC40bC0zLjIsMy4yCgkJCQljLTAuMiwwLjItMC41LDAuMi0wLjcsMEMxNC45LDYuNSwxMy42LDYsMTIuMiw2QzExLDYsOS43LDYuNCw4LjcsNy4xYy0xLDAuNy0xLjgsMS44LTIuMiwzYy0wLjIsMC41LTAuMywxLjEtMC4zLDEuNgoJCQkJYzAsMC4xLDAsMC4yLDAsMC4zczAsMC4yLDAsMC4yYzAsMC42LDAuMSwxLjEsMC4zLDEuNmMwLjEsMC4yLDAsMC40LTAuMiwwLjYwMWwtMy43LDIuOEMyLjYsMTcuMywyLjUsMTcuNCwyLjQsMTcuNHogTTEyLjIsMS41CgkJCQljLTMuMSwwLTYsMS4zLTcuOSwzLjZDMy43LDUuOCwzLjIsNi41LDIuOCw3LjNjLTAuNywxLjQtMSwyLjgtMS4xLDQuNFYxMnYwLjNjMCwxLjMsMC4zLDIuNjAxLDAuOCwzLjhMNS40LDEzLjkKCQkJCWMtMC4xLTAuNS0wLjItMS0wLjItMS41YzAtMC4xLDAtMC4yLDAtMC4zczAtMC4yLDAtMC4zYzAtMC43LDAuMS0xLjMsMC4zLTEuOUM2LDguNSw2LjgsNy4zLDgsNi40YzIuNC0xLjcsNS43LTEuOCw4LjEtMC4xCgkJCQlsMi41LTIuNUMxNi45LDIuMywxNC42LDEuNSwxMi4yLDEuNXoiLz4KCQk8L2c+CgkJPGc+CgkJCTxwYXRoIGZpbGw9IiNGRkZGRkYiIGQ9Ik0xMi4yLDIzLjVjLTMuMywwLTYuNS0xLjQtOC43LTRjLTAuNy0wLjgtMS4yLTEuNi0xLjYtMi40Yy0wLjEtMC4xOTksMC0wLjUsMC4xLTAuNmwzLjctMi44CgkJCQlDNS44LDEzLjYsNiwxMy42LDYuMiwxMy42YzAuMiwwLDAuMywwLjIsMC4zLDAuMzAxYzAuNCwxLjE5OSwxLjEsMi4xOTksMi4yLDNjMC40LDAuMywwLjksMC42LDEuNCwwLjgKCQkJCWMwLjcsMC4zLDEuNCwwLjM5OSwyLjIsMC4zOTljMS4zLDAsMi41LTAuMywzLjQtMC44OTljMC44OTktMC42MDEsMS41LTEuNCwxLjg5OS0yLjRoLTUuM2MtMC4zLDAtMC41LTAuMi0wLjUtMC41VjEwCgkJCQljMC0wLjMsMC4yLTAuNSwwLjUtMC41aDEwLjNjMC4yLDAsMC40LDAuMiwwLjUsMC40YzAuMiwwLjcsMC4zMDEsMS41LDAuMzAxLDIuMWMwLDEuOS0wLjMwMSwzLjYtMSw1LjIKCQkJCWMtMC42MDEsMS4zLTEuNCwyLjUtMi41LDMuNWMtMS4yLDEuMS0yLjgwMSwyLTQuNCwyLjVDMTQuMywyMy40LDEzLjMsMjMuNSwxMi4yLDIzLjV6IE0zLDE3YzAuNCwwLjcsMC44LDEuMywxLjMsMS45CgkJCQljMiwyLjMsNC45LDMuNiw4LDMuNmMxLDAsMS45LTAuMSwyLjgtMC40YzEuNS0wLjM5OSwyLjktMS4xOTksNC0yLjE5OUMyMCwxOSwyMC44LDE4LDIxLjMsMTYuOGMwLjYwMS0xLjM5OSwxLTMsMS00LjgKCQkJCWMwLTAuNS0wLjEtMS0wLjItMS41SDEyLjd2My4zSDE4LjFjMC4yLDAsMC4zMDEsMC4xMDEsMC40LDAuMnMwLjEsMC4zLDAuMSwwLjRjLTAuMywxLjUtMS4xOTksMi44LTIuNSwzLjYKCQkJCUMxNSwxOC43LDEzLjcsMTksMTIuMiwxOWMtMC45LDAtMS43LTAuMi0yLjUtMC41Yy0wLjYtMC4yLTEuMS0wLjUtMS42LTAuOWMtMS0wLjY5OS0xLjgtMS42OTktMi4zLTIuOEwzLDE3eiIvPgoJCTwvZz4KCTwvZz4KCTxnPgoJCTxwYXRoIGZpbGw9IiNGRkZGRkYiIGQ9Ik02LjEsMTAuNWMtMC4xLDAtMC4yLDAtMC4zLTAuMUwyLjEsNy41QzEuOCw3LjQsMS44LDcsMiw2LjhjMC4yLTAuMiwwLjUtMC4zLDAuNy0wLjFsMy43LDIuOAoJCQljMC4yLDAuMiwwLjMsMC41LDAuMSwwLjdDNi40LDEwLjQsNi4yLDEwLjUsNi4xLDEwLjV6Ii8+Cgk8L2c+Cgk8Zz4KCQk8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNMTkuNCwyMC44Yy0wLjEwMSwwLTAuMiwwLTAuMzAxLTAuMUwxNS42LDE4Yy0wLjE5OS0wLjItMC4zLTAuNS0wLjEtMC43czAuNS0wLjMsMC43LTAuMWwzLjUsMi43CgkJCWMwLjIsMC4xOTksMC4zLDAuNSwwLjEsMC42OTlDMTkuNywyMC43LDE5LjYsMjAuOCwxOS40LDIwLjh6Ii8+Cgk8L2c+Cgk8Zz4KCQk8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNMTkuNSwxNC44aC01LjljLTAuMywwLTAuNS0wLjItMC41LTAuNXMwLjItMC41LDAuNS0wLjVoNS45YzAuMywwLDAuNSwwLjIsMC41LDAuNVMxOS44LDE0LjgsMTkuNSwxNC44eiIKCQkJLz4KCTwvZz4KPC9nPgo8L3N2Zz4="
                        style="background-color:rgb(0, 0, 0); color:black;">
                    --}}
                    <br />
                    <span class="span-text">{{$app_user}}</span>
                </th>
                <th width=" 10%"></th>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    <img src="data:image/png;base64,{{$item['audit_user_sign']}}" class="img-tabz" />
                    <br />
                    {{$auditor}}
                </th>
            </tr>
            <tr>
                <th width="30%" style="text-align: left; font-size: 12px; border-top: 1px black solid;">
                    (Signature over printed name)
                </th>
                <th width="10%"></th>
                <th width="30%" style="text-align: left; font-size: 12px; border-top: 1px black solid;">
                    (Signature over printed name)
                </th>
                <th width="10%"></th>
                <th width="30%" style="text-align: left; font-size: 12px; border-top: 1px black solid;">
                    (Signature over printed name)
                </th>
            </tr>
            <tr>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Designation: {{$data['user_position']}}
                </th>
                <th width="10%"></th>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Designation: {{$item['app_user_position']}}
                </th>
                <th width="10%"></th>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Designation: {{$item['audit_position']}}
                </th>
            </tr>
            <tr>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Date:
                </th>
                <th width="10%"></th>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Date: {{date("Y-m-d", strtotime($item['datetime_exported']))}}
                </th>
                <th width="10%"></th>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Date: {{date("Y-m-d", strtotime($item['datetime_exported']))}}
                </th>
            </tr>
            <tr>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Time:
                </th>
                <th width="10%"></th>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Time: {{date("h:i A", strtotime($item['datetime_exported']))}}
                </th>
                <th width="10%"></th>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Time: {{date("h:i A", strtotime($item['datetime_exported']))}}
                </th>
            </tr>
            <tr>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Count Start: {{ $countStart }}
                </th>

            </tr>
            <tr>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Count End: {{ $countEnd }}
                </th>
            </tr>
            {{-- <tr>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    Count Time: {{$countEnd}}
                </th>
            </tr> --}}
            <tr>
                <th width="30%" style="text-align: left; font-size: 12px;">
                    SKU Total: {{join(", ",array_unique($skus))}}
                </th>
            </tr>
        </thead>
    </table>
    <div class="page-break"></div>
    @endforeach
    @endforeach
    @if (count($data['data']) > 1)
    <div class="page-break"></div>
    @endif
    @endforeach
</body>
{{-- {{dd($data)}} --}}
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