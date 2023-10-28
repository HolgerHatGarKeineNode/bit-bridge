<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Bit-Bridge</title>

    <!-- Styles -->
    @vite('resources/css/app.css')
    <style>
        /* inconsolata-200 - latin */
        @font-face {
            font-display: swap; /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Inconsolata';
            font-style: normal;
            font-weight: 200;
            src: url('/fonts/inconsolata-v32-latin-200.woff2') format('woff2'); /* Chrome 36+, Opera 23+, Firefox 39+, Safari 12+, iOS 10+ */
        }

        /* inconsolata-300 - latin */
        @font-face {
            font-display: swap; /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Inconsolata';
            font-style: normal;
            font-weight: 300;
            src: url('/fonts/inconsolata-v32-latin-300.woff2') format('woff2'); /* Chrome 36+, Opera 23+, Firefox 39+, Safari 12+, iOS 10+ */
        }

        /* inconsolata-regular - latin */
        @font-face {
            font-display: swap; /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Inconsolata';
            font-style: normal;
            font-weight: 400;
            src: url('/fonts/inconsolata-v32-latin-regular.woff2') format('woff2'); /* Chrome 36+, Opera 23+, Firefox 39+, Safari 12+, iOS 10+ */
        }

        /* inconsolata-500 - latin */
        @font-face {
            font-display: swap; /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Inconsolata';
            font-style: normal;
            font-weight: 500;
            src: url('/fonts/inconsolata-v32-latin-500.woff2') format('woff2'); /* Chrome 36+, Opera 23+, Firefox 39+, Safari 12+, iOS 10+ */
        }

        /* inconsolata-600 - latin */
        @font-face {
            font-display: swap; /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Inconsolata';
            font-style: normal;
            font-weight: 600;
            src: url('/fonts/inconsolata-v32-latin-600.woff2') format('woff2'); /* Chrome 36+, Opera 23+, Firefox 39+, Safari 12+, iOS 10+ */
        }

        /* inconsolata-700 - latin */
        @font-face {
            font-display: swap; /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Inconsolata';
            font-style: normal;
            font-weight: 700;
            src: url('/fonts/inconsolata-v32-latin-700.woff2') format('woff2'); /* Chrome 36+, Opera 23+, Firefox 39+, Safari 12+, iOS 10+ */
        }

        /* inconsolata-800 - latin */
        @font-face {
            font-display: swap; /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Inconsolata';
            font-style: normal;
            font-weight: 800;
            src: url('/fonts/inconsolata-v32-latin-800.woff2') format('woff2'); /* Chrome 36+, Opera 23+, Firefox 39+, Safari 12+, iOS 10+ */
        }

        /* inconsolata-900 - latin */
        @font-face {
            font-display: swap; /* Check https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display for other options. */
            font-family: 'Inconsolata';
            font-style: normal;
            font-weight: 900;
            src: url('/fonts/inconsolata-v32-latin-900.woff2') format('woff2'); /* Chrome 36+, Opera 23+, Firefox 39+, Safari 12+, iOS 10+ */
        }
    </style>

    <wireui:scripts />
</head>
<body class="antialiased bg-gray-900">
{{ $slot }}
</body>
</html>
