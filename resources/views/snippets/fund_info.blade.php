<div class="row-item green">
    <div class="row-line fond-type-line">
                    <span class="fond-type">
                        {{$fund->qualification_text}}
                    </span>
        <span class="info-icon">
                        <svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M4 0C3.20888 0 2.43552 0.234596 1.77772 0.674121C1.11992 1.11365 0.607234 1.73836 0.304484 2.46927C0.00173315 3.20017 -0.0774802 4.00444 0.0768607 4.78036C0.231201 5.55628 0.612165 6.26901 1.17157 6.82842C1.73098 7.38783 2.44372 7.7688 3.21964 7.92314C3.99556 8.07748 4.79983 7.99827 5.53073 7.69551C6.26164 7.39276 6.88635 6.88007 7.32588 6.22228C7.7654 5.56448 8 4.79112 8 4C7.99875 2.93952 7.57692 1.92283 6.82704 1.17295C6.07717 0.42308 5.06048 0.00125108 4 0ZM4 7.27272C3.35272 7.27272 2.71997 7.08078 2.18177 6.72117C1.64357 6.36156 1.2241 5.85043 0.976396 5.25242C0.728691 4.6544 0.66388 3.99637 0.790159 3.36152C0.916438 2.72667 1.22813 2.14353 1.68583 1.68583C2.14353 1.22813 2.72668 0.916436 3.36152 0.790157C3.99637 0.663878 4.65441 0.728689 5.25242 0.976394C5.85043 1.2241 6.36156 1.64357 6.72117 2.18177C7.08079 2.71997 7.27273 3.35271 7.27273 4C7.27167 4.86765 6.92652 5.69947 6.313 6.313C5.69947 6.92652 4.86766 7.27167 4 7.27272ZM4.36364 2.54545H3.63636V1.81818H4.36364V2.54545ZM4.36364 6.18182H3.63636V3.27273H4.36364V6.18182Z"
                                fill="#2D3A2E"/>
                        </svg>
                        <div class="icon-description">
                            {{$fund->destiny}}
                        </div>
                    </span>
    </div>
    <div class="row-line">
        <div class="row-item__heading">
            <a href="/fund/{{$fund->id}}">{{$fund->name}}</a>
        </div>
    </div>
    <div class="row-line">
        <span></span>
        <a href="/fund/{{$fund->id}}">
            <svg width="21" height="8" viewBox="0 0 21 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M20.3536 4.35356C20.5488 4.15829 20.5488 3.84171 20.3536 3.64645L17.1716 0.464468C16.9763 0.269205 16.6597 0.269205 16.4645 0.464468C16.2692 0.65973 16.2692 0.976312 16.4645 1.17157L19.2929 4L16.4645 6.82843C16.2692 7.02369 16.2692 7.34027 16.4645 7.53554C16.6597 7.7308 16.9763 7.7308 17.1716 7.53554L20.3536 4.35356ZM-4.37114e-08 4.5L20 4.5L20 3.5L4.37114e-08 3.5L-4.37114e-08 4.5Z"
                    fill="#475256"/>
            </svg>
        </a>
    </div>
</div>
