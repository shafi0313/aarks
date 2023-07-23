<li class="nav-item menu_dropdown">
    <a class="nav-link menu_dropdown-toggle {{$mp=='upgradePlan'?'active':''}}" href="#" id="navbarmenu_dropdown" role="button"
        data-toggle="menu_dropdown" aria-haspopup="true" aria-expanded="false">Upgrade Plan</a>
    <div class="menu_dropdown-menu" aria-labelledby="navbarmenu_dropdown">
        <a class="menu_dropdown-item {{$p=='upr'?'active':''}}" href="{{ route('upgrade') }}">Update Plan
            Request</a>
    </div>
</li>
<li class="nav-item menu_dropdown">
    <a class="nav-link menu_dropdown-toggle {{$mp=='setting'?'active':''}}" href="#" id="navbarmenu_dropdown" role="button"
        data-toggle="menu_dropdown" aria-haspopup="true" aria-expanded="false">Setting</a>
    <div class="menu_dropdown-menu" aria-labelledby="navbarmenu_dropdown">
        <a class="menu_dropdown-item {{$p=='mp'?'active':''}}" href="{{ route('profile.index') }}">My Profile</a>
        <a class="menu_dropdown-item {{$p=='ul'?'active':''}}" href="{{ route('profile.logo') }}">Upload Logo</a>
        <a class="menu_dropdown-item {{$p=='il'?'active':''}}" href="{{ route('invoice_layout.index') }}">Invoice Layout</a>
        <a class="menu_dropdown-item" href="#">Purchase Layout</a>
        <a class="menu_dropdown-item {{$p=='pl'?'active':''}}" href="{{ route('period_lock_index') }}">Period Lock</a>
        <a class="menu_dropdown-item" href="#">Payment List</a>
    </div>
</li>
