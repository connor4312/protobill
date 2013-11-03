<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ Config::get('protobill.pageTitle') }}</title>
    {{ HTML::style('//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,900') }}
    {{ HTML::style('/css/core.css') }}
    {{ HTML::style('/css/style.css') }}
    {{ HTML::style('/css/admin.css') }}
    <script type="text/javascript">
    	window.baseurl = '{{ rtrim(Config::get('app.url'), '/') }}';
    	window.basepath = window.baseurl.replace(/^(http|https):\/\/[0-9A-z\.]+/g, '');
    </script>
    {{ HTML::script('/lib/require.js', array('data-main' => URL::to('/js/gateway.js'))) }}
</head>
<body>
    <div id="page">
        
    </div>
</body>
</html>