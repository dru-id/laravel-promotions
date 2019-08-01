
<li class="navigation__sub {{ (($section=='campaign')||($section=='promotion')) ? 'navigation__sub--active' : '' }}">
    <a href=""><i class="zmdi zmdi-card-giftcard"></i> Promotions</a>
    <ul>
        <li class="{{ ($section=='campaign') ? 'navigation__active' : '' }}"><a href="{{ route('campaigns.home') }}">Campaigns</a></li>
        <li class="{{ ($section=='promotion') ? 'navigation__active' : '' }}"><a href="{{ route('promotions.home') }}">Promotions</a></li>
    </ul>
</li>
