@component('mail::message')
<!doctype html>
<html lang="en-US">
<head>
<title>{{$offer['title']}}</title> 
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
</head>
<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
<table cellspacing="0" cellpadding="0" width="100%" border="0" background-color="#f2f3f8" style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif; padding:10px;">
        <tr>
            <td>	
				@lang('Hello') {!! $offer['user'] !!} ,
			</td>
		</tr>
		<tr>
			<td>
				{!! $offer['introLines'] !!} 
			</td>
		</tr>
		<tr>
			<td>
				@component('mail::button', ['url' => $offer['actionUrl']])
				{{ $offer['actionText'] }}
				@endcomponent
			</td>
		</tr>
		<tr>
			<td>
				{!! $offer['outroLines'] !!} 
			</td>
		</tr>
		<tr>
			<td> <br><br> 
				@lang('Regards'),<br>
				{{ config('app.name') }} 
			</td>
		</tr>
	</table>
</body>
</html>
@endcomponent