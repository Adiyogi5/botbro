@extends('admin.layouts.print')
<style type="text/css">
    body {
        width: 100%;
        height: 100%;
        margin: 0 auto;
        padding: 0;
        font-size: 14px;
        background: rgb(204, 204, 204);
    }

    p {
        font-size: 15px;
        margin-bottom: 0px !important;
    }

    * {
        -webkit-print-color-adjust: exact !important;
        /* Chrome, Safari 6 – 15.3, Edge */
        color-adjust: exact !important;
        /* Firefox 48 – 96 */
        print-color-adjust: exact !important;
        /* Firefox 97+, Safari 15.4+ */
    }

    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .footer {
        background-size: 100%;
        height: 230px;
        text-align: center;
    }

    .table th,
    .table td {
        border: 1px #000 dashed !important;
        font-size: 14px !important;
        padding: 0.25rem !important;
    }

    .main-page {
        width: 210mm;
        min-height: 297mm;
        margin: 10mm auto;
        background: white;
        box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
    }

    .sub-page {
        padding: 0px;
        position: relative;
        height: 297mm;
    }

    .footer {
        position: absolute;
        bottom: 0;
        width: 95%;
    }

    @page {
        size: A4;
        margin: 0;
    }

    @media print {

        .table th,
        .table td {
            border: 1px #000 dashed !important;
            font-size: 14px !important;
            padding: 0.25rem !important;
        }

        .borderless th,
        .borderless td {
            border-left: 0px !important;
            border-right: 0px !important;
        }

        .footer {
            background-size: 100%;
            height: 260px;
            text-align: center;
        }

        p {
            font-size: 14px;
            margin-bottom: 0px;
        }

        .sub-page {
            padding: 0px;
            position: relative;
            height: 297mm;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 90%;
        }

        .footer h5 {
            position: absolute;
            bottom: 120px;
            left: 26%;
        }

        html,
        body {
            width: 100%;
            height: 297mm;
            padding: 0px 5px;
        }

        .main-page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }
</style>
@section('content')
    <div class="main-page">
        <div class="sub-page">
            <table style="width: 95%;  margin: auto 20px;">
                <tr>
                    <td>
                        <div class="row" style="padding-top:25px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                            <div class="col-5">
                                <img src="{{ asset($site_settings['logo']) }}" class="img-fluid"
                                    style="width: 250px; filter: invert(100%); -webkit-filter: invert(100%);" alt="">
                            </div>
                            <div class="col-7">
                                <b style="font-size:20px"><?= $site_settings['application_name'] ?></b><br>
                                <p><?= $site_settings['address'] ?></p>
                                <p><?= $site_settings['email'] ?></p>
                                <p><?= $site_settings['phone'] ?></p>
                            </div>
                        </div>
                        <div class="text-center" style="width: 90%; margin: 10px auto;">
                            <b style="font-size:25px;color: #0175f0!important;">Dear <?= $data['customer_name'] ?></b><br>
                            <p>Thank You for your order from <?= $site_settings['application_name'] ?>. You can check the
                                status of your order by <b>logging into your account</b> </p>
                            <p>If you have questions about your order, you can email at</p>
                            <b><?= $site_settings['email'] ?></b>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="table" style="border: 1px solid #000;">
                            <thead>
                                <tr>
                                    <th colspan="4" scope="col" style="border: none!important;">YOUR ORDER
                                        #<?= $data['invest_no'] ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="4" scope="col" style="border: none!important;">Placed on
                                        <?= date('M d, Y', strtotime($data['date'])) ?> at
                                        <?= date('h:i A', strtotime($data['created_at'])) ?></th>
                                </tr>
                                <tr>
                                    <th colspan="2" scope="col"><b>PAYMENT METHOD</b><br>
                                        @if ($data['payment_type'] == 0)
                                            {{ 'COD' }}
                                        @else
                                            <span class="small">
                                                <b>Payment Status:</b>
                                                @if ($data['payment_status'] == 0)
                                                    {{ 'Unpaid' }}
                                                @else
                                                    {{ 'Paid' }}
                                                @endif
                                            </span>
                                        @endif
                                    </th>
                                </tr>
                                <tr style="background-color: #dfdede!important;">
                                    <td colspan="3" style="text-align: right;">Rate Of Intrest </td>
                                    <td style="text-align: right; "><?= $data['rate_of_intrest'] . '%' ?></td>
                                </tr>
                                <tr style="background-color: #dfdede!important;">
                                    <td colspan="3" style="text-align: right;">Investment Amount </td>
                                    <td style="text-align: right; "><?= $data['invest_amount'] ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <!-- this row will not appear when printing -->
    <div class="row no-print">
        <div class="col-md-6">
            <script>
                function printpage() {
                    var is_chrome = function() {
                        return Boolean(window.chrome);
                    }
                    if (is_chrome) {
                        window.print();
                        /*setTimeout(function() {
                            window.close();
                        }, 10000);*/
                        //give them 10 seconds to print, then close
                    } else {
                        window.print();
                        window.close();
                    }
                }
                printpage();
            </script>
        </div>
    </div>

@endsection
