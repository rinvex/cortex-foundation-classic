<div id="navigation" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('frontarea.home') }}"><b>{{ config('app.name') }}</b></a>
        </div>
        <div class="navbar-collapse collapse">
            {!! Menu::render('frontarea.header.navigation') !!}

            <div class="navbar-right">
                {!! Menu::render('frontarea.header.language') !!}
                {!! Menu::render('frontarea.header.user') !!}
            </div>
        </div>
    </div>
</div>
