<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
</head>
<body>
<style>
    @media only screen and (max-width: 600px) {
        .inner-body {
            width: 100% !important;
        }

        .footer {
            width: 100% !important;
        }
    }

    @media only screen and (max-width: 500px) {
        .button {
            width: 100% !important;
        }
    }

    .red {
        padding: 0.5rem;
        color: #f30041;
        font-size: 20px;
    }

    .all {
        text-align: left;
        font-size: 25px;
        font-weight: bold;
    }
</style>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">

                <tr>
                    <td class="header">
                        <img src="https://dyatlovait.ru/logo-red.png" class="logo" alt="">
                    </td>
                </tr>
                <!-- Email Body -->
                <tr>
                    <td>
                        {{$text}}<br>
                        <a href="{{route('bill.view',$bill->id)}}">Перейти в счет</a>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0"
                               role="presentation">

                            <tr>
                                <td class="content-cell" align="center">
                                    Спасибо, ваш Пино.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
