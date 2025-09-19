@extends('layout.admin')

<body class="four-zero-four">
    <div class="four-zero-four-container">
        <div class="error-code">500</div>
        <div class="error-message">Internal Server Error</div>
        <div class="button-place">
            <a href="{{ url('admins/index') }}" class="btn btn-default btn-lg waves-effect">GO TO HOMEPAGE</a>
        </div>
    </div>
</body>